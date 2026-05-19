<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseGoal;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Seed 15 kursus aktif dengan sections + lectures + goals.
     * 3 featured, 3 bestseller.
     */
    public function run(): void
    {
        $instructors = User::where('role', 'instructor')->get();
        $categories = Category::with('subCategories')->get();

        if ($instructors->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('UserSeeder dan CategorySeeder harus dijalankan terlebih dahulu.');
            return;
        }

        $courseSamples = [
            ['Laravel 12 untuk Pemula', 'Web Development', 'Laravel'],
            ['React + TypeScript Bootcamp', 'Web Development', 'React'],
            ['Vue.js 3 Masterclass', 'Web Development', 'Vue.js'],
            ['Flutter Clone App Studi Kasus', 'Mobile Development', 'Flutter'],
            ['Data Science dengan Python', 'Data Science', 'Python'],
            ['Machine Learning A-Z', 'Data Science', 'Machine Learning'],
            ['UI/UX Design dengan Figma', 'UI/UX Design', 'Figma'],
            ['Docker & Kubernetes Essentials', 'DevOps', 'Docker'],
            ['Ethical Hacking Fundamentals', 'Cybersecurity', 'Ethical Hacking'],
            ['Digital Marketing Modern', 'Bisnis & Marketing', 'Digital Marketing'],
            ['SEO untuk Website 2026', 'Bisnis & Marketing', 'SEO'],
            ['Public Speaking Professional', 'Personal Development', 'Public Speaking'],
            ['Next.js + Tailwind Project', 'Web Development', 'Next.js'],
            ['iOS Native Swift Basics', 'Mobile Development', 'iOS Native'],
            ['Deep Learning dengan TensorFlow', 'Data Science', 'Deep Learning'],
        ];

        foreach ($courseSamples as $i => [$title, $categoryName, $subName]) {
            $category = $categories->firstWhere('name', $categoryName);
            $subCategory = $category?->subCategories->firstWhere('name', $subName);

            $course = Course::create([
                'category_id' => $category?->id ?? $categories->random()->id,
                'subcategory_id' => $subCategory?->id,
                'instructor_id' => $instructors->random()->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => fake()->paragraphs(3, true),
                'price' => fake()->randomElement([149000, 199000, 299000, 399000, 499000]),
                'discount' => fake()->randomElement([0, 0, 10, 20, 30]),
                'thumbnail' => 'courses/'.Str::slug($title).'.jpg',
                'video_url' => 'https://youtu.be/'.fake()->regexify('[A-Za-z0-9]{11}'),
                'duration' => fake()->numberBetween(5, 40).' jam',
                'bestseller' => $i < 3,
                'featured' => $i >= 3 && $i < 6,
                'status' => 'active',
            ]);

            // --- Course Goals ---
            $goals = [
                'Memahami '.$title.' dari dasar hingga mahir',
                'Mampu membuat project nyata',
                'Siap kerja di industri',
                'Mendapat sertifikat penyelesaian',
                'Akses seumur hidup ke materi',
            ];
            foreach ($goals as $goal) {
                CourseGoal::create([
                    'course_id' => $course->id,
                    'goal' => $goal,
                ]);
            }

            // --- Sections & Lectures ---
            $sectionCount = fake()->numberBetween(3, 6);
            for ($s = 1; $s <= $sectionCount; $s++) {
                $section = CourseSection::create([
                    'course_id' => $course->id,
                    'title' => "Bagian $s: ".fake()->sentence(3),
                    'sort_order' => $s,
                ]);

                $lectureCount = fake()->numberBetween(3, 8);
                for ($l = 1; $l <= $lectureCount; $l++) {
                    CourseLecture::create([
                        'section_id' => $section->id,
                        'title' => "Lecture $l: ".fake()->sentence(5),
                        'url' => 'https://youtu.be/'.fake()->regexify('[A-Za-z0-9]{11}'),
                        'content' => fake()->paragraph(),
                        'duration' => fake()->numberBetween(3, 30).':'.fake()->numerify('##'),
                        'sort_order' => $l,
                    ]);
                }
            }
        }
    }
}
