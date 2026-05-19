# ADR-002: Frontend pakai Blade + Alpine.js (bukan Livewire / Inertia / Vue SPA)

**Status:** ✅ Accepted
**Date:** 12 Mei 2026
**Decision By:** Yosua (PM)

---

## Context

Laravel ekosistem punya banyak pilihan frontend:
1. **Blade + Alpine.js** (TALL-lite stack)
2. **Livewire** (Server-rendered reactive)
3. **Inertia.js + Vue/React** (SPA feel dengan Laravel routing)
4. **Separate SPA** (Vue/React standalone + Laravel API)

## Decision

Gunakan **Blade + Alpine.js + TailwindCSS v4** saja.

- Server-side rendering (SSR) untuk semua halaman
- Alpine.js untuk interaktivitas UI (dropdown, modal, tabs, accordion, live search)
- Tanpa Livewire, Inertia, atau SPA terpisah

## Consequences

### Positive
- **Learning curve flat** untuk tim — anggota sudah familiar Blade dari kuliah
- **Zero build complexity** untuk banyak page (Blade langsung render)
- **SEO-friendly** untuk landing page & course detail
- **Fast initial load** — no JS framework hydration
- **Alpine.js cukup** untuk 90% interaktivitas yang kita butuh

### Negative
- Tidak cocok untuk flow yang sangat interaktif (misal: video editor real-time)
- Harus manual build component reusability dengan Blade components

### Mitigations
- Untuk real-time, pakai Laravel Reverb (WebSocket) + Alpine.js listener
- Untuk form validation, pakai Laravel Form Request + flash error messages

## Alternatives Considered

1. **Livewire** — Ditolak: tim belum familiar, debugging wire:model bisa tricky
2. **Inertia + Vue** — Ditolak: overhead setup Vue ecosystem untuk project 4 minggu
3. **Vue SPA standalone** — Ditolak: butuh API layer terpisah, testing double

---

*Revisit hanya jika flow fitur benar-benar butuh SPA reactivity (unlikely untuk MVP LMS).*
