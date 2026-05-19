# 📚 BelajarKUY — Dokumentasi Proyek (Master Index)

> **Project:** BelajarKUY — Platform E-Learning Indonesia (Udemy Clone)
> **Tech Stack:** Laravel 12 + MySQL/SQLite + Midtrans Sandbox + TailwindCSS v4 + Alpine.js + Cloudinary + Meilisearch + Laravel Reverb + Resend
> **Team:** 5 Anggota | **Deadline:** Tugas Besar Kuliah
> **Created:** 12 Mei 2026 | **Last Updated:** 14 Mei 2026

---

## 🎯 Quick Start untuk AI Agent / Developer Baru

**Wajib baca urutan ini:**

1. **`01_guides/AGENT_GUIDELINES.md`** — System prompt utama (v2.0)
2. **`01_guides/GLOSSARY.md`** — Terminologi (prevents ambiguity)
3. **`02_architecture/DATABASE_SCHEMA.md`** — Schema v2 canonical
4. **`02_architecture/ADR/`** — Architecture Decision Records
5. File spesifik feature di `03_features/` sesuai task

**Setelah itu:**
- Setup: `01_guides/SETUP_GUIDE.md`
- Git workflow: `01_guides/GIT_WORKFLOW.md`
- Coding style: `01_guides/CODING_STANDARDS.md`

---

## 🗂️ Struktur Folder Dokumentasi

```
BelajarKUY_docs/
│
├── 00_INDEX.md                          ← KAMU DI SINI
├── CHANGELOG.md                         ← 🆕 Log perubahan dokumentasi
│
├── 01_guides/                           📖 Panduan & Standards
│   ├── AGENT_GUIDELINES.md              ⭐ System prompt — WAJIB BACA
│   ├── GLOSSARY.md                      🆕 Terminologi (student/user, paid/enrolled, dll)
│   ├── SETUP_GUIDE.md                   Setup project dari nol
│   ├── GIT_WORKFLOW.md                  Branching & commit convention (simplified v2)
│   ├── CODING_STANDARDS.md              Konvensi kode Laravel 12
│   ├── SECURITY_GUIDELINES.md           🆕 Security checklist
│   ├── TESTING_STRATEGY.md              🆕 Testing pyramid + patterns
│   └── UI_UX_GUIDELINES.md              Design workflow
│
├── 02_architecture/                     🏗️ Architecture & Design
│   ├── TECH_STACK.md                    Detail tech stack & versi
│   ├── DATABASE_SCHEMA.md               ⭐ Schema v2 canonical (19 tabel)
│   ├── FOLDER_STRUCTURE.md              Struktur folder Laravel
│   ├── API_ROUTES.md                    Semua routes & endpoint
│   └── ADR/                             🆕 Architecture Decision Records
│       ├── README.md                    Index ADR
│       ├── ADR-001-midtrans-payment-gateway.md
│       ├── ADR-002-frontend-blade-not-livewire.md
│       ├── ADR-003-denormalized-instructor-in-orders.md
│       ├── ADR-004-sandbox-only-midtrans.md
│       ├── ADR-005-payout-out-of-scope.md
│       ├── ADR-006-instructor-auto-active.md
│       └── ADR-007-role-naming.md
│
├── 03_features/                         🎁 Feature Specs
│   ├── F01_AUTH_SYSTEM.md               Register, Login, Multi-role, Google OAuth
│   ├── F02_LANDING_PAGE.md              Homepage, Slider, Info Boxes
│   ├── F03_COURSE_MANAGEMENT.md         CRUD Course (Instructor)
│   ├── F04_CATEGORY_SYSTEM.md           Kategori & Sub-kategori
│   ├── F05_CART_WISHLIST.md             Keranjang & Wishlist (Schema v2 aligned)
│   ├── F06_PAYMENT_MIDTRANS.md          Integrasi Midtrans Snap + auto-enrollment
│   ├── F07_ADMIN_PANEL.md               Admin Dashboard (scope: ADR-005, ADR-006)
│   ├── F08_INSTRUCTOR_PANEL.md          Instructor Dashboard
│   ├── F09_STUDENT_PANEL.md             Student Dashboard + links ke Course Player
│   ├── F10_REVIEW_RATING.md             Sistem Review & Rating
│   ├── F11_COUPON_SYSTEM.md             Kupon (Schema v2 aligned)
│   ├── F12_SITE_SETTINGS.md             Site settings (DB-backed only)
│   ├── F13_COURSE_PLAYER.md             🆕 Watch page + progress tracking
│   └── F14_NOTIFICATIONS.md             🆕 Email + real-time events
│
├── 04_plans/                            📅 Timeline & Task Distribution
│   ├── MASTER_ROADMAP.md                Timeline Phase 1-5 (relative days)
│   ├── SPRINT_PLAN.md                   Sprint breakdown per minggu
│   └── TASK_DISTRIBUTION.md             Pembagian tugas per anggota
│
├── 05_prompts/                          🤖 AI Prompt Templates
│   ├── PROMPT_SETUP_PROJECT.md          Init Laravel 12 project
│   ├── PROMPT_MIGRATIONS.md             Generate migrations (Schema v2)
│   ├── PROMPT_MODELS.md                 Generate Eloquent models
│   ├── PROMPT_AUTH.md                   Build auth system
│   ├── PROMPT_MIDTRANS.md               Integrate Midtrans
│   ├── PROMPT_FRONTEND.md               Build frontend pages
│   └── PROMPT_ADMIN_PANEL.md            Build admin panel
│
├── 06_reports/                          📊 Progress & Daily Reports
│   ├── PROGRESS_TRACKER.md              Status live progress
│   ├── REPORT_2026-05-13_DATABASE_LAYER.md
│   └── REPORT_2026-05-14_SEEDERS_FACTORIES.md
│
└── 07_extras/                           🎁 Extras & References
    ├── ERD_BelajarKUY.html              Visual ERD (HTML)
    ├── TECH_STACK_EXTRAS.md             Additional tech details (Alpine, Reverb, dll)
    └── AUDIT_DOCS_REVIEW.md             Docs audit report (14 Mei 2026)
```

