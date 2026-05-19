<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Enrollment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Display checkout page with current cart items and optional coupon.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['course.instructor', 'course.category'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        $coupon = null;
        $discountAmount = 0;
        $subtotal = $cartItems->sum(function ($item) {
            return $item->course->discounted_price;
        });

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::active()->where('code', $request->coupon_code)->first();
            if ($coupon) {
                // Apply coupon discount (global or specific course)
                foreach ($cartItems as $item) {
                    if (is_null($coupon->course_id) || $coupon->course_id == $item->course_id) {
                        $discountAmount += $item->course->discounted_price * ($coupon->discount_percent / 100);
                    }
                }
                session()->put('applied_coupon', $coupon->code);
            } else {
                session()->forget('applied_coupon');
                return redirect()->back()
                    ->with('error', 'Kupon tidak valid atau sudah kedaluwarsa.');
            }
        } else {
            session()->forget('applied_coupon');
        }

        $total = max(0, $subtotal - $discountAmount);

        return view('frontend.checkout', compact('cartItems', 'coupon', 'subtotal', 'discountAmount', 'total'));
    }

    /**
     * Process checkout, generate Midtrans Snap Token, and return checkout screen.
     */
    public function process(Request $request): View|RedirectResponse
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['course.instructor', 'course.category'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validate coupon if provided
        $coupon = null;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::active()->where('code', $request->coupon_code)->first();
            if (!$coupon) {
                return redirect()->back()
                    ->with('error', 'Kupon tidak valid atau sudah kedaluwarsa.');
            }
        }

        // Calculate exact total amount
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $price = $item->course->discounted_price;
            if ($coupon) {
                if (is_null($coupon->course_id) || $coupon->course_id == $item->course_id) {
                    $price = $price - ($price * ($coupon->discount_percent / 100));
                }
            }
            $totalAmount += (int) round($price);
        }

        // Generate Order ID format: BKUY-{timestamp}-{user_id}
        $midtransOrderId = 'BKUY-' . time() . '-' . auth()->id();

        try {
            DB::beginTransaction();

            // 1. Create Payment record in 'pending' status BEFORE fetching Snap token
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'midtrans_order_id' => $midtransOrderId,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // 2. Fetch Snap Token from Midtrans Service
            $snapToken = $this->midtrans->createSnapToken($cartItems, auth()->user(), $midtransOrderId, $coupon);

            DB::commit();

            $clientKey = config('midtrans.client_key');

            return view('frontend.checkout-process', compact('cartItems', 'snapToken', 'clientKey', 'payment', 'coupon', 'totalAmount'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout processing failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memproses pembayaran. Silakan coba beberapa saat lagi.');
        }
    }

    /**
     * Handle asynchronous Midtrans webhook callbacks.
     */
    public function callback(Request $request): JsonResponse
    {
        try {
            $notification = $this->midtrans->handleNotification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;
            $transactionId = $notification->transaction_id;

            // Retrieve payment record created during process()
            $payment = Payment::where('midtrans_order_id', $orderId)->first();

            if (!$payment) {
                Log::warning('Payment record not found for Order ID: ' . $orderId);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Extract custom_field1 for Coupon ID if present in notification response
            // Midtrans SDK returns response as an object. We can check property or raw payload
            $midtransRaw = json_decode($request->getContent(), true);
            $couponId = $midtransRaw['custom_field1'] ?? null;

            // Update payment with Midtrans metadata
            $payment->update([
                'midtrans_transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'midtrans_response' => $midtransRaw,
            ]);

            // Handle transaction status mapping
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->handleSuccess($payment, $couponId);
                } elseif ($fraudStatus == 'challenge') {
                    $payment->update(['status' => 'pending']);
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->handleSuccess($payment, $couponId);
            } elseif ($transactionStatus == 'pending') {
                $payment->update(['status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $payment->update(['status' => $transactionStatus]);
                
                // Update corresponding orders status to cancelled if they exist
                Order::where('payment_id', $payment->id)->update(['status' => 'cancelled']);
            }

            return response()->json(['status' => 'OK']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Handling Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Process successful payment: Create Orders, Enroll student, Clear cart, update Coupon count.
     */
    private function handleSuccess(Payment $payment, $couponId = null): void
    {
        // Prevent duplicate processing
        if ($payment->status === 'settlement' || $payment->status === 'capture') {
            return;
        }

        DB::transaction(function () use ($payment, $couponId) {
            $payment->update(['status' => 'settlement']);

            // Get all cart items for this user
            $cartItems = Cart::where('user_id', $payment->user_id)
                ->with('course')
                ->get();

            $coupon = $couponId ? Coupon::find($couponId) : null;

            foreach ($cartItems as $item) {
                $course = $item->course;
                $originalPrice = $course->price;
                $discountAmount = $course->price - $course->discounted_price;
                $finalPrice = $course->discounted_price;

                // Adjust price if coupon applies to this course
                if ($coupon) {
                    if (is_null($coupon->course_id) || $coupon->course_id == $course->id) {
                        $couponDiscount = $finalPrice * ($coupon->discount_percent / 100);
                        $discountAmount += $couponDiscount;
                        $finalPrice = $finalPrice - $couponDiscount;
                    }
                }

                // Create individual Order record
                $order = Order::create([
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'course_id' => $course->id,
                    'instructor_id' => $course->instructor_id,
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'original_price' => $originalPrice,
                    'discount_amount' => $discountAmount,
                    'final_price' => $finalPrice,
                    'status' => 'completed',
                ]);

                // Create Enrollment record to grant instant access to the course
                Enrollment::firstOrCreate([
                    'user_id' => $payment->user_id,
                    'course_id' => $course->id,
                ], [
                    'order_id' => $order->id,
                    'enrolled_at' => now(),
                ]);
            }

            // Increment coupon usage count if used
            if ($coupon) {
                $coupon->increment('used_count');
            }

            // Clear Cart items only after successful order and enrollment creation
            Cart::where('user_id', $payment->user_id)->delete();
        });
    }

    /**
     * Payment Success Page.
     */
    public function success(Request $request): View
    {
        $orderId = $request->query('order_id');
        $payment = null;

        if ($orderId) {
            $payment = Payment::where('midtrans_order_id', $orderId)->first();
        }

        return view('frontend.payment-success', compact('payment'));
    }

    /**
     * Payment Failed Page.
     */
    public function failed(Request $request): View
    {
        $orderId = $request->query('order_id');
        $payment = null;

        if ($orderId) {
            $payment = Payment::where('midtrans_order_id', $orderId)->first();
        }

        return view('frontend.payment-failed', compact('payment'));
    }
}
