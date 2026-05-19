# 💳 F06: Payment Gateway Midtrans

> Integrasi pembayaran menggunakan Midtrans **Snap** API (popup mode — bukan redirect).
> **INI ADALAH PENGGANTI STRIPE dari project referensi YouTubeLMS.**
> ⚠️ **Project ini SELALU menggunakan Sandbox Midtrans**, bahkan saat di-deploy (project non-komersial).

---

## Overview

Midtrans Snap API menampilkan popup pembayaran yang mendukung semua metode pembayaran Indonesia:
- **E-Wallet:** GoPay, ShopeePay, Dana, OVO
- **Bank Transfer:** BCA, BNI, BRI, Mandiri, Permata
- **Credit Card:** Visa, Mastercard
- **Retail:** Alfamart, Indomaret

---

## Flow Pembayaran

```
[Checkout Page]
    ↓
[User klik "Bayar Sekarang"]
    ↓
[Controller buat Snap Token via Midtrans API]
    ↓
[Frontend tampilkan Snap Popup]
    ↓
[User pilih metode & bayar di popup Midtrans]
    ↓
[Midtrans proses pembayaran]
    ↓
[Redirect ke success/failed page]
    ↓
[Midtrans kirim Notification/Callback ke server kita]
    ↓
[Server verifikasi signature]
    ↓
[Update payment status + create orders]
```

---

## Setup Midtrans

### 1. Install Package

```bash
composer require midtrans/midtrans-php
```

### 2. Config File (`config/midtrans.php`)

```php
<?php

return [
    'server_key'    => env('MIDTRANS_SERVER_KEY', ''),
    'client_key'    => env('MIDTRANS_CLIENT_KEY', ''),
    'is_production' => false,      // SELALU false — project ini pakai sandbox
    'is_sanitized'  => true,       // Midtrans akan sanitize input
    'is_3ds'        => true,       // Wajib untuk credit card 3DS
    'merchant_id'   => env('MIDTRANS_MERCHANT_ID', ''),
];
```

> 💡 **Perhatikan:** `is_production` di-hardcode `false` (bukan dari `.env`) karena project ini **selalu sandbox**. Jika suatu hari ingin production, baru ubah ke `env()`.

### 3. Environment Variables (`.env`)

```env
# Ambil dari: https://dashboard.sandbox.midtrans.com → Settings → Access Keys
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxx
MIDTRANS_MERCHANT_ID=G000000000
# MIDTRANS_IS_PRODUCTION tidak perlu — di-hardcode false di config/midtrans.php
```

> ⚠️ Prefix `SB-` pada key menandakan ini adalah **Sandbox key**. Production key tidak ada prefix `SB-`.

---

## Implementation

### MidtransService (`app/Services/MidtransService.php`)

```php
<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        // Ref: https://github.com/Midtrans/midtrans-php#21-general-settings
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = false;   // SELALU sandbox
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Generate Snap token for checkout popup.
     * Ref: https://github.com/Midtrans/midtrans-php#22a-snap
     */
    public function createSnapToken(array $cartItems, $user, ?string $couponCode = null): string
    {
        $grossAmount = collect($cartItems)->sum('price');

        // Apply coupon jika ada
        if ($couponCode) {
            // Validasi & apply discount — implementasi di CouponService
        }

        // Order ID harus UNIK — format: BKUY-{timestamp}-{user_id}
        $orderId = 'BKUY-' . time() . '-' . $user->id;

        // item_details: price harus INTEGER (Rupiah, tanpa desimal)
        // name: max 50 karakter (Midtrans limit)
        $itemDetails = collect($cartItems)->map(fn($item) => [
            'id'       => (string) $item['course_id'],
            'price'    => (int) $item['price'],
            'quantity' => 1,
            'name'     => mb_substr($item['course_title'], 0, 50),
        ])->toArray();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $grossAmount,  // HARUS integer
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone ?? '',
            ],
            'item_details' => $itemDetails,
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Parse dan verifikasi HTTP notification dari Midtrans.
     * Ref: https://github.com/Midtrans/midtrans-php#23-handle-http-notification
     */
    public function handleNotification(): Notification
    {
        $notification = new Notification();
        // Midtrans SDK otomatis membaca php://input dan set properties
        return $notification;
    }

    /**
     * Verifikasi bahwa notification berasal dari Midtrans (bukan spoofed).
     * Gunakan Transaction::status() untuk cross-check.
     */
    public function verifyTransaction(string $orderId): object
    {
        return \Midtrans\Transaction::status($orderId);
    }
}
```

