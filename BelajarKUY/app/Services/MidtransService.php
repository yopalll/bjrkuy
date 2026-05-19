<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;
use App\Models\Coupon;

class MidtransService
{
    /**
     * Initialize Midtrans SDK configuration.
     */
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = false; // Always Sandbox for this project
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Generate Midtrans Snap Token for checkout popup.
     *
     * @param mixed $cartItems Collection of Cart items
     * @param mixed $user User model
     * @param string|null $orderId Explicit Order ID BKUY-{timestamp}-{user_id}
     * @param Coupon|null $coupon Optional validated Coupon model
     * @return string
     * @throws \Exception
     */
    public function createSnapToken($cartItems, $user, string $orderId, ?Coupon $coupon = null): string
    {
        $grossAmount = 0;
        $itemDetails = [];

        foreach ($cartItems as $item) {
            $course = $item->course;
            $price = $course->discounted_price;

            // Apply coupon discount if applicable
            if ($coupon) {
                if (is_null($coupon->course_id) || $coupon->course_id == $course->id) {
                    $discount = $price * ($coupon->discount_percent / 100);
                    $price = $price - $discount;
                }
            }

            // Ensure price is rounded to integer for Rupiah
            $finalPrice = (int) round($price);
            $grossAmount += $finalPrice;

            $itemDetails[] = [
                'id' => (string) $course->id,
                'price' => $finalPrice,
                'quantity' => 1,
                'name' => mb_substr($course->title, 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ],
            'item_details' => $itemDetails,
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Generation Failed: ' . $e->getMessage(), [
                'params' => $params,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Parse and verify HTTP notification from Midtrans.
     *
     * @return Notification
     */
    public function handleNotification(): Notification
    {
        return new Notification();
    }

    /**
     * Verify transaction status directly with Midtrans.
     *
     * @param string $orderId
     * @return object
     */
    public function verifyTransaction(string $orderId): object
    {
        return Transaction::status($orderId);
    }
}
