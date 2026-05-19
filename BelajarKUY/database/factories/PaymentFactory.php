<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'midtrans_order_id' => 'BKUY-'.time().'-'.fake()->unique()->numberBetween(1000, 99999),
            'midtrans_transaction_id' => fake()->uuid(),
            'payment_type' => fake()->randomElement([
                'credit_card', 'gopay', 'shopeepay', 'bank_transfer', 'qris',
            ]),
            'total_amount' => fake()->randomElement([99000, 149000, 199000, 299000, 499000]),
            'status' => 'settlement',
            'midtrans_response' => [
                'status_code' => '200',
                'status_message' => 'Success, transaction is found',
                'fraud_status' => 'accept',
            ],
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function failed(): static
    {
        return $this->state(fn () => ['status' => 'deny']);
    }
}
