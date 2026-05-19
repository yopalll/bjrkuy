# 🛒 F05: Cart & Wishlist

> Sistem keranjang belanja dan wishlist.
> **Schema v2** — cart disederhanakan (no `price`, no `instructor_id`).

---

## Wishlist

1. **Add to Wishlist** — Toggle wishlist via AJAX (logged-in users only)
2. **View Wishlist** — List kursus di wishlist student
3. **Remove from Wishlist** — Hapus item dari wishlist
4. **Wishlist Count** — Badge counter di navbar
5. **Prevent Duplicate** — UNIQUE constraint `(user_id, course_id)` di DB

## Cart

1. **Add to Cart** — Tambah kursus ke keranjang (AJAX)
2. **View Cart** — Halaman cart (`/cart`)
3. **Remove from Cart** — Hapus item dari cart
4. **Cart Count** — Badge counter di navbar (realtime)
5. **Prevent Duplicate** — UNIQUE constraint `(user_id, course_id)`
6. **Prevent Re-purchase** — Cek `Enrollment` sebelum allow add (lihat Business Rules di bawah)
7. **Auto Price** — Harga dihitung real-time dari `courses.price` dan `courses.discount` **saat render**. Cart table **tidak menyimpan** harga.

---

## Schema v2 — Simplified Cart Table

```
carts
├── id
├── user_id (FK → users.id)
├── course_id (FK → courses.id)
├── UNIQUE(user_id, course_id)
└── timestamps
```

### ⚠️ Yang TIDAK Disimpan (intentional)

- ❌ `price` — akan stale jika harga course berubah. Dihitung real-time.
- ❌ `instructor_id` — derivable via `course.instructor_id`. Redundansi berbahaya.

Alasan lengkap lihat `02_architecture/DATABASE_SCHEMA.md` section 9 (`carts`).

---

## AJAX Endpoint Contracts

### Wishlist

```
POST   /wishlist/add
  Body:       { course_id: number }
  Auth:       required
  Response:   { success: true, wishlist_count: number, action: 'added' | 'removed' }
  Behavior:   Toggle — jika belum di-wishlist → add; jika sudah → remove

GET    /wishlist/all
  Auth:       required
  Response:   [{ id, course: {...} }, ...]

DELETE /user/wishlist/{id}
  Auth:       required, role:student
  Response:   { success: true, message: string }
```

### Cart

```
POST   /cart/add
  Body:       { course_id: number }
  Auth:       required
  Response:   { success: true, cart_count: number, cart_item: {...} }
  Errors:
    - 409 if course already in cart (UNIQUE violation handled gracefully)
    - 409 if student sudah enrolled ke course ini
  Behavior:
    - Cek Enrollment first (ADR: tidak boleh beli course yang sudah dimiliki)
    - Cek Cart exists (idempotent add — return existing)
    - INSERT dengan firstOrCreate
    - Return cart_count terbaru

GET    /cart/all
  Auth:       required
  Response:   [
    {
      id: number,
      course: {
        id, title, slug, thumbnail, price, discount,
        discounted_price: number (computed from price - price*discount/100)
      },
      instructor: { id, name, photo }  // dari course.instructor
    }, ...
  ]

GET    /cart/fetch
  Auth:       required
  Response:   { cart_count: number, total_amount: number }
  Usage:      Poll untuk navbar badge

POST   /cart/remove
  Body:       { id: number }  // cart item id
  Auth:       required
  Response:   { success: true, cart_count: number }
```

---

## Pricing Logic (Real-time Calculation)

Saat render cart atau checkout, **jangan** baca harga dari cart table (tidak ada). Eager load course dengan discount accessor:

```php
// CartController@index
public function index(): View
{
    $cartItems = Cart::where('user_id', auth()->id())
        ->with(['course.instructor'])  // eager load
        ->get();

    $subtotal = $cartItems->sum(fn ($item) => $item->course->discounted_price);

    return view('frontend.cart', compact('cartItems', 'subtotal'));
}
```

```php
// app/Models/Course.php — accessor sudah tersedia
public function getDiscountedPriceAttribute(): float
{
    if ($this->discount <= 0) {
        return (float) $this->price;
    }
    return round($this->price - ($this->price * $this->discount / 100), 2);
}
```

---

## Business Rules

### Prevent Re-purchase (Important)

Student **tidak boleh** menambahkan course ke cart jika sudah enrolled:

```php
// CartController@store
public function store(Request $request): JsonResponse
{
    $courseId = $request->validate(['course_id' => 'required|exists:courses,id'])['course_id'];
    $userId = auth()->id();

    // ✅ BENAR — cek enrolled via tabel enrollments (fast)
    $alreadyEnrolled = Enrollment::where('user_id', $userId)
        ->where('course_id', $courseId)
        ->exists();

    if ($alreadyEnrolled) {
        return response()->json([
            'success' => false,
            'message' => 'Kamu sudah memiliki kursus ini.'
        ], 409);
    }

    // Idempotent: firstOrCreate
    $cart = Cart::firstOrCreate([
        'user_id' => $userId,
        'course_id' => $courseId,
    ]);

    return response()->json([
        'success' => true,
        'cart_count' => Cart::where('user_id', $userId)->count(),
        'cart_item' => $cart->load('course'),
    ]);
}
```

### Checkout Clears Cart

Cart items di-clear **hanya setelah** payment `settlement`/`capture+accept` dan order records berhasil dibuat. Lihat `F06_PAYMENT_MIDTRANS.md` → `handleSuccess()`.

---

## Pricing Components Pattern (Blade)

```blade
{{-- resources/views/components/cart-item.blade.php --}}
@props(['cartItem'])

@php $course = $cartItem->course; @endphp

<div class="flex items-center gap-4 p-4 border-b">
    <img src="{{ $course->thumbnail }}" class="w-24 h-16 rounded object-cover" alt="{{ $course->title }}">

    <div class="flex-1">
        <h3 class="font-medium">{{ $course->title }}</h3>
        <p class="text-sm text-gray-500">{{ $course->instructor->name }}</p>
    </div>

    <div class="text-right">
        @if($course->discount > 0)
            <p class="text-sm line-through text-gray-400">
                Rp {{ number_format($course->price, 0, ',', '.') }}
            </p>
        @endif
        <p class="font-bold text-indigo-600">
            Rp {{ number_format($course->discounted_price, 0, ',', '.') }}
        </p>
    </div>

    <button @click="removeFromCart({{ $cartItem->id }})" class="text-red-500">
        Hapus
    </button>
</div>
```

---

## Glossary Reference

- **Enrolled** — punya akses ke course (cek `enrollments` table)
- **Cart** — isi keranjang, belum bayar
- **Wishlist** — daftar keinginan, separate dari cart

Lihat `01_guides/GLOSSARY.md` untuk terminologi lengkap.

---

## PIC: Ray Nathan
