# ADR-001: Gunakan Midtrans Snap sebagai Payment Gateway

**Status:** ✅ Accepted
**Date:** 12 Mei 2026
**Decision By:** Yosua (PM)
**Superseded By:** None

---

## Context

Reference project (YouTubeLMS) menggunakan **Stripe** sebagai payment gateway. Namun kita membangun platform untuk pasar Indonesia, di mana Stripe tidak populer dan belum support penuh (banyak metode pembayaran lokal tidak tersedia).

Metode pembayaran yang harus support:
- Bank transfer (BCA, BNI, BRI, Mandiri, Permata) — paling umum di Indonesia
- E-wallet (GoPay, ShopeePay, Dana, OVO)
- Credit card (Visa, Mastercard)
- Retail outlet (Alfamart, Indomaret)
- QRIS

## Decision

Gunakan **Midtrans Snap API** (v2) sebagai satu-satunya payment gateway.

Konfigurasi:
- `is_production = false` (hardcoded, sandbox only) — lihat ADR-004
- Package: `midtrans/midtrans-php`
- Frontend integration: Snap popup (bukan redirect)

## Consequences

### Positive
- Native support untuk semua metode bayar Indonesia
- Free sandbox untuk unlimited testing
- SDK PHP maintained oleh Midtrans
- Dokumentasi Bahasa Indonesia

### Negative
- Harus ngrok untuk test webhook di local (Midtrans butuh public URL)
- Production butuh KYC ke Midtrans (tidak relevan untuk project akademik)

## Alternatives Considered

1. **Stripe** — Ditolak: metode lokal tidak lengkap
2. **Xendit** — Mirip Midtrans tapi fee lebih tinggi, dokumentasi kurang lengkap
3. **DOKU** — Kompleks untuk integrate, cocok untuk enterprise
4. **Manual transfer + admin verify** — Too much operational overhead

---

*ADR ini final. Perubahan butuh ADR baru yang supersede.*
