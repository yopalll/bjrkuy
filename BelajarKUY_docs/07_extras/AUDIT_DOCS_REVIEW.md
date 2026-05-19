# 🔍 Documentation Audit Report — BelajarKUY

> **Audited by:** Kiro (AI Agent acting as Senior System Designer)
> **Audit Date:** 14 Mei 2026
> **Scope:** Seluruh `BelajarKUY_docs/` (29 files)
> **Status:** 🟡 **Proposal — butuh review & approval dari PM sebelum eksekusi**

---

## 🎯 Executive Summary

Dokumentasi BelajarKUY sudah **sangat lengkap** untuk project akademik — struktur folder bagus, tech stack modern (Laravel 12 + Meilisearch + Reverb + Cloudinary), dan prompt-driven development yang AI-friendly. Namun audit mendalam menemukan **30+ isu** yang membuat dokumentasi ini belum efektif sebagai *single source of truth* untuk AI agent:

| Kategori | Jumlah Isu | Severity |
|----------|------------|----------|
| **Schema drift** (dokumen tidak sinkron dengan DATABASE_SCHEMA v2) | 7 | 🔴 Critical |
| **Spec gaps** (fitur core belum didefinisikan) | 6 | 🔴 Critical |
| **Terminology inconsistencies** | 5 | 🟠 High |
| **Architecture decisions undocumented** | 4 | 🟠 High |
| **Security/operations gaps** | 5 | 🟡 Medium |
| **Minor inconsistencies** (domain, lokasi file, dll.) | 6 | 🟢 Low |

**Rekomendasi:** Lakukan *documentation cleanup sprint* selama 1 hari untuk menutup critical gaps **sebelum** Phase 2 (Auth) dimulai. Investasi awal ini akan mencegah AI agent mengambil keputusan yang berbeda di tiap session.

---

## 📐 Audit Methodology

Saya membaca setiap file di `BelajarKUY_docs/` sebagai AI agent yang akan eksekusi task, lalu mencari:

1. **Contradiction** — apakah 2 dokumen berbeda mengatakan hal berbeda untuk topik sama?
2. **Gap** — apakah ada fitur dalam roadmap yang tidak punya spec detail?
3. **Ambiguity** — apakah instruksi bisa ditafsirkan >1 cara?
4. **Obsolete** — apakah dokumen merefleksikan keputusan lama yang sudah berubah?
5. **Drift** — apakah dokumen sinkron dengan kode yang sudah ada?

---

## 🔴 CRITICAL ISSUES

### C-1. Schema Drift: `DATABASE_MIGRATIONS_PROMPT.md` masih versi lama

**Lokasi:** `02_architecture/DATABASE_MIGRATIONS_PROMPT.md`

**Problem:** Dokumen ini masih berbasis Schema v1. Setiap AI agent yang membaca file ini akan menghasilkan migration **salah**:

| Field | MIGRATIONS_PROMPT (lama) | SCHEMA v2 (benar) |
|-------|--------------------------|---------------------|
| `midtrans_configs` table | Masih ada | **Dihapus** (security) |
| `carts.instructor_id`, `carts.price` | Masih ada | **Dihapus** (derivable) |
| `coupons.name`, `coupons.validity`, `coupons.discount` | Field lama | Seharusnya `code`, `valid_until`, `discount_percent` + `course_id` + `max_usage` + `used_count` |
| `orders.price` | Single field | Seharusnya `original_price` + `discount_amount` + `final_price` + `coupon_id` |
| `enrollments` table | **Tidak ada** | **Wajib ada** (NEW v2) |
| `lecture_completions` table | **Tidak ada** | **Wajib ada** (NEW v2) |
| `reviews.status` default | `false` | `true` |
| `course_sections.order` / `course_lectures.order` | `order` | Seharusnya `sort_order` |

**Dampak:** Agent baru yang tidak baca Schema v2 akan generate migration lama → merusak referential integrity dengan codebase saat ini.

**Rekomendasi:**
- ✅ **Hapus file `DATABASE_MIGRATIONS_PROMPT.md`** — duplikasi `PROMPT_MIGRATIONS.md` tapi outdated. Satu source of truth cukup.
- Alternatif: redirect (1-liner file) yang cuma point ke `05_prompts/PROMPT_MIGRATIONS.md`.

---

### C-2. `F05_CART_WISHLIST.md` — AJAX endpoint kontradiksi dengan Schema v2

**Problem:**
```md
POST /cart/add → { course_id, instructor_id, price }   ← Schema v1 style
```

Tapi Schema v2 eksplisit: `carts` **tidak punya** `instructor_id` dan `price`. Kalau agent ikuti docs ini, akan crash saat insert.

**Rekomendasi:** Update kontrak AJAX ke:
```md
POST /cart/add → { course_id }
Response: { success: true, cart_count: N, cart_item: {...} }
```

Price & instructor dihitung real-time di controller menggunakan `$course->price` dan `$course->discount`.

---

### C-3. `F11_COUPON_SYSTEM.md` — contoh kode pakai field lama

**Problem:** Code snippet pakai `name`, `discount`, `validity` — semua sudah di-rename di Schema v2.

**Rekomendasi:** Rewrite example lengkap dengan:
```php
$coupon = Coupon::active()  // scope sudah implement di Model
    ->where('code', $request->coupon_code)
    ->where(function ($q) use ($courseId) {
        $q->whereNull('course_id')         // global
          ->orWhere('course_id', $courseId);  // specific
    })
    ->first();

if (!$coupon) { /* invalid */ }

$discountAmount = $totalPrice * $coupon->discount_percent / 100;
```

