<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseGoal>
 */
class CourseGoalFactory extends Factory
{
    protected $model = CourseGoal::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'goal' => fake()->randomElement([
                'Memahami konsep dasar dengan baik',
                'Mampu membuat project nyata',
                'Siap kerja di industri',
                'Memahami best practices yang digunakan perusahaan',
                'Debugging dan troubleshooting',
                'Mendapat sertifikat penyelesaian',
                'Akses seumur hidup ke materi',
            ]).' — '.fake()->words(3, true),
        ];
    }
}
