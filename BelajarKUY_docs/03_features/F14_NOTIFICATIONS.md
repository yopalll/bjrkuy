# 📢 F14: Notifications (Email + Real-time)

> Kumpulan semua notifikasi di BelajarKUY — email + real-time via WebSocket.
> **Priority:** Integrate bertahap per feature (Auth, Payment, Course approval, dll)

---

## Overview

Ada 2 channel notifikasi:

1. **Email** — Persistent, untuk konfirmasi penting (pembelian, welcome, course approval)
2. **Real-time (WebSocket via Reverb)** — Transient, untuk instant feedback (payment success, new order to instructor)

Semua email notifications menggunakan **Laravel Mailable** classes di `app/Mail/`.
Semua real-time events menggunakan **Laravel Event + broadcasting** di `app/Events/`.

---

## Email Notifications Map

| Event | Trigger | Recipient | Mail Class | Blade Template |
|-------|---------|-----------|------------|----------------|
| User register | `RegisteredUserController::store()` | New user | `WelcomeMail` | `emails.welcome` |
| Forgot password | Breeze default | User | — built-in — | — built-in — |
| Email verification | Breeze default | User | — built-in — | — built-in — |
| Payment settlement | `CheckoutController::handleSuccess()` | Buyer (student) | `OrderConfirmationMail` | `emails.order-confirmation` |
| New sale | `CheckoutController::handleSuccess()` | Instructor | `NewSaleMail` | `emails.new-sale` |
| Course submitted for review | `InstructorCourseController::submit()` | Admin | `CourseSubmittedMail` | `emails.course-submitted` |
| Course approved | `AdminCourseController::approve()` | Instructor | `CourseApprovedMail` | `emails.course-approved` |
| Course rejected | `AdminCourseController::reject()` | Instructor | `CourseRejectedMail` | `emails.course-rejected` |
| New review | `ReviewController::store()` | Instructor | `NewReviewMail` | `emails.new-review` |

### Out of Scope (sesuai ADR-005 & ADR-006)

- ~~Instructor approved notification~~ — instructor auto-active (ADR-006)
- ~~Payout released notification~~ — payout out of scope (ADR-005)

---

## Mail Class Template

```php
// app/Mail/OrderConfirmationMail.php
namespace App\Mail;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Payment  $payment  Payment yang baru settle (dengan relasi orders.course)
     */
    public function __construct(public Payment $payment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pembelian — BelajarKUY',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'payment' => $this->payment,
                'orders'  => $this->payment->orders()->with('course.instructor')->get(),
                'user'    => $this->payment->user,
            ],
        );
    }
}
```

### Trigger Point

```php
// CheckoutController::handleSuccess()
private function handleSuccess(Payment $payment): void
{
    DB::transaction(function () use ($payment) {
        // ... create orders, enrollments, clear cart ...
    });

    // Kirim email ke buyer (queue untuk tidak blocking)
    Mail::to($payment->user)->queue(new OrderConfirmationMail($payment));

    // Kirim email ke setiap instructor yang ada order
    foreach ($payment->orders as $order) {
        Mail::to($order->instructor)->queue(new NewSaleMail($order));
    }

    // Broadcast real-time (lihat section berikutnya)
    event(new PaymentSuccessful($payment));
}
```

---

## Blade Template Example (Email)

```blade
{{-- resources/views/emails/order-confirmation.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembelian</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 8px; padding: 32px;">
        <h1 style="color: #4f46e5;">Terima kasih atas pembelianmu, {{ $user->name }}! 🎉</h1>

        <p>Pembayaranmu telah dikonfirmasi. Berikut detail pesananmu:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 24px 0;">
            <thead>
                <tr style="background: #f1f5f9;">
                    <th style="padding: 12px; text-align: left;">Kursus</th>
                    <th style="padding: 12px; text-align: right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px;">
                            {{ $order->course->title }}<br>
                            <small style="color: #64748b;">Oleh {{ $order->course->instructor->name }}</small>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            Rp {{ number_format($order->final_price, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td style="padding: 12px; font-weight: bold;">Total</td>
                    <td style="padding: 12px; text-align: right; font-weight: bold; color: #4f46e5;">
                        Rp {{ number_format($payment->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <a href="{{ route('user.courses') }}"
           style="display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none;">
            Lihat Kursusku
        </a>

        <p style="margin-top: 32px; color: #64748b; font-size: 14px;">
            Butuh bantuan? Balas email ini atau hubungi kami di halo@belajarkuy.id.
        </p>
    </div>
</body>
</html>
```