### CheckoutController

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('course')
            ->get();

        return view('frontend.checkout', compact('cartItems'));
    }

    public function process(Request $request, MidtransService $midtrans)
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('course')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong!');
        }

        $snapToken = $midtrans->createSnapToken(
            $cartItems->toArray(),
            auth()->user(),
            $request->coupon_code
        );

        return view('frontend.checkout', [
            'cartItems' => $cartItems,
            'snapToken' => $snapToken,
            'clientKey' => config('midtrans.client_key'),
        ]);
    }
}
```

### Payment Callback Controller

> ⚠️ **Penting:** Midtrans mengirim `fraud_status` untuk credit card. Logic harus handle ini.
> Ref: https://github.com/Midtrans/midtrans-php#23-handle-http-notification

```php
public function callback(Request $request, MidtransService $midtrans)
{
    $notification = $midtrans->handleNotification();

    $orderId           = $notification->order_id;
    $transactionStatus = $notification->transaction_status;
    $fraudStatus       = $notification->fraud_status;  // null untuk non-CC
    $paymentType       = $notification->payment_type;
    $transactionId     = $notification->transaction_id;

    // Cari payment record yang sudah dibuat saat process()
    $payment = Payment::where('midtrans_order_id', $orderId)->firstOrFail();

    // Update midtrans_response dengan data terbaru
    $payment->update([
        'midtrans_transaction_id' => $transactionId,
        'payment_type'            => $paymentType,
        'midtrans_response'       => json_encode($notification),
    ]);

    // Handle berdasarkan transaction_status + fraud_status
    // Ref: https://github.com/Midtrans/midtrans-php#23-handle-http-notification
    if ($transactionStatus == 'capture') {
        // Credit card: cek fraud_status
        if ($fraudStatus == 'accept') {
            $this->handleSuccess($payment);
        } elseif ($fraudStatus == 'challenge') {
            // Perlu review manual di Midtrans dashboard
            $payment->update(['status' => 'pending']);
        }
    } elseif ($transactionStatus == 'settlement') {
        // Bank transfer, e-wallet — langsung success
        $this->handleSuccess($payment);
    } elseif ($transactionStatus == 'pending') {
        $payment->update(['status' => 'pending']);
    } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
        $payment->update(['status' => $transactionStatus]);
    }

    return response()->json(['status' => 'OK']);
}

private function handleSuccess(Payment $payment): void
{
    DB::transaction(function () use ($payment) {
        $payment->update(['status' => 'settlement']);

        // Create order records dari cart items yang tersimpan saat checkout
        $cartItems = Cart::where('user_id', $payment->user_id)
            ->with('course')
            ->get();

        foreach ($cartItems as $item) {
            $order = Order::create([
                'payment_id'      => $payment->id,
                'user_id'         => $payment->user_id,
                'course_id'       => $item->course_id,
                'instructor_id'   => $item->course->instructor_id,  // denormalized (ADR-003)
                'coupon_id'       => $payment->applied_coupon_id ?? null,
                'original_price'  => $item->course->price,
                'discount_amount' => $discountAmount,
                'final_price'     => $item->course->discounted_price,
                'status'          => 'completed',
            ]);

            // Create enrollment — grants access to course
            Enrollment::create([
                'user_id'     => $payment->user_id,
                'course_id'   => $item->course_id,
                'order_id'    => $order->id,
                'enrolled_at' => now(),
            ]);
        }

        // Increment coupon used_count (jika dipakai)
        if ($couponId = $payment->applied_coupon_id ?? null) {
            Coupon::where('id', $couponId)->increment('used_count');
        }

        // Clear cart HANYA setelah order + enrollment berhasil
        Cart::where('user_id', $payment->user_id)->delete();
    });

    // Notifications (setelah transaction commit)
    Mail::to($payment->user)->queue(new OrderConfirmationMail($payment));
    foreach ($payment->orders()->with('instructor')->get() as $order) {
        Mail::to($order->instructor)->queue(new NewSaleMail($order));
    }

    // Real-time toast ke buyer
    event(new PaymentSuccessful($payment));
}
```

### Frontend Snap Integration

> Jangan gunakan URL production (`https://app.midtrans.com/snap/snap.js`) karena project ini sandbox-only.