---

### C-4. `AGENT_GUIDELINES` section 5.3 — Enrollment check pakai query lama

**Problem:**
```php
$isEnrolled = Order::where('user_id', ...)->where('status', 'completed')->exists();
```

Tapi Schema v2 sudah ada `enrollments` table khusus untuk ini. Query ini juga lebih lambat karena harus check join ke payments status.

**Rekomendasi:** Update ke:
```php
$isEnrolled = Enrollment::where('user_id', auth()->id())
    ->where('course_id', $courseId)
    ->exists();
```

Alasan tabel `enrollments` dibuat — gunakan.

---

### C-5. Missing Spec: Course Player / Watch Page

**Problem:** Ini adalah **core LMS feature** (bagaimana student nonton materi) tapi:
- `F03_COURSE_MANAGEMENT.md` hanya covers CRUD by instructor
- `F09_STUDENT_PANEL.md` cuma list halaman (dashboard, wishlist, profile) — **tidak ada Course Player**
- `API_ROUTES.md` tidak ada route `/course/{slug}/learn/{lecture}` atau semacamnya
- Tidak ada controller mapping
- `lecture_completions` model/table exists tapi spec cara pakainya tidak ada

**Yang hilang:**
- Route `/user/course/{slug}/learn` atau `/course/{slug}/watch/{lectureId}`
- `StudentCoursePlayerController` atau sejenisnya
- Blade view: `frontend/course-player.blade.php`
- API endpoint: `POST /lecture/{id}/complete` untuk mark complete
- Progress calculation logic: `completions / total_lectures * 100`
- Auto-play next lecture after complete

**Rekomendasi:** Buat `03_features/F13_COURSE_PLAYER.md` dengan spec lengkap.

---

### C-6. Missing Spec: Payout / Revenue Split

**Problem:** `AGENT_GUIDELINES` section 5.4 mention:
> Instructor dapat 70% dari harga kursus

Tapi:
- **Tidak ada tabel `payouts`** — gimana track berapa yang sudah dibayar ke instructor?
- **Tidak ada field `platform_fee` / `instructor_share`** di `orders`
- `F07_ADMIN_PANEL` menyebut "approve Payout" tapi endpoint & UI tidak didefinisikan
- Schema v2 tidak punya foundation untuk ini

**Rekomendasi:** Decide: apakah Payout feature ada di scope MVP atau tidak? Kalau ya, bikin:
- `F14_PAYOUT_SYSTEM.md` — spec lengkap
- Tambah tabel `payouts` di Schema v3 (dengan persetujuan PM)
- Atau: tulis eksplisit "Out of Scope for Academic MVP" di AGENT_GUIDELINES

---

### C-7. Missing Spec: Instructor Approval Flow

**Problem:** `F07_ADMIN_PANEL`:
> Approve/Block — Toggle active status

Tapi:
- `users` table tidak punya field `is_approved` / `approval_status`
- Schema v2 cuma punya `role` enum
- Unclear: siapa bisa menjadi instructor? Apakah langsung aktif saat register sebagai instructor? Atau butuh approval admin dulu?

**Rekomendasi:** Decide:
- **Option A (simpler):** Register sebagai instructor langsung aktif — skip approval. Admin hanya bisa block dengan mengubah `role` ke `user` atau delete account.
- **Option B (realistic):** Tambah `users.is_approved` boolean dan `users.approval_status` enum. Butuh Schema v3.

---

## 🟠 HIGH SEVERITY ISSUES

### H-1. Role Naming Inconsistency: `user` vs `student`

**Problem:**
- DB value: `role = 'user'`
- Route prefix: `/user/*`
- Controller: `StudentDashboardController`
- Folder: `Backend/Student/`
- Model scope: `scopeStudents()`
- Docs sometimes say "user", sometimes "student"

**Dampak:** AI agent bingung kapan pakai "user" kapan "student". Ada risiko method/variable naming inconsistent.

**Rekomendasi:** Buat **Glossary** di `01_guides/GLOSSARY.md`:
```md
- "Student" (UI-facing term, folder name, controller name) 
  ≡ User with role='user' (DB value)
- Route prefix is /user/ for historical compatibility
- Always use "Student" in UI text (Bahasa Indonesia: "Siswa")
- Always use "user" as DB value
```

Atau lebih baik: refactor sekarang — ubah enum `user` → `student` di migrations. Tapi butuh Schema v3.

---

### H-2. Terminology Drift — "enrolled" vs "purchased" vs "active order"

**Problem:** Sama-sama menyatakan hal sama (student sudah punya akses ke course) tapi dibahas berbeda:
- `AGENT_GUIDELINES` section 5.3: check via `orders.status = completed`
- `REPORT_2026-05-14`: "13 enrollments"
- `F09_STUDENT_PANEL`: "kursus yang sudah dibeli" (purchased)
- Schema v2: ada tabel `enrollments` terpisah

**Rekomendasi:** Standardize di Glossary:
```
"Enrolled" = ada record di `enrollments` table = student bisa akses konten
"Purchased" = proses pembelian = ada record di `orders.status = completed`
"Paid" = ada record di `payments.status IN (settlement, capture)`

Flow: Paid Payment → Completed Order → Created Enrollment
```

