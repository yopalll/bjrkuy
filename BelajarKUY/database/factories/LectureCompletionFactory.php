<?php

namespace Database\Factories;

use App\Models\CourseLecture;
use App\Models\LectureCompletion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LectureCompletion>
 */
class LectureCompletionFactory extends Factory
{
    protected $model = LectureCompletion::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lecture_id' => CourseLecture::factory(),
            'completed_at' => now(),
        ];
    }
}
