# ADR-004: Midtrans di-hardcode ke Sandbox (non-production only)

**Status:** ✅ Accepted
**Date:** 12 Mei 2026
**Decision By:** Yosua (PM)

---

## Context

Midtrans punya 2 environment:
- **Sandbox** (`app.sandbox.midtrans.com`) — free, unlimited testing, fake money
- **Production** (`app.midtrans.com`) — real money, butuh KYC merchant

BelajarKUY adalah **project akademik non-komersial**:
- Tidak ada transaksi real
- Tidak punya entitas bisnis untuk KYC
- Demo presentation tetap perlu simulasi pembayaran

## Decision

**Hardcode** `Config::$isProduction = false` di `config/midtrans.php`. 

Frontend Snap JS URL juga hardcoded ke `https://app.sandbox.midtrans.com/snap/snap.js`.

**Tidak** menggunakan `.env` variable `MIDTRANS_IS_PRODUCTION`.

### Implementation

```php
// config/midtrans.php
return [
    'server_key'    => env('MIDTRANS_SERVER_KEY', ''),
    'client_key'    => env('MIDTRANS_CLIENT_KEY', ''),
    'is_production' => false,      // HARDCODED — ADR-004
    'is_sanitized'  => true,
    'is_3ds'        => true,
    'merchant_id'   => env('MIDTRANS_MERCHANT_ID', ''),
];
```

```blade
{{-- checkout.blade.php --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
```

## Consequences

### Positive
- **Impossible to accidentally charge real users** — config lock prevents mistake
- Tidak butuh toggling env variable antar deployment
- Clear documentation intent: "sandbox only"
- `.env.example` lebih bersih (1 variable kurang)

### Negative
- Jika future ingin pivot ke commercial, butuh edit config (bukan env var)
- Tidak bisa A/B test production vs sandbox

### Mitigations
- Jika pivot, buat ADR baru yang supersede ADR ini
- Edit 2 tempat: `config/midtrans.php` + frontend `<script src>` URL

## Rules for AI Agents

- **JANGAN** ubah `Config::$isProduction` ke `true` atau ke `env()`
- **JANGAN** ubah Snap JS URL ke `app.midtrans.com` (production)
- **JANGAN** tambah `MIDTRANS_IS_PRODUCTION` ke `.env.example`

---

*Project ini akademik. Hardcoded sandbox adalah safety net, bukan batasan.*