Semua code & docs harus pakai "enrolled" untuk akses-related check.

---

### H-3. Media Storage Inconsistency — Cloudinary vs public/uploads/

**Problem:** Keputusan sudah adopt Cloudinary (lihat `07_extras/MODERN_TECH_STACK_RECOMMENDATIONS.md`), tapi:
- `PROMPT_AUTH.md` instruksikan: "Photo upload ke public/uploads/profiles/"
- `F03_COURSE_MANAGEMENT.md`: "Thumbnail di-upload ke public/uploads/courses/"
- `FOLDER_STRUCTURE.md`: masih ada `public/uploads/` struktur
- `PROMPT_ADMIN_PANEL.md`: sekarang sudah benar (→ Cloudinary)

**Rekomendasi:** Sweep semua file, ganti semua "public/uploads/" → "Cloudinary upload". Buat section di `MODERN_TECH_STACK_RECOMMENDATIONS` yang jadi authoritative: "SEMUA media upload ke Cloudinary. public/uploads/ digunakan HANYA untuk static assets (ikon brand, dll)."

---

### H-4. Midtrans Production Flag Conflict

**Problem:**
- `F06_PAYMENT_MIDTRANS.md`: "is_production SELALU false (hardcoded, bukan env)"
- `TECH_STACK.md` .env example: `MIDTRANS_IS_PRODUCTION=false` (dari env)
- `AGENT_GUIDELINES`: "is_production SELALU false (hardcoded)"
- `PROMPT_SETUP_PROJECT.md`: include `MIDTRANS_IS_PRODUCTION` di env

**Rekomendasi:** Decide one:
- **Option A (safer for academic):** Hardcode `false` di `config/midtrans.php`, **hapus** `MIDTRANS_IS_PRODUCTION` dari `.env.example` dan `TECH_STACK.md`. Jelaskan di comments: "This is an academic project, production mode is disabled by design."
- **Option B (flexible):** Use env var, default false, add note: "Only set to true if instructor says so for final demo."

Saya rekomendasikan **Option A** — lebih sulit salah.

---

### H-5. Route Naming vs Folder Naming Mismatch (User → Student)

**Problem:** Route `/user/dashboard` tapi controller di `Backend/Student/DashboardController`. `API_ROUTES.md` pakai `user.dashboard` route name. Ini confusing.

**Rekomendasi:** Pick one:
- **Quick fix (recommended):** Tetap pakai `/user/*` route prefix (lebih match DB), TAPI rename folder `Backend/Student/` → `Backend/User/` dan `StudentDashboardController` → `UserDashboardController`. Sinkronkan semuanya pakai `user`.
- **Alternative:** Rename semua jadi "student" termasuk enum di migrations. Butuh Schema v3.

---

## 🟡 MEDIUM SEVERITY ISSUES

### M-1. Settings Pages — Config vs UI Ambiguity

**Problem:** `F07_ADMIN_PANEL` section 11-14 lists pages untuk:
- Mail Settings
- Midtrans Settings  
- Google Settings
- Cloudinary Settings

Tapi semua values adalah `.env` variables. `PROMPT_ADMIN_PANEL` akhirnya bilang "reference only — values in .env". Apa artinya?
- Kalau read-only, kenapa ada Edit page?
- Kalau writable, di mana disimpan (DB? File? .env)?

**Rekomendasi:** Remove halaman Edit untuk API credentials. Ganti dengan "View Only" reference page yang menunjukkan:
- Environment variables yang harus di-set
- Instructions cara mengubah (via .env + server restart)
- Connection test button (opsional — test ping ke Midtrans API)

Feature "edit via UI" biasanya untuk shared-hosting deploy di mana admin tidak punya akses server. Untuk project akademik ini, overkill dan introduce security risk.

---

### M-2. Mail Notifications — Coverage Incomplete

**Problem:** `07_extras/MODERN_TECH_STACK_RECOMMENDATIONS.md` list 6 mail types tapi:
- `F01_AUTH_SYSTEM.md` tidak mention Welcome email
- `F06_PAYMENT_MIDTRANS.md` tidak mention Order Confirmation email
- `F03_COURSE_MANAGEMENT.md` tidak mention Course Approved/Rejected email
- Tidak jelas kapan di-trigger, template di mana

**Rekomendasi:** Buat `03_features/F13_NOTIFICATIONS.md` (atau rename F13 jadi COURSE_PLAYER dan ini F14) dengan mapping lengkap:

| Event | Trigger Point | Recipient | Mail Class | Template |
|-------|---------------|-----------|------------|----------|
| User register | RegisteredUserController | New user | `WelcomeMail` | `emails.welcome` |
| Payment settlement | CheckoutController::handleSuccess | Buyer | `OrderConfirmationMail` | `emails.order-confirmation` |
| New sale | CheckoutController::handleSuccess | Instructor | `NewSaleMail` | `emails.new-sale` |
| Course approved | AdminCourseController::updateStatus | Instructor | `CourseApprovedMail` | `emails.course-approved` |
| Course rejected | AdminCourseController::updateStatus | Instructor | `CourseRejectedMail` | `emails.course-rejected` |
| Password reset | Breeze default | User | — (built-in) | — |

---

### M-3. Security Posture Incomplete

