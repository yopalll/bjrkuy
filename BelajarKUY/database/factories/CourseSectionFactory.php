<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseSection>
 */
class CourseSectionFactory extends Factory
{
    protected $model = CourseSection::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => 'Bagian '.fake()->numberBetween(1, 20).': '.fake()->sentence(4),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
