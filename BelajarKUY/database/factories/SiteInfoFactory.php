<?php

namespace Database\Factories;

use App\Models\SiteInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteInfo>
 */
class SiteInfoFactory extends Factory
{
    protected $model = SiteInfo::class;

    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(),
            'value' => fake()->sentence(),
        ];
    }
}
