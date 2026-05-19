# 🏛️ Architecture Decision Records (ADR)

> Catatan keputusan arsitektur penting untuk project BelajarKUY.
> Setiap ADR mendokumentasikan **alasan** di balik keputusan — bukan cuma apa yang diputuskan.

---

## Index

| # | Title | Status | Date |
|---|-------|--------|------|
| [001](./ADR-001-midtrans-payment-gateway.md) | Gunakan Midtrans sebagai Payment Gateway | ✅ Accepted | 12 Mei 2026 |
| [002](./ADR-002-frontend-blade-not-livewire.md) | Frontend pakai Blade + Alpine.js | ✅ Accepted | 12 Mei 2026 |
| [003](./ADR-003-denormalized-instructor-in-orders.md) | Denormalisasi `instructor_id` di `orders` | ✅ Accepted | 12 Mei 2026 |
| [004](./ADR-004-sandbox-only-midtrans.md) | Midtrans hardcoded ke Sandbox | ✅ Accepted | 12 Mei 2026 |
| [005](./ADR-005-payout-out-of-scope.md) | Payout / Revenue Split Out of Scope | ✅ Accepted | 14 Mei 2026 |
| [006](./ADR-006-instructor-auto-active.md) | Instructor Auto-Active (No Approval) | ✅ Accepted | 14 Mei 2026 |
| [007](./ADR-007-role-naming.md) | Role Naming Duality (user/student) | ✅ Accepted | 14 Mei 2026 |

---

## When to Write an ADR

Tulis ADR baru ketika:
- Membuat keputusan yang **mahal untuk diubah nanti** (schema, tech stack, auth flow)
- Ada **multiple valid options** dan kita pilih satu
- Keputusan **tidak obvious** dari code saja
- Keputusan **mempengaruhi multiple features**

## ADR Template

```md
# ADR-NNN: [Title]

**Status:** [Proposed | Accepted | Deprecated | Superseded by ADR-XXX]
**Date:** [YYYY-MM-DD]
**Decision By:** [Name/Role]

## Context
[Apa masalahnya? Kenapa perlu keputusan?]

## Decision
[Apa yang kita putuskan? Be specific.]

## Consequences
### Positive
### Negative
### Mitigations

## Alternatives Considered
[Opsi lain yang dipikirkan + alasan ditolak]

---
```

---

*ADR adalah **immutable** — jangan edit ADR yang sudah Accepted. Jika berubah, buat ADR baru dengan status "Supersedes ADR-XXX".*
