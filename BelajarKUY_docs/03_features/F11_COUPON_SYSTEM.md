# 🏷️ F11: Coupon System

> Sistem kupon diskon yang dibuat instructor, diterapkan student saat checkout.
> **Schema v2** — enhanced dengan `code`, `course_id` (nullable), `max_usage`, `used_count`.

---

## Fitur

1. **Instructor CRUD kupon** — di instructor panel
2. **Kode unik** — `coupons.code` (UNIQUE varchar 50)
3. **Global atau per-course** — `course_id` nullable (null = berlaku global untuk semua kursus instructor)
4. **Expiry** — `valid_until` (date)
5. **Usage limit** — `max_usage` nullable (null = unlimited)
6. **Usage counter** — `used_count` di-increment saat order finalized
7. **Status on/off** — `status` boolean
8. **Student apply di checkout** — input kode → validate → apply diskon
9. **Validasi 4 layer:** kode exists, status active, belum expired, belum habis quota

---

## Schema v2 — `coupons` Table

```
coupons
├── id
├── instructor_id (FK → users.id, CASCADE)
├── course_id (FK → courses.id, SET NULL, NULLABLE)
│         ↑ NULL = berlaku untuk semua kursus instructor
├── code (UNIQUE varchar 50)
├── discount_percent (unsigned int, 1-100)
├── valid_until (date)
├── max_usage (unsigned int, NULLABLE)
│         ↑ NULL = unlimited usage
├── used_count (unsigned int, DEFAULT 0)
├── status (boolean, DEFAULT true)
└── timestamps
```

### ⚠️ DEPRECATED Fields (Jangan Dipakai)

Jika Anda melihat kode dengan field di bawah, ini **Schema v1** yang sudah diganti:

| ❌ v1 (deprecated) | ✅ v2 (current) |
|---|---|
| `coupons.name` | `coupons.code` |
| `coupons.validity` | `coupons.valid_until` |
| `coupons.discount` | `coupons.discount_percent` |

---

## Apply Coupon Logic

```php
namespace App\Http\Controllers\Frontend;

use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request): JsonResponse
    {
        $data = $request->validate([
            'coupon_code' => 'required|string|max:50',
            'course_ids'  => 'required|array|min:1',
            'course_ids.*'=> 'integer|exists:courses,id',
            'subtotal'    => 'required|numeric|min:0',
        ]);

        // Pakai scope active() yang sudah handle:
        //   status=true AND valid_until >= today AND (max_usage NULL OR used_count < max_usage)
        $coupon = Coupon::active()
            ->where('code', $data['coupon_code'])
            ->where(function ($q) use ($data) {
                // Global coupon (course_id NULL) ATAU course-specific yang match salah satu course di cart
                $q->whereNull('course_id')
                  ->orWhereIn('course_id', $data['course_ids']);
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Kupon tidak valid, sudah expired, atau sudah habis.',
            ], 404);
        }

        $discountAmount = round($data['subtotal'] * $coupon->discount_percent / 100, 2);
        $finalPrice = max(0, $data['subtotal'] - $discountAmount);

        return response()->json([
            'success'           => true,
            'coupon_id'         => $coupon->id,
            'coupon_code'       => $coupon->code,
            'discount_percent'  => $coupon->discount_percent,
            'discount_amount'   => $discountAmount,
            'final_price'       => $finalPrice,
        ]);
    }
}
```

### Coupon Scope (Model)

Model `Coupon` sudah punya scope `active()` yang encapsulate validasi:

```php
// app/Models/Coupon.php
public function scopeActive(Builder $query): Builder
{
    return $query
        ->where('status', true)
        ->whereDate('valid_until', '>=', now()->toDateString())
        ->where(function (Builder $q) {
            $q->whereNull('max_usage')
              ->orWhereColumn('used_count', '<', 'max_usage');
        });
}
```

Gunakan scope ini di semua query coupon — single source of truth untuk "kupon aktif".

---

## Increment `used_count` — Saat Order Finalized

**PENTING:** `used_count` **jangan** di-increment saat apply. Apply bisa di-cancel. Increment saat:
- Payment `settlement` / `capture+accept` → order created successfully

```php
// CheckoutController@handleSuccess (saat payment berhasil)
private function handleSuccess(Payment $payment): void
{
    DB::transaction(function () use ($payment) {
        $payment->update(['status' => 'settlement']);

        $cartItems = Cart::where('user_id', $payment->user_id)
            ->with('course')
            ->get();

        foreach ($cartItems as $item) {
            // Create order dengan coupon_id (jika ada)
            $order = Order::create([
                'payment_id'      => $payment->id,
                'user_id'         => $payment->user_id,
                'course_id'       => $item->course_id,
                'instructor_id'   => $item->course->instructor_id,
                'coupon_id'       => $payment->applied_coupon_id ?? null,
                'original_price'  => $item->course->price,
                'discount_amount' => $item->course->price * ($couponPercent / 100),
                'final_price'     => $item->course->discounted_price,
                'status'          => 'completed',
            ]);

            // Create enrollment
            Enrollment::create([
                'user_id'    => $payment->user_id,
                'course_id'  => $item->course_id,
                'order_id'   => $order->id,
                'enrolled_at' => now(),
            ]);
        }

        // Increment coupon usage ONLY after success
        if ($couponId = $payment->applied_coupon_id ?? null) {
            Coupon::where('id', $couponId)->increment('used_count');
        }

        // Clear cart HANYA setelah orders + enrollments berhasil
        Cart::where('user_id', $payment->user_id)->delete();
    });
}
```

---

## Instructor Coupon CRUD

Instructor bisa manage kupon di `/instructor/coupon`:

- **Create**: form dengan fields (course_id optional, code auto-gen or manual, discount_percent 1-100, valid_until, max_usage optional)
- **List**: table semua kupon instructor + counter `used_count / max_usage`
- **Toggle status**: on/off tanpa menghapus
- **Delete**: hard delete (tapi warning jika sudah dipakai)

Form validation menggunakan `StoreCouponRequest`:

```php
public function rules(): array
{
    return [
        'course_id'        => 'nullable|exists:courses,id',
        'code'             => 'required|string|max:50|unique:coupons,code,' . $this->coupon?->id,
        'discount_percent' => 'required|integer|between:1,100',
        'valid_until'      => 'required|date|after_or_equal:today',
        'max_usage'        => 'nullable|integer|min:1',
        'status'           => 'boolean',
    ];
}
```

---

## UI Text (Bahasa Indonesia)

Contoh flash messages:
- Sukses: "Kupon berhasil diterapkan! Diskon {{ $percent }}%"
- Error: "Kupon tidak valid atau sudah kedaluwarsa."
- Error: "Kupon hanya berlaku untuk kursus tertentu."
- Error: "Kupon sudah mencapai batas pemakaian."

---

## Glossary Reference

- **Apply** — submit kode kupon untuk validasi + dapatkan diskon preview (tidak commit)
- **Use** — finalisasi — increment `used_count` setelah payment sukses
- **Global coupon** — `course_id = NULL`, berlaku untuk semua kursus instructor
- **Course-specific coupon** — `course_id = X`, hanya untuk kursus X

---

## PIC: Ray Nathan
