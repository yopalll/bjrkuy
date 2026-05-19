<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\Enrollment;
use App\Models\LectureCompletion;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Seed data transaksi end-to-end:
     *   - Wishlist & Cart untuk student
     *   - Coupon per instructor
     *   - Payment → Order → Enrollment chain
     *   - Lecture completions (progress belajar)
     *   - Review untuk course yang sudah di-enroll
     */
    public function run(): void
    {
        $students = User::where('role', 'user')->get();
        $instructors = User::where('role', 'instructor')->get();
        $courses = Course::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('UserSeeder & CourseSeeder harus dijalankan terlebih dahulu.');
            return;
        }

        // --- Coupons: 1 global + 1 spesifik course per instructor ---
        foreach ($instructors as $instructor) {
            Coupon::create([
                'instructor_id' => $instructor->id,
                'course_id' => null,
                'code' => strtoupper(Str::random(6)),
                'discount_percent' => fake()->randomElement([10, 15, 20]),
                'valid_until' => now()->addDays(30)->toDateString(),
                'max_usage' => 100,
                'used_count' => fake()->numberBetween(0, 20),
                'status' => true,
            ]);

            $instructorCourse = $courses->where('instructor_id', $instructor->id)->first();
            if ($instructorCourse) {
                Coupon::create([
                    'instructor_id' => $instructor->id,
                    'course_id' => $instructorCourse->id,
                    'code' => strtoupper(Str::random(8)),
                    'discount_percent' => 25,
                    'valid_until' => now()->addDays(14)->toDateString(),
                    'max_usage' => null,
                    'used_count' => 0,
                    'status' => true,
                ]);
            }
        }

        // --- Wishlist & Cart per student ---
        foreach ($students as $student) {
            $wishlistCourses = $courses->random(min(3, $courses->count()));
            foreach ($wishlistCourses as $course) {
                Wishlist::firstOrCreate([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ]);
            }

            $cartCourses = $courses->random(min(2, $courses->count()));
            foreach ($cartCourses as $course) {
                Cart::firstOrCreate([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ]);
            }
        }

        // --- Payment → Order → Enrollment ---
        // Setiap student 1-2 pembayaran yang berhasil.
        foreach ($students as $student) {
            $paymentCount = fake()->numberBetween(1, 2);

            for ($p = 0; $p < $paymentCount; $p++) {
                $purchasedCourse = $courses->random();
                $price = (int) $purchasedCourse->price;

                $payment = Payment::create([
                    'user_id' => $student->id,
                    'midtrans_order_id' => 'BKUY-'.time().'-'.$student->id.'-'.$p,
                    'midtrans_transaction_id' => fake()->uuid(),
                    'payment_type' => fake()->randomElement(['gopay', 'bank_transfer', 'credit_card', 'qris']),
                    'total_amount' => $price,
                    'status' => 'settlement',
                    'midtrans_response' => [
                        'status_code' => '200',
                        'status_message' => 'Success',
                        'fraud_status' => 'accept',
                    ],
                ]);

                $order = Order::create([
                    'payment_id' => $payment->id,
                    'user_id' => $student->id,
                    'course_id' => $purchasedCourse->id,
                    'instructor_id' => $purchasedCourse->instructor_id,
                    'coupon_id' => null,
                    'original_price' => $price,
                    'discount_amount' => 0,
                    'final_price' => $price,
                    'status' => 'completed',
                ]);

                // firstOrCreate untuk menghindari pelanggaran UNIQUE(user_id, course_id)
                Enrollment::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'course_id' => $purchasedCourse->id,
                    ],
                    [
                        'order_id' => $order->id,
                        'enrolled_at' => now(),
                    ]
                );
            }
        }

        // --- Lecture completions untuk enrolled courses ---
        foreach (Enrollment::with('course.sections.lectures')->get() as $enrollment) {
            $lectures = $enrollment->course->sections->flatMap->lectures;
            if ($lectures->isEmpty()) {
                continue;
            }

            // Student menyelesaikan 30-70% lecture secara acak.
            $completionRatio = fake()->randomFloat(2, 0.3, 0.7);
            $lecturesToComplete = $lectures->random((int) ceil($lectures->count() * $completionRatio));

            foreach ($lecturesToComplete as $lecture) {
                LectureCompletion::firstOrCreate(
                    [
                        'user_id' => $enrollment->user_id,
                        'lecture_id' => $lecture->id,
                    ],
                    ['completed_at' => now()]
                );
            }
        }

        // --- Review: student yang sudah enroll boleh review ---
        foreach (Enrollment::all() as $enrollment) {
            // 60% chance untuk review
            if (fake()->boolean(60)) {
                Review::firstOrCreate(
                    [
                        'user_id' => $enrollment->user_id,
                        'course_id' => $enrollment->course_id,
                    ],
                    [
                        'rating' => fake()->numberBetween(3, 5),
                        'comment' => fake()->randomElement([
                            'Kursusnya bagus, penjelasan jelas dan materi lengkap!',
                            'Sangat membantu untuk pemula. Recommended!',
                            'Instruktur profesional, project-nya aplikatif.',
                            'Materi up-to-date dan mudah dipahami.',
                            'Worth it banget, belajar jadi lebih terstruktur.',
                        ]),
                        'status' => true,
                    ]
                );
            }
        }
    }
}