**Problem:** `CODING_STANDARDS.md` section 1.5 list 7 security rules. Tidak cover:
- Rate limiting — Laravel `throttle` middleware mana yang dipakai?
- CSRF webhook exclusion — `/payment/callback` harus di-exclude tapi tidak diinstruksikan di mana
- Session security — SameSite, HttpOnly, Secure cookie
- Password policy — Laravel default atau custom?
- Audit trail — admin action logging?
- XSS escape — Blade `{{ }}` default escape, tapi `{!! !!}` untuk raw HTML

**Rekomendasi:** Buat `01_guides/SECURITY_GUIDELINES.md` dengan:
- Checklist minimal per feature
- Contoh kode untuk each concern
- Session config hardening pattern

---

### M-4. Testing Strategy Missing

**Problem:** `CODING_STANDARDS.md` cuma 1 contoh test Feature test. Tidak ada:
- Testing pyramid (Unit vs Feature vs Browser)
- Folder structure (tests/Feature/ tests/Unit/)
- Target coverage (60%? 80%?)
- CI integration (GitHub Actions?)
- Mocking strategy untuk Midtrans callback

**Rekomendasi:** Buat `01_guides/TESTING_STRATEGY.md` dengan template test per layer:
```
tests/
├── Feature/
│   ├── Auth/LoginTest.php
│   ├── Course/CourseCrudTest.php
│   ├── Payment/CheckoutTest.php (mock Midtrans)
│   └── ...
├── Unit/
│   ├── Models/CourseTest.php (test accessors, scopes)
│   ├── Services/MidtransServiceTest.php
│   └── ...
└── Browser/ (Optional — Laravel Dusk)
```

Minimum target untuk MVP: **Feature tests for happy paths di setiap major controller.**

---

### M-5. Environment Config Drift

**Problem:**
- `SETUP_GUIDE.md` says `DB_CONNECTION=mysql`
- `.env.example` aktual: `DB_CONNECTION=sqlite`
- `TECH_STACK.md` env contoh pakai `mysql`
- `REPORT_2026-05-14` bilang "di production pakai MySQL"
- `APP_LOCALE=en` di `.env.example`, tapi UI harus Indonesian — UI text in Bahasa Indonesia tidak butuh locale=id selama pakai plain Blade, tapi kalau pakai Laravel translation helper akan bermasalah

**Rekomendasi:** Update `.env.example`:
```env
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta
DB_CONNECTION=sqlite  # default untuk dev cepat, ganti ke mysql untuk prod
```

Dan tambah section di `SETUP_GUIDE.md`: "Dev dengan SQLite (zero setup) atau MySQL (production parity)."

---

## 🟢 LOW SEVERITY ISSUES

### L-1. Demo Account Domain Inconsistent

- `SETUP_GUIDE.md`: `admin@belajarkuy.com`
- `REPORT_2026-05-14` + `UserSeeder.php`: `admin@belajarkuy.test`

**Rekomendasi:** Standarisasi ke `belajarkuy.test` (TLD `.test` reserved untuk testing, tidak konflik dengan real domain). Update `SETUP_GUIDE.md`.

---

### L-2. Date Inconsistency in Roadmap

**Problem:** `MASTER_ROADMAP.md` mention tanggal absolut (`2026-05-12`, `2026-05-15`, dll). Deadline project akademik biasanya bergeser.

**Rekomendasi:** Ganti jadi relative day:
```
Day 1-3: Foundation
Day 4-6: Auth
Day 7-13: Core Features
...
```

Lebih future-proof dan tetap informative.

---

### L-3. Git Workflow vs Reality

**Problem:** `GIT_WORKFLOW.md` mention `develop` branch sebagai integration branch. Saat ini branch yang ada:
- `main`
- `feature/database-migrations`
- `feature/database-seeders`

Tidak ada `develop`.

**Rekomendasi:** Salah satu:
- **Option A (follow docs):** PM create `develop` branch, semua feature PR ke develop.
- **Option B (simplify docs):** Hapus `develop`, feature branches PR langsung ke `main`. Untuk tim kecil (5 orang) di project 4 minggu, ini sudah cukup. Update `GIT_WORKFLOW.md`.

Rekomendasi saya: **Option B** — simpler, sesuai skala project.

---

### L-4. 00_INDEX.md Outdated

**Problem:** Last updated 12 Mei, tidak include:
- `UI_UX_GUIDELINES.md` (Session 2)
- `REPORT_2026-05-13_*.md` dan `REPORT_2026-05-14_*.md`
- `ERD_BelajarKUY.html`
- `MODERN_TECH_STACK_RECOMMENDATIONS.md` (referenced tapi tidak di tree)
- File ini (`AUDIT_DOCS_REVIEW.md`)

**Rekomendasi:** Update tree di INDEX. Tambah Last Updated badge per section.

---

### L-5. Report File Naming Convention Undocumented

**Problem:** Saya pakai `REPORT_YYYY-MM-DD_TOPIC.md` tapi tidak ada standar.

**Rekomendasi:** Documentkan di `AGENT_GUIDELINES.md` section 9:
```
Format: REPORT_YYYY-MM-DD_TOPIC.md (TOPIC in SNAKE_CASE, ALL CAPS)
Contoh: REPORT_2026-05-14_SEEDERS_FACTORIES.md
```

---

### L-6. Team Task Distribution Overlap