---

## Real-time Notifications (Reverb)

### Events yang Broadcast

| Event | Channel | Recipient |
|-------|---------|-----------|
| `PaymentSuccessful` | `private-user.{userId}` | Buyer — toast "Pembayaran berhasil!" |
| `NewSaleReceived` | `private-instructor.{instructorId}` | Instructor — badge notification |
| `CourseApproved` | `private-instructor.{instructorId}` | Instructor — toast |
| `CourseRejected` | `private-instructor.{instructorId}` | Instructor — toast |

### Event Class Template

```php
// app/Events/PaymentSuccessful.php
namespace App\Events;

use App\Models\Payment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessful implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Payment $payment,
        public string $message = 'Pembayaran berhasil! Kursusmu sudah bisa diakses.'
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->payment->user_id)];
    }

    public function broadcastAs(): string
    {
        return 'payment.success';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'payment_id' => $this->payment->id,
            'total_amount' => $this->payment->total_amount,
        ];
    }
}
```

### Channel Authorization

```php
// routes/channels.php
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('instructor.{instructorId}', function ($user, $instructorId) {
    return $user->isInstructor() && (int) $user->id === (int) $instructorId;
});
```

### Frontend Listener

```blade
{{-- layouts/app.blade.php (di layout utama) --}}
@auth
    <script>
        window.currentUser = {
            id: {{ auth()->id() }},
            role: '{{ auth()->user()->role }}',
        };
    </script>
@endauth
```

```javascript
// resources/js/echo.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Setelah login, listen di channel user
if (window.currentUser) {
    window.Echo.private(`user.${window.currentUser.id}`)
        .listen('.payment.success', (e) => {
            // Trigger toast / SweetAlert
            window.dispatchEvent(new CustomEvent('notification', {
                detail: { type: 'success', message: e.message }
            }));
        });

    // Jika instructor, listen channel instructor juga
    if (window.currentUser.role === 'instructor') {
        window.Echo.private(`instructor.${window.currentUser.id}`)
            .listen('.sale.new', (e) => {
                window.dispatchEvent(new CustomEvent('notification', {
                    detail: { type: 'info', message: `Penjualan baru: ${e.course_title}` }
                }));
            });
    }
}
```

### Toast Component (Alpine.js)

```blade
{{-- resources/views/components/toast-listener.blade.php --}}
<div x-data="{ notifications: [] }"
     @notification.window="
        notifications.push({
            id: Date.now(),
            type: $event.detail.type,
            message: $event.detail.message
        });
        setTimeout(() => {
            notifications = notifications.filter(n => n.id !== $event.detail.id);
        }, 5000);
     "
     class="fixed top-4 right-4 z-50 space-y-2">

    <template x-for="notif in notifications" :key="notif.id">
        <div x-transition
             class="px-4 py-3 rounded-lg shadow-lg min-w-[300px]"
             :class="{
                'bg-green-500 text-white': notif.type === 'success',
                'bg-blue-500 text-white': notif.type === 'info',
                'bg-red-500 text-white': notif.type === 'error',
             }">
            <p x-text="notif.message"></p>
        </div>
    </template>
</div>
```

---

## Queue Configuration

Email di-send via queue untuk tidak blocking HTTP request:

```env
QUEUE_CONNECTION=database
```

```bash
# Jalankan worker untuk process queue
php artisan queue:work
```

Untuk project akademik, cukup `database` driver. Production: gunakan Redis.

---

## Testing Checklist

- [ ] Register user → terima welcome email
- [ ] Bayar course → terima order confirmation + real-time toast
- [ ] Instructor terima new sale email + badge notification
- [ ] Submit course for review → admin terima notif
- [ ] Admin approve course → instructor terima email approval
- [ ] Admin reject course → instructor terima email rejection
- [ ] Student post review → instructor terima email

---

## PIC: Ray Nathan (payment-related) + Albariqi Tarigan (course-related)
