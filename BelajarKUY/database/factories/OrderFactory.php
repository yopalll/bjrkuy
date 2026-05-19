<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $original = fake()->randomElement([99000, 149000, 199000, 299000, 499000]);
        $discount = fake()->randomElement([0, 0, 0, 10000, 20000, 50000]);

        return [
            'payment_id' => Payment::factory(),
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'instructor_id' => User::factory()->instructor(),
            'coupon_id' => null,
            'original_price' => $original,
            'discount_amount' => $discount,
            'final_price' => max(0, $original - $discount),
            'status' => 'completed',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => 'completed']);
    }
}
