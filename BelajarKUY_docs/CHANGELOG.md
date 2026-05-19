# 📜 BelajarKUY Docs — Changelog

> Log perubahan struktur & konten dokumentasi BelajarKUY.
> Format: [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), versioning by date.

---

## [2026-05-14] — Documentation Cleanup (Audit Remediation)

Mayor cleanup berdasarkan audit di `07_extras/AUDIT_DOCS_REVIEW.md`.

### ➕ Added

- **`01_guides/GLOSSARY.md`** — Kamus terminologi resmi (student/user, paid/purchased/enrolled, dll)
- **`01_guides/SECURITY_GUIDELINES.md`** — Security checklist per layer
- **`01_guides/TESTING_STRATEGY.md`** — Testing pyramid + templates
- **`02_architecture/ADR/`** folder — 7 Architecture Decision Records:
  - ADR-001: Midtrans payment gateway
  - ADR-002: Blade (not Livewire/Inertia)
  - ADR-003: Denormalized `instructor_id` in orders
  - ADR-004: Sandbox-only Midtrans
  - ADR-005: Payout out of scope
  - ADR-006: Instructor auto-active
  - ADR-007: Role naming duality (user/student)
- **`03_features/F13_COURSE_PLAYER.md`** — Watch page + progress tracking spec
- **`03_features/F14_NOTIFICATIONS.md`** — Mail + real-time event mapping
- **`CHANGELOG.md`** — File ini

### 🔧 Changed

- **`01_guides/AGENT_GUIDELINES.md`** — Version 2.0:
  - Section 5.3 fix: Enrollment check pakai `Enrollment` table (bukan Order)
  - Section 5.4 hapus: 70/30 payout split (ADR-005)
  - Tambah section 5.6 Instructor Lifecycle (ADR-006)
  - Tambah strict constraint tentang Cloudinary, enrollment, payout
  - Tambah section 4.6 Media Upload Rule
  - Tambah mandatory reading section di atas
- **`03_features/F05_CART_WISHLIST.md`** — AJAX contract disinkronkan Schema v2 (no price/instructor_id fields)
- **`03_features/F11_COUPON_SYSTEM.md`** — Code examples pakai field v2 (`code`, `discount_percent`, `valid_until`)
- **`03_features/F06_PAYMENT_MIDTRANS.md`** — `handleSuccess()` lengkap dengan Enrollment + mail + broadcast
- **`03_features/F07_ADMIN_PANEL.md`** — Hapus halaman Mail/Midtrans/Google/Cloudinary settings UI; Instructor view-only (ADR-006)
- **`03_features/F01_AUTH_SYSTEM.md`** — Welcome email reference, Cloudinary profile photo
- **`03_features/F09_STUDENT_PANEL.md`** — Tambah link ke Course Player (F13), enrollment query
- **`03_features/F03_COURSE_MANAGEMENT.md`** — Thumbnail upload ke Cloudinary (bukan public/uploads/)
- **`02_architecture/API_ROUTES.md`** — Route summary updated, admin settings routes removed, Course Player routes added, `role:student` alias explained
- **`02_architecture/FOLDER_STRUCTURE.md`** — Clarify no `public/uploads/` (Cloudinary)
- **`02_architecture/TECH_STACK.md`** — Env: `APP_LOCALE=id`, SQLite default dev, remove `MIDTRANS_IS_PRODUCTION`
- **`01_guides/GIT_WORKFLOW.md`** — Simplified (no `develop` branch), Conventional Commits style
- **`01_guides/SETUP_GUIDE.md`** — Demo accounts pakai `.test` domain, password info
- **`04_plans/MASTER_ROADMAP.md`** — Relative days (Day N) bukan tanggal absolut, Phase 4 tambah Course Player
- **`05_prompts/PROMPT_SETUP_PROJECT.md`** — APP_LOCALE=id, timezone, no `MIDTRANS_IS_PRODUCTION`
- **`05_prompts/PROMPT_AUTH.md`** — Cloudinary upload (bukan public/uploads)
- **`05_prompts/PROMPT_ADMIN_PANEL.md`** — Hapus mail/midtrans/google settings pages
- **`.env.example`** — Full rewrite: locale=id, timezone Jakarta, SQLite default, Cloudinary/Meilisearch/Reverb sections, no MIDTRANS_IS_PRODUCTION
- **`07_extras/MODERN_TECH_STACK_RECOMMENDATIONS.md`** → Renamed to **`TECH_STACK_EXTRAS.md`**
- **`00_INDEX.md`** — Rewritten: reflect new structure, quick navigation

### ➖ Removed

- **`02_architecture/DATABASE_MIGRATIONS_PROMPT.md`** — Duplikat outdated (Schema v1). `05_prompts/PROMPT_MIGRATIONS.md` adalah source of truth.

---

## [2026-05-13] — Session 3 (Kiro)

### ➕ Added
- 19 database migration files (Schema v2) di `BelajarKUY/database/migrations/`
- 19 Eloquent models di `BelajarKUY/app/Models/`
- `07_extras/ERD_BelajarKUY.html` — Visual ERD
- `06_reports/REPORT_2026-05-13_DATABASE_LAYER.md`

### 🔧 Changed
- `05_prompts/PROMPT_MIGRATIONS.md` — Align ke Schema v2
- `05_prompts/PROMPT_MODELS.md` — Align ke Schema v2
- `06_reports/PROGRESS_TRACKER.md` — Phase 1 database 100%

---

## [2026-05-14] — Session 4 (Kiro)

### ➕ Added
- 19 model factories di `BelajarKUY/database/factories/`
- 5 seeders orchestrated: `UserSeeder`, `CategorySeeder`, `CourseSeeder`, `TransactionSeeder`, `CmsSeeder`
- `06_reports/REPORT_2026-05-14_SEEDERS_FACTORIES.md`

### ✅ Verified
- `php artisan migrate:fresh --seed` on SQLite → 22 migrations + 5 seeders PASS, 896 records

---

## [2026-05-13] — Session 2 (Antigravity)

### ➕ Added
- `01_guides/UI_UX_GUIDELINES.md` — Design workflow

---

## [2026-05-12] — Session 1 (Yosua) — Initial Setup

### ➕ Added
- Initial `BelajarKUY_docs/` structure
- All baseline documentation (INDEX, guides, architecture, features, plans, prompts)
- Laravel 12 project initialized
- TailwindCSS + Vite setup

---

*Format konvensi: `### ➕ Added | 🔧 Changed | ➖ Removed | ✅ Verified | 🐛 Fixed`*