**Problem:** `TASK_DISTRIBUTION.md` punya "Quinsha & Vascha" collaborating on many tasks, tapi siapa lead tidak jelas. Beberapa task double-counted.

**Rekomendasi:** Convention: "PIC utama: Nama1. Collaborator: Nama2."

---

## 📐 RECOMMENDED DOCUMENTATION STRUCTURE (PROPOSED V2)

Berdasarkan audit di atas, saya usulkan struktur baru yang lebih kohesif:

```
BelajarKUY_docs/
│
├── 00_INDEX.md                         ← Updated tree + quick links
├── CHANGELOG.md                        ← NEW: Track major doc changes
│
├── 01_guides/
│   ├── AGENT_GUIDELINES.md             ← Source of truth untuk AI agents
│   ├── CODING_STANDARDS.md             ← Style guide
│   ├── SECURITY_GUIDELINES.md          ← NEW: Security posture
│   ├── TESTING_STRATEGY.md             ← NEW: Testing pyramid + patterns
│   ├── GIT_WORKFLOW.md                 ← Simplified (no develop branch)
│   ├── GLOSSARY.md                     ← NEW: Terminology dictionary
│   ├── SETUP_GUIDE.md                  ← Updated .env, demo account
│   └── UI_UX_GUIDELINES.md
│
├── 02_architecture/
│   ├── TECH_STACK.md                   ← Core stack + reasoning (ADR embed)
│   ├── DATABASE_SCHEMA.md              ← v2 (canonical, jangan di-duplicate)
│   ├── FOLDER_STRUCTURE.md             ← Cloudinary-aligned, no public/uploads
│   ├── API_CONTRACTS.md                ← NEW: request/response schemas
│   ├── API_ROUTES.md                   ← Route table (referenced)
│   └── ADR/                            ← NEW: Architecture Decision Records
│       ├── ADR-001-why-midtrans.md
│       ├── ADR-002-why-blade-not-livewire.md
│       ├── ADR-003-denormalized-instructor-in-orders.md
│       └── ADR-004-sandbox-only-midtrans.md
│
├── 03_features/
│   ├── F01_AUTH_SYSTEM.md              ← + email notifications trigger
│   ├── F02_LANDING_PAGE.md             ← + Meilisearch hook
│   ├── F03_COURSE_MANAGEMENT.md        ← Cloudinary upload pattern
│   ├── F04_CATEGORY_SYSTEM.md
│   ├── F05_CART_WISHLIST.md            ← Updated AJAX contract (no price field)
│   ├── F06_PAYMENT_MIDTRANS.md         ← Enrollment creation hook
│   ├── F07_ADMIN_PANEL.md              ← Remove misleading "edit API keys" pages
│   ├── F08_INSTRUCTOR_PANEL.md
│   ├── F09_STUDENT_PANEL.md            ← + link ke Course Player
│   ├── F10_REVIEW_RATING.md
│   ├── F11_COUPON_SYSTEM.md            ← Updated fields (code, discount_percent, valid_until)
│   ├── F12_SITE_SETTINGS.md
│   ├── F13_COURSE_PLAYER.md            ← NEW: Watch page + progress tracking
│   └── F14_NOTIFICATIONS.md            ← NEW: Mail + Reverb events mapping
│
├── 04_plans/
│   ├── MASTER_ROADMAP.md               ← Relative days, not absolute dates
│   ├── SPRINT_PLAN.md
│   └── TASK_DISTRIBUTION.md            ← Clear lead vs collaborator
│
├── 05_prompts/
│   ├── PROMPT_SETUP_PROJECT.md
│   ├── PROMPT_MIGRATIONS.md            ← Align dengan Schema v2 (DONE)
│   ├── PROMPT_MODELS.md                ← Align (DONE)
│   ├── PROMPT_SEEDERS.md               ← NEW: explicit seeder generation prompt
│   ├── PROMPT_AUTH.md                  ← Cloudinary upload (not public/uploads)
│   ├── PROMPT_MIDTRANS.md
│   ├── PROMPT_FRONTEND.md
│   └── PROMPT_ADMIN_PANEL.md           ← Remove "edit API keys" pages
│
├── 06_reports/
│   ├── PROGRESS_TRACKER.md
│   ├── REPORT_YYYY-MM-DD_TOPIC.md      ← Standardized naming
│   └── README.md                       ← NEW: explain report format
│
└── 07_extras/
    ├── ERD_BelajarKUY.html
    ├── MODERN_TECH_STACK_RECOMMENDATIONS.md  ← Rename: TECH_STACK_EXTRAS.md
    ├── AUDIT_DOCS_REVIEW.md                  ← This file
    └── REFERENCE_PROJECT_NOTES.md             ← NEW: what we take/differ from YouTubeLMS

# File to REMOVE (redundant):
- 02_architecture/DATABASE_MIGRATIONS_PROMPT.md  (duplicate outdated)
```

---

## 🎯 PROPOSED ACTION PLAN (Priority Order)

Saya urutkan berdasarkan ROI — fix dulu yang impact besar tapi effort kecil:

### 🔥 Must-Do (Before Phase 2 / Auth)

