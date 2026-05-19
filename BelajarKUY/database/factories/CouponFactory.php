<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'instructor_id' => User::factory()->instructor(),
            'course_id' => null,
            'code' => strtoupper(Str::random(8)),
            'discount_percent' => fake()->randomElement([10, 15, 20, 25, 30, 50]),
            'valid_until' => now()->addDays(fake()->numberBetween(7, 90))->toDateString(),
            'max_usage' => fake()->randomElement([null, 50, 100, 500]),
            'used_count' => 0,
            'status' => true,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'valid_until' => now()->subDays(1)->toDateString(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => false]);
    }
}
