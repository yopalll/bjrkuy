<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Web Development',
            'Mobile Development',
            'Data Science',
            'UI/UX Design',
            'DevOps',
            'Cybersecurity',
            'Machine Learning',
            'Bahasa & Komunikasi',
            'Bisnis & Marketing',
            'Personal Development',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'image' => 'categories/'.Str::slug($name).'.png',
            'status' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => false]);
    }
}