| # | Action | Effort | Impact |
|---|--------|--------|--------|
| 1 | **Hapus** `02_architecture/DATABASE_MIGRATIONS_PROMPT.md` | 2 menit | 🔴 Critical — prevents schema drift |
| 2 | Update `F05_CART_WISHLIST.md` AJAX contract (no price field) | 10 menit | 🔴 |
| 3 | Update `F11_COUPON_SYSTEM.md` code example ke field v2 | 10 menit | 🔴 |
| 4 | Update `AGENT_GUIDELINES` section 5.3 pakai Enrollment query | 5 menit | 🔴 |
| 5 | Buat `01_guides/GLOSSARY.md` (student/user, enrolled/purchased/paid, dll) | 30 menit | 🟠 |
| 6 | Sweep "public/uploads/" → "Cloudinary" di semua file | 20 menit | 🟠 |
| 7 | Update `00_INDEX.md` dengan semua file baru | 10 menit | 🟢 |

**Total effort:** ~90 menit. **Impact:** Eliminate all schema drift risk.

### 🟠 Should-Do (Week 1)

| # | Action | Effort | Impact |
|---|--------|--------|--------|
| 8 | Buat `F13_COURSE_PLAYER.md` — spec lengkap | 2 jam | 🔴 Core feature missing |
| 9 | Buat `F14_NOTIFICATIONS.md` — email+event mapping | 1 jam | 🟠 |
| 10 | Buat `01_guides/TESTING_STRATEGY.md` | 1 jam | 🟡 |
| 11 | Buat `01_guides/SECURITY_GUIDELINES.md` | 1 jam | 🟡 |
| 12 | Decide: Payout in-scope atau out-of-scope? Document. | 30 menit | 🟠 |
| 13 | Decide: Instructor approval in-scope atau otomatis aktif? Document. | 30 menit | 🟠 |
| 14 | Update `MASTER_ROADMAP.md` pakai relative days | 15 menit | 🟢 |
| 15 | Update `GIT_WORKFLOW.md` — simplify (hapus `develop`) | 15 menit | 🟢 |
| 16 | Update `.env.example` (APP_LOCALE=id, APP_TIMEZONE, dll) | 10 menit | 🟡 |
| 17 | Standardize demo domain ke `.test` | 5 menit | 🟢 |

**Total effort:** ~7 jam.

### 🟢 Nice-to-Have (Later)

| # | Action | Effort |
|---|--------|--------|
| 18 | Buat ADR folder dengan 4 keputusan utama | 2 jam |
| 19 | Buat `API_CONTRACTS.md` — formal request/response schemas | 3 jam |
| 20 | Buat `PROMPT_SEEDERS.md` — explicit prompt untuk generate seeder | 30 menit |
| 21 | Buat `CHANGELOG.md` root level | 15 menit |

---

## ✍️ CONCRETE DIFFS (Yang Saya Rekomendasikan)

Jika PM setuju dengan audit ini, berikut patch spesifik yang bisa diterapkan (contoh untuk Must-Do items):

### Diff 1: `F05_CART_WISHLIST.md`

```diff
 ## AJAX Endpoints

 ```
 POST   /wishlist/add      → { course_id }
 GET    /wishlist/all       → JSON list wishlists
 DELETE /user/wishlist/{id} → Remove wishlist

-POST   /cart/add           → { course_id, instructor_id, price }
+POST   /cart/add           → { course_id }
+      Response:            → { success: true, cart_count: N }
 GET    /cart/all            → JSON list cart items
 GET    /cart/fetch          → JSON cart items for navbar badge
 POST   /cart/remove         → { id }
 ```
+
+## Pricing Logic
+
+⚠️ Harga dan instructor_id **tidak disimpan** di tabel `carts`.
+Saat tampilkan cart, hitung real-time:
+```php
+$cartItems = Cart::with('course.instructor')->get();
+foreach ($cartItems as $item) {
+    $price = $item->course->discounted_price; // accessor di Course model
+    $instructor = $item->course->instructor;
+}
+```
```

### Diff 2: `F11_COUPON_SYSTEM.md`

```diff
 ## Apply Coupon Logic

 ```php
 public function applyCoupon(Request $request)
 {
-    $coupon = Coupon::where('name', $request->coupon_code)
-        ->where('status', true)
-        ->where('validity', '>=', now()->format('Y-m-d'))
+    $coupon = Coupon::active()  // scope sudah di Model: status=true + valid_until>=today + usage limit check
+        ->where('code', $request->coupon_code)
+        ->where(function ($q) use ($courseId) {
+            $q->whereNull('course_id')          // Global coupon
+              ->orWhere('course_id', $courseId); // Course-specific
+        })
         ->first();

     if (!$coupon) {
         return response()->json(['error' => 'Kupon tidak valid atau sudah expired'], 400);
     }

-    $discountAmount = ($totalPrice * $coupon->discount) / 100;
+    $discountAmount = ($totalPrice * $coupon->discount_percent) / 100;
     $finalPrice = $totalPrice - $discountAmount;

+    // Increment used_count saat order finalized (bukan saat apply)
+    // $coupon->increment('used_count');

     return response()->json([
         'success' => true,
-        'discount' => $coupon->discount,
+        'discount_percent' => $coupon->discount_percent,
         'discount_amount' => $discountAmount,
         'final_price' => $finalPrice,
     ]);
 }
 ```
```

### Diff 3: `AGENT_GUIDELINES.md` section 5.3

```diff
 ### 5.3 Enrollment Check

 ```php
-// Cek apakah student sudah beli kursus ini
-$isEnrolled = Order::where('user_id', auth()->id())
+// Cek apakah student sudah enrolled ke kursus ini
+// Pakai tabel `enrollments` — tidak perlu join ke orders/payments
+$isEnrolled = Enrollment::where('user_id', auth()->id())
     ->where('course_id', $courseId)