---

## 👥 Tim Pengembang

| # | Nama | Role | Tanggung Jawab Utama |
|---|------|------|---------------------|
| 1 | **Yosua Valentino** | Project Manager | Arsitektur, DB, integrasi, code review |
| 2 | **Albariqi Tarigan** | Backend Developer | Auth, course management, course player |
| 3 | **Ray Nathan** | Backend Developer | Payment (Midtrans), cart, wishlist, coupon |
| 4 | **Vascha U** | Frontend Developer | Landing page, course detail, student panel |
| 5 | **Quinsha Ilmi** | UI/UX Developer | Admin panel, frontend components, UI/UX |

---

## 📈 Current Status (14 Mei 2026)

| Phase | Status | Notes |
|-------|--------|-------|
| P0: Project Setup | 🟢 Done | Laravel 12 + Tailwind + Vite |
| P1: Database (Migrations + Models) | 🟢 Done | 19 tables, 19 models |
| P1b: Seeders + Factories | 🟢 Done | 896 records seeded |
| P2: Auth System | 🔜 Next | Breeze + RoleMiddleware + Google OAuth |
| P3+: Remaining Features | 🔴 Pending | Lihat `04_plans/MASTER_ROADMAP.md` |

**Overall:** ~15% complete.

Lihat `06_reports/PROGRESS_TRACKER.md` untuk detail live.

---

## ⚠️ ATURAN PENTING

1. **SELALU** baca `AGENT_GUIDELINES.md` sebelum mulai coding
2. **SELALU** baca `GLOSSARY.md` untuk terminologi yang ambigu
3. **JANGAN** modifikasi migration yang sudah dibuat tanpa persetujuan PM
4. **JANGAN** pakai Stripe — SELALU Midtrans Sandbox (ADR-001, ADR-004)
5. **JANGAN** upload ke `public/uploads/` — SELALU Cloudinary
6. **JANGAN** cek enrollment via `Order::where(...)` — pakai `Enrollment` table
7. **SELALU** commit sesuai Conventional Commits: `feat(module): ...`
8. **JANGAN** push ke `main` langsung — selalu via feature branch + PR
9. **UPDATE** `06_reports/PROGRESS_TRACKER.md` setelah setiap sesi kerja
10. **BUAT** daily report di `06_reports/REPORT_YYYY-MM-DD_TOPIC.md` setelah milestone

---

## 🔗 Quick Links

- [📖 Glossary](./01_guides/GLOSSARY.md) — Terminology dictionary
- [🛡️ Security Guidelines](./01_guides/SECURITY_GUIDELINES.md) — Security checklist
- [🧪 Testing Strategy](./01_guides/TESTING_STRATEGY.md) — Testing patterns
- [🏛️ ADR Index](./02_architecture/ADR/README.md) — Decision records
- [🗃️ Database Schema](./02_architecture/DATABASE_SCHEMA.md) — v2 canonical
- [🎬 Course Player Spec](./03_features/F13_COURSE_PLAYER.md) — Core LMS feature
- [📢 Notifications Spec](./03_features/F14_NOTIFICATIONS.md) — Mail + real-time
- [📜 Changelog](./CHANGELOG.md) — Docs changelog

---

*Single source of truth untuk seluruh development BelajarKUY.*
