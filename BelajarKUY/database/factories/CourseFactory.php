<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(rand(3, 6));
        $title = rtrim($title, '.');

        return [
            'category_id' => Category::factory(),
            'subcategory_id' => null,
            'instructor_id' => User::factory()->instructor(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 999999),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->randomElement([99000, 149000, 199000, 299000, 499000, 799000, 1200000]),
            'discount' => fake()->randomElement([0, 0, 0, 10, 20, 30, 50]),
            'thumbnail' => 'courses/thumb-'.fake()->uuid().'.jpg',
            'video_url' => 'https://youtu.be/'.fake()->regexify('[A-Za-z0-9]{11}'),
            'duration' => fake()->numberBetween(2, 40).' jam',
            'bestseller' => false,
            'featured' => false,
            'status' => 'active',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true, 'status' => 'active']);
    }

    public function bestseller(): static
    {
        return $this->state(fn () => ['bestseller' => true, 'status' => 'active']);
    }
}