-    ->where('status', 'completed')
     ->exists();
 ```
+
+Enrollment dibuat otomatis oleh `CheckoutController::handleSuccess()` setelah
+payment `settlement`/`capture`+`accept`. Flow: Payment → Order → Enrollment.
```

### Diff 4: Proposed new file `01_guides/GLOSSARY.md` (excerpt)

```md
# 📖 BelajarKUY Glossary

## Roles & Users

| Term | Meaning | Where to Use |
|------|---------|--------------|
| **User** (generic) | Anyone in `users` table | Code: DB values, variables |
| **Student** | User with `role='user'` | UI text (Siswa), folder names, controllers |
| **Instructor** | User with `role='instructor'` | UI text, folder names, controllers |
| **Admin** | User with `role='admin'` | UI text, folder names, controllers |

⚠️ Yes, "User" has dual meaning (table name AND role value). For role value, we kept `user` for backward-compat with YouTubeLMS reference. UI always says "Siswa"/"Student".

## Purchase States

| State | Meaning | Table |
|-------|---------|-------|
| **Paid** | Midtrans confirmed payment | `payments.status IN (settlement, capture)` |
| **Purchased** | Business-level purchase record | `orders.status = completed` |
| **Enrolled** | Student has access to course | `enrollments` row exists |

Flow: Paid → Purchased → Enrolled (all created in same transaction via `handleSuccess`).

For "can this student watch this course?" ALWAYS check `Enrollment` (fastest, simplest).

...
```

---

## 🧭 WHAT NOT TO CHANGE (intentionally)

Ini adalah hal-hal yang saat ini non-ideal tapi **jangan diubah** — cost > benefit:

- **Tech stack picks**: Midtrans, Blade, TailwindCSS v4, Alpine.js, Reverb — semua sudah diputuskan. Dokumentasikan alasan di ADR tapi jangan revisit.
- **Database Schema v2 itself**: Schema sudah solid. Kalau ada gap (misal: `payouts`), buat Schema v3 baru dengan proposal formal.
- **Kebijakan Sandbox-only Midtrans**: Sudah benar untuk project akademik.
- **Normalized 3NF + strategic denormalization**: Keputusan bagus (instructor_id di orders untuk reporting). Jangan diotak-atik.

---

## 📊 METRICS & SUCCESS CRITERIA

Setelah cleanup ini, metrik keberhasilannya:

1. ✅ **Zero schema drift**: Semua docs konsisten dengan `DATABASE_SCHEMA.md v2`
2. ✅ **Zero contradiction**: Tidak ada 2 docs yang mengatakan hal berbeda untuk topik sama
3. ✅ **Single-pass AI execution**: Agent bisa ambil 1 file feature spec dan selesaikan task tanpa perlu cross-reference file lain untuk klarifikasi (atau cross-reference-nya jelas lewat link)
4. ✅ **Glossary ambigu-free**: Tidak ada term yang bermakna ganda tanpa dokumentasi
5. ✅ **Core features fully spec'd**: Course Player, Payout decision, Instructor Approval — semua have a written position

---

## 🎬 REQUEST FOR PM DECISION

Sebelum saya eksekusi, saya perlu keputusan dari PM (Yosua) untuk:

1. **Payout feature**: In-scope untuk MVP atau tidak?
2. **Instructor approval flow**: Otomatis aktif atau perlu admin approve?
3. **Role naming**: Tetap `user` (DB value) atau refactor ke `student`?
4. **Git workflow**: Keep `develop` branch or simplify ke feature → main?
5. **Admin edit API keys UI**: Remove halaman-nya atau tetap ada sebagai "reference only"?

Setelah keputusan di atas, saya bisa langsung eksekusi patch dokumentasi sesuai action plan.

---

## 📎 APPENDIX: File Inventory

Inventory lengkap dokumen yang di-audit:

