# ADR-003: Denormalisasi `instructor_id` di tabel `orders`

**Status:** ✅ Accepted
**Date:** 12 Mei 2026
**Decision By:** Yosua (PM)
**Schema Version:** v2

---

## Context

Instructor perlu melihat laporan penjualan mereka:
- Berapa order yang saya terima bulan ini?
- Berapa total revenue saya?
- List order saya dengan status tertentu

Dalam schema yang normalized penuh, query-nya:
```sql
SELECT o.*
FROM orders o
INNER JOIN courses c ON c.id = o.course_id
WHERE c.instructor_id = 123
  AND o.status = 'completed';
```

Join ke `courses` diperlukan hanya untuk filter by `instructor_id`. Untuk instructor dashboard yang dipanggil sering, ini overhead tidak perlu.

## Decision

Tambahkan kolom `instructor_id` di tabel `orders` sebagai **snapshot** (denormalized).

```
orders:
  id, payment_id, user_id, course_id,
  instructor_id,     ← denormalized dari courses.instructor_id saat checkout
  coupon_id,
  original_price, discount_amount, final_price,
  status, timestamps
```

Query instructor dashboard jadi:
```sql
SELECT * FROM orders
WHERE instructor_id = 123
  AND status = 'completed';
```

Tidak ada join. Single table scan dengan composite index `(instructor_id, status)`.

## Consequences

### Positive
- **10-50x faster** untuk instructor dashboard query
- Composite index `(instructor_id, status)` membuat filter sangat cepat
- Simpler application code

### Negative
- **Data redundancy**: `instructor_id` ada di 2 tempat (`courses.instructor_id` dan `orders.instructor_id`)
- **Risiko inconsistency** jika instructor course dipindah (tidak seharusnya terjadi di business model kita)

### Mitigations
- Course tidak bisa dipindah ke instructor lain (business rule) — jika perlu, buat course baru
- Set `instructor_id` sekali saja saat order dibuat, lalu read-only
- Di-handle otomatis oleh `CheckoutController::handleSuccess()`:
  ```php
  Order::create([
      ...
      'instructor_id' => $item->course->instructor_id, // snapshot
      ...
  ]);
  ```

## Similar Pattern

Pricing fields (`original_price`, `discount_amount`, `final_price`) juga denormalized dari `courses.price` untuk alasan yang sama — harga bisa berubah, order harus ingat harga saat transaksi.

---

*Denormalisasi strategis ini sengaja. Jangan normalisasi balik kecuali ada alasan kuat.*