```html
<!-- Di checkout.blade.php -->
<button id="pay-button" class="btn-primary w-full">Bayar Sekarang</button>

{{-- Load Snap JS — SELALU pakai URL sandbox --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    // snap.pay() membuka popup Midtrans
    // Ref: https://github.com/Midtrans/midtrans-php#22a-snap
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            // Payment berhasil — redirect ke halaman sukses
            // Catatan: status final dikonfirmasi via webhook/callback, bukan dari sini
            window.location.href = '{{ route("payment.success") }}?order_id=' + result.order_id;
        },
        onPending: function(result) {
            // User memilih bank transfer/VA — menunggu transfer
            window.location.href = '{{ route("payment.success") }}?order_id=' + result.order_id;
        },
        onError: function(result) {
            // Pembayaran gagal
            window.location.href = '{{ route("payment.failed") }}';
        },
        onClose: function() {
            // User menutup popup tanpa bayar
            alert('Pembayaran dibatalkan. Silakan coba lagi.');
        }
    });
});
</script>
```

---

## Status Payment Midtrans

| Status | Artinya | Action |
|--------|---------|--------|
| `pending` | Menunggu pembayaran | Tampilkan instruksi bayar |
| `settlement` | Pembayaran berhasil | Create orders, clear cart |
| `capture` | CC captured | Same as settlement |
| `deny` | Ditolak | Tampilkan pesan error |
| `cancel` | Dibatalkan | — |
| `expire` | Kadaluarsa | — |

---

## Sandbox Test Credentials

> Ref: https://docs.midtrans.com/docs/testing-payment-on-sandbox

### Credit Card
| Tipe | Nomor | Exp | CVV | OTP |
|------|-------|-----|-----|-----|
| Visa | 4811 1111 1111 1114 | 01/39 | 123 | 112233 |
| Mastercard | 5211 1111 1111 1117 | 01/39 | 123 | 112233 |
| Visa (simulate deny) | 4911 1111 1111 1113 | 01/39 | 123 | — |

> ⚠️ **Perhatian:** Gunakan **expiry 01/39** (bukan 01/25 yang sudah expired). Cek expiry terbaru di [Midtrans Sandbox Testing Docs](https://docs.midtrans.com/docs/testing-payment-on-sandbox).

### E-Wallet (GoPay, ShopeePay)
- Pilih GoPay/ShopeePay di popup Snap
- Klik **"Bayar"** → otomatis simulate approved di sandbox
- Tidak perlu app sungguhan

### Bank Transfer (BCA, BNI, BRI, dll)
- Generate VA number otomatis
- Di sandbox: langsung klik **"Simulasikan Pembayaran"** di dashboard Midtrans
- Atau tunggu expire (default 24 jam)

### Retail (Alfamart, Indomaret)
- Generate kode pembayaran
- Di sandbox: simulasi via Midtrans dashboard

---

## ⚠️ Penting: Webhook Callback di Local Development

Midtrans mengirim callback ke URL server. Di local (`localhost:8000`), Midtrans **tidak bisa** mengakses URL tersebut.

**Solusi untuk testing lokal:**
1. Gunakan **ngrok**: `ngrok http 8000` → dapat URL publik
2. Set Notification URL di [Midtrans Dashboard Sandbox](https://dashboard.sandbox.midtrans.com) → Settings → Configuration → Payment Notification URL
3. Format: `https://abc123.ngrok.io/payment/callback`

Alternatif: gunakan **Midtrans Dashboard → Transaction → Manually trigger notification** untuk testing.

---

## PIC: Ray Nathan