| File | Status | Issue Count |
|------|--------|-------------|
| `00_INDEX.md` | Outdated | L-4 |
| `01_guides/AGENT_GUIDELINES.md` | OK w/ minor fix | C-4, H-1, L-5 |
| `01_guides/CODING_STANDARDS.md` | OK | M-3, M-4 |
| `01_guides/GIT_WORKFLOW.md` | Diverge from reality | L-3 |
| `01_guides/SETUP_GUIDE.md` | Minor drift | L-1, M-5 |
| `01_guides/UI_UX_GUIDELINES.md` | OK | — |
| `02_architecture/API_ROUTES.md` | OK | C-5 (missing Course Player routes) |
| `02_architecture/DATABASE_MIGRATIONS_PROMPT.md` | **DELETE** | C-1 |
| `02_architecture/DATABASE_SCHEMA.md` | ✅ Authoritative | — |
| `02_architecture/FOLDER_STRUCTURE.md` | OK w/ fix | H-3 |
| `02_architecture/TECH_STACK.md` | OK w/ fix | H-4, M-5 |
| `03_features/F01_AUTH_SYSTEM.md` | Minor gap | M-2 |
| `03_features/F02_LANDING_PAGE.md` | OK | — |
| `03_features/F03_COURSE_MANAGEMENT.md` | OK w/ fix | H-3 |
| `03_features/F04_CATEGORY_SYSTEM.md` | OK | — |
| `03_features/F05_CART_WISHLIST.md` | **CRITICAL FIX** | C-2 |
| `03_features/F06_PAYMENT_MIDTRANS.md` | Minor gap | M-2 |
| `03_features/F07_ADMIN_PANEL.md` | Needs decision | C-6, C-7, M-1 |
| `03_features/F08_INSTRUCTOR_PANEL.md` | OK | — |
| `03_features/F09_STUDENT_PANEL.md` | Missing sub-feature | C-5 |
| `03_features/F10_REVIEW_RATING.md` | OK | — |
| `03_features/F11_COUPON_SYSTEM.md` | **CRITICAL FIX** | C-3 |
| `03_features/F12_SITE_SETTINGS.md` | OK | M-1 |
| `04_plans/MASTER_ROADMAP.md` | Dates outdated | L-2 |
| `04_plans/SPRINT_PLAN.md` | OK | — |
| `04_plans/TASK_DISTRIBUTION.md` | Ambiguous leads | L-6 |
| `05_prompts/PROMPT_ADMIN_PANEL.md` | OK w/ fix | M-1 |
| `05_prompts/PROMPT_AUTH.md` | Need fix | H-3 |
| `05_prompts/PROMPT_FRONTEND.md` | OK | — |
| `05_prompts/PROMPT_MIDTRANS.md` | OK | — |
| `05_prompts/PROMPT_MIGRATIONS.md` | ✅ Updated to v2 | — |
| `05_prompts/PROMPT_MODELS.md` | ✅ Updated to v2 | — |
| `05_prompts/PROMPT_SETUP_PROJECT.md` | Minor fix | H-4, M-5 |
| `06_reports/PROGRESS_TRACKER.md` | OK | — |
| `06_reports/REPORT_2026-05-13_*.md` | OK | — |
| `06_reports/REPORT_2026-05-14_*.md` | OK | — |
| `07_extras/ERD_BelajarKUY.html` | OK | — |
| `07_extras/MODERN_TECH_STACK_RECOMMENDATIONS.md` | Rename suggested | — |

---

*End of Audit Report — Kiro (Senior System Designer perspective)*
*Awaiting PM review & approval before executing changes.*



JAWABAN BY PM

Kiro membutuhkan persetujuanmu untuk 5 poin ini sebelum dia menyentuh kode atau dokumen:

1.Fitur Payout (Bagi Hasil): Tetapkan sebagai Out-of-Scope (Tidak untuk MVP). Fokuskan waktu tim untuk menyelesaikan fitur inti (seperti Player Video dan Sistem Transaksi). Fitur ini bisa dikerjakan belakangan jika masih ada sisa waktu sebelum demo.

2. Approval Instruktur: Pilih Otomatis Aktif. Tidak perlu approval admin agar proses testing fitur instruktur oleh tim lebih cepat. Admin cukup memiliki hak untuk memblokir akun jika diperlukan.

3.Penamaan Role: Tetap user di Database, tetapi gunakan student di UI dan Controller. Membuat Glossary adalah langkah yang tepat agar tidak perlu membongkar migrasi database yang sudah stabil.

4. Git Workflow: Hapus branch develop. Setiap anggota tim wajib membuat 1 branch per fitur (contoh: feature/cart-wishlist) lalu melakukan Pull Request langsung ke main. Ini secara spesifik menjawab kebutuhan Aslab/Dosen untuk melacak kontribusi individu.

5.UI Edit API Key Admin: Ubah menjadi Reference/View-Only. Nilai API untuk Midtrans dan Cloudinary tetap disimpan dengan aman di file .env dan tidak boleh diubah melalui antarmuka web.

Persetujuan Isu Kritis (Must-Do)

Hapus Dokumen Usang: Berikan izin penuh kepada Kiro untuk menghapus DATABASE_MIGRATIONS_PROMPT.md yang masih menggunakan versi lama agar tidak memicu kebingungan schema drift.

Update Kontrak AJAX: Setujui perubahan endpoint untuk Cart dan Wishlist. Harga dan ID Instruktur tidak perlu dikirim via AJAX, melainkan dihitung langsung di backend (Controller).

Perbaikan Kode Kupon: Minta Kiro memperbarui semua contoh kode di dokumen Kupon agar sesuai dengan skema v2 (code, valid_until, discount_percent).

Standarisasi Enrollment: Pastikan pengecekan akses siswa menggunakan tabel baru enrollments, bukan lagi mengecek status pesanan (orders) secara manual.

Penambahan Spesifikasi Core (Should-Do):

Spesifikasi Course Player (F13_COURSE_PLAYER.md): Ini sangat krusial karena merupakan fitur inti aplikasi. Minta Kiro mendeskripsikan route, tampilan, dan cara melacak progres belajar (lecture completions).

Standarisasi Penyimpanan: Setujui arahan untuk menggunakan Cloudinary pada semua unggahan media (foto profil, thumbnail kursus) dan hentikan penggunaan folder public/uploads/.

Notifikasi & Midtrans: Buat dokumen spesifikasi email (seperti email sambutan dan konfirmasi pesanan). Pastikan status Midtrans di-hardcode ke mode Sandbox (false) agar aman saat demo.


pastikan agar seluruh LLM melakukan apa yang dibuat di docs (termasuk membuat report, dan push ke branch setelah selesai mengerjakan sesuatu.)

