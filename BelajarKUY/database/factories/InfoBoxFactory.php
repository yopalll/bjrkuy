<?php

namespace Database\Factories;

use App\Models\InfoBox;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InfoBox>
 */
class InfoBoxFactory extends Factory
{
    protected $model = InfoBox::class;

    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(10),
            'icon' => fake()->randomElement([
                'book-open', 'users', 'award', 'clock', 'globe', 'shield-check',
            ]),
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
