<?php

namespace Database\Factories;

use App\Models\CourseLecture;
use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseLecture>
 */
class CourseLectureFactory extends Factory
{
    protected $model = CourseLecture::class;

    public function definition(): array
    {
        return [
            'section_id' => CourseSection::factory(),
            'title' => 'Lecture '.fake()->numberBetween(1, 50).': '.fake()->sentence(5),
            'url' => 'https://youtu.be/'.fake()->regexify('[A-Za-z0-9]{11}'),
            'content' => fake()->paragraph(),
            'duration' => fake()->numberBetween(3, 45).':'.fake()->numerify('##'),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
