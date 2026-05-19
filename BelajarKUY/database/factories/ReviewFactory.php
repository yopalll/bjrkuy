<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'rating' => fake()->numberBetween(3, 5),
            'comment' => fake()->randomElement([
                'Kursusnya bagus banget, penjelasan jelas dan materi lengkap!',
                'Sangat membantu untuk pemula. Recommended!',
                'Instruktur profesional, project-nya aplikatif.',
                'Materi lengkap dan up-to-date.',
                'Belajar jadi lebih mudah. Worth it!',
                'Penjelasan detail dan mudah dipahami.',
                'Kurikulumnya tersusun rapi, cocok untuk belajar otodidak.',
            ]),
            'status' => true,
        ];
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => false]);
    }
}
