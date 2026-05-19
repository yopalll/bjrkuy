# ADR-005: Payout / Revenue Split Out of Scope untuk MVP

**Status:** ✅ Accepted
**Date:** 14 Mei 2026
**Decision By:** Yosua (PM) — default decision via audit recommendation

---

## Context

Dalam platform LMS komersial (Udemy, Teachable), ada pembagian revenue antara platform dan instructor:
- Platform ambil 30% fee
- Instructor terima 70% — dibayarkan via mekanisme payout (bulanan/weekly)

Sebelumnya di `AGENT_GUIDELINES.md` section 5.4 ada snippet:
```php
$instructorShare = $order->price * 0.70;
$platformShare = $order->price * 0.30;
```

Namun:
1. Tidak ada tabel `payouts` di schema
2. Tidak ada UI payout management
3. Tidak ada mekanisme transfer bank
4. Project akademik — tidak ada uang real

## Decision

**Payout feature OUT OF SCOPE untuk MVP.**

Tidak ada:
- Tabel `payouts`
- Field `platform_fee`, `instructor_share` di `orders`
- Controller `PayoutController`
- Admin page payout approval
- Instructor page "My Payouts"

Hapus snippet 70/30 dari `AGENT_GUIDELINES.md` untuk mencegah confusion.

### Yang tetap ADA

- Instructor bisa melihat **gross revenue** mereka di dashboard = sum of `orders.final_price` where `instructor_id = auth()->id() AND status = 'completed'`
- Admin bisa melihat **platform gross revenue** = sum of all `payments.total_amount` where `status IN (settlement, capture)`

Ini cukup untuk mendemonstrasikan capability reporting — sisa (payout mechanism) cukup mock di demo.

## Consequences

### Positive
- Schema v2 tetap lean (19 tables)
- Tidak butuh mock transfer API
- Focus resource ke core LMS features (course player, auth, commerce)
- Clear scope — no ambiguity

### Negative
- Tidak bisa showcase end-to-end instructor monetization
- Jika future pivot ke commercial, butuh Schema v3 + UI baru

### Mitigations
- Dalam demo, bilang: "untuk demo tujuan akademik, kita tampilkan gross revenue saja. Mekanisme payout akan di-implement di Phase 2 komersial."

## Alternatives Considered

1. **Full payout feature** — Rejected: scope creep, 3+ hari effort tambahan
2. **Mock payout table (tanpa actual logic)** — Rejected: semi-implementation lebih confusing dari nol
3. **Simple calculation field only (tanpa payout tracking)** — Considered tapi ditolak karena field jadi "dead data"

---

## Scope Re-evaluation Trigger

Revisit ADR ini jika:
- Project pivot ke komersial
- Ada anggota tim spesialis payment dengan waktu 3+ hari untuk implement
- Demo audience secara eksplisit meminta demo payout flow

---

*Keep scope tight. Quality > breadth.*
