# 🤖 BelajarKUY — AI Agent System Guidelines

> **Version:** 2.0 | **Updated:** 14 Mei 2026
> **Purpose:** File ini adalah SYSTEM PROMPT wajib untuk SEMUA AI agent (LLM) yang mengerjakan project BelajarKUY. Baca SELURUH file ini sebelum memulai tugas apapun.
> **Applies To:** Claude, GPT, Gemini, Copilot, Cursor, Windsurf, Kiro, dan LLM lainnya.

---

## 🔔 BEFORE YOU START — Mandatory Reading

**Urutan baca:**

1. **File ini** (`AGENT_GUIDELINES.md`) — System prompt utama
2. **`GLOSSARY.md`** — Terminologi yang sering ambigu (user/student, paid/purchased/enrolled, dll). WAJIB baca — prevents confusion 90% kasus.
3. **`DATABASE_SCHEMA.md`** — Schema v2 canonical. Jika ada kontradiksi dengan file lain, schema ini yang benar.
4. **`ADR/`** folder — Architecture Decision Records. Baca untuk reasoning di balik keputusan design.
5. File spesifik feature di `03_features/` untuk task yang dikerjakan.

---

## 1. MISI PROYEK

**BelajarKUY** adalah platform e-learning berbasis web (clone Udemy versi Indonesia) yang dibangun sebagai tugas besar kuliah. Platform ini memungkinkan:

- **Student** → Mendaftar, browse kursus, beli kursus, belajar, beri review
- **Instructor** → Membuat & mengelola kursus, section, lecture, kupon
- **Admin** → Mengelola seluruh platform (user, kursus, kategori, order, setting)

### Referensi Fitur
Project ini terinspirasi dari [Shuvouits/YouTubeLMS](https://github.com/Shuvouits/YouTubeLMS) (Laravel 11 LMS). Kita membangun versi yang **LEBIH BAIK** menggunakan **Laravel 12** dengan payment gateway **Midtrans** (bukan Stripe). Lihat `ADR-001` untuk reasoning.

---

## 2. ARSITEKTUR TEKNIS

### 2.1 Tech Stack

| Layer | Teknologi | Versi |
|-------|-----------|-------|
| **Backend** | Laravel | 12.x (boot Laravel 13 compatible) |
| **PHP** | PHP | ^8.3 |
| **Database** | MySQL (prod) / SQLite (dev) | 8.x / 3.x |
| **Frontend** | Blade + TailwindCSS | v4 |
| **JS Interactivity** | Alpine.js | ^3.x |
| **Build Tool** | Vite | Latest |
| **Payment** | Midtrans Snap API | v2 (sandbox only — ADR-004) |
| **Auth** | Laravel Breeze (Blade) | Latest |
| **Social Login** | Laravel Socialite (Google) | Latest |
| **Media Storage** | Cloudinary | Latest |
| **Video Hosting** | YouTube (Unlisted) | — |
| **Search Engine** | Meilisearch + Laravel Scout | Latest |
| **Real-time** | Laravel Reverb (WebSocket) | Latest |
| **Email (prod)** | Resend | Latest |
| **Email (dev)** | Mailtrap / log driver | — |
| **Admin Panel** | Custom Blade (bukan Filament) | — |

### 2.2 Database Schema (Canonical)

> ⚠️ **SINGLE SOURCE OF TRUTH:** `02_architecture/DATABASE_SCHEMA.md` (v2).
> 19 tabel. JANGAN modifikasi schema tanpa persetujuan PM.

Tabel-tabel yang ada:
```
users (extended), categories, sub_categories, courses, course_goals,
course_sections, course_lectures, wishlists, carts, coupons,
payments, orders, enrollments, lecture_completions, reviews,
sliders, info_boxes, partners, site_infos
```

### 2.3 Eloquent Models

Lokasi: `app/Models/` — **19 model** sudah tersedia dengan relationships, scopes, casts, dan accessors lengkap.

### 2.4 Role System

Lihat `01_guides/GLOSSARY.md` untuk klarifikasi naming:

| Role | DB enum | Akses |
|------|---------|-------|
| Student | `user` | Browse, beli kursus, enroll, wishlist, review |
| Instructor | `instructor` | + Buat/edit kursus, section, lecture, kupon |
| Admin | `admin` | + Kelola seluruh platform |

⚠️ Dalam UI selalu pakai **"Siswa"** (Indonesian) / **"Student"** (English).
Dalam code business logic pakai `student`. Dalam DB value pakai `user`. Lihat `ADR-007`.

---

## 3. PRIORITAS DEVELOPMENT

Kerjakan sesuai urutan prioritas. **JANGAN** loncat ke prioritas lebih rendah sebelum yang lebih tinggi selesai.

| Priority | Modul | Status | PIC |
|----------|-------|--------|-----|
| **P0** | Project Setup (Laravel 12 + DB) | ✅ DONE | Yosua |
| **P1** | Database Migrations & Models | ✅ DONE | Yosua |
| **P1b** | Seeders & Factories | ✅ DONE | Yosua |
| **P2** | Auth System (Breeze + Role + Google) | 🔜 NEXT | Albariqi |
| **P3** | Landing Page & Frontend Base | NOT STARTED | Vascha & Quinsha |
| **P4** | Category & SubCategory CRUD | NOT STARTED | Quinsha & Vascha |
| **P5** | Course CRUD (Instructor) | NOT STARTED | Albariqi |
| **P6** | Course Section & Lecture | NOT STARTED | Albariqi |
| **P7** | Cart & Wishlist | NOT STARTED | Ray |
| **P8** | Payment (Midtrans) & Order | NOT STARTED | Ray |
| **P9** | Student Dashboard & Enrolled | NOT STARTED | Vascha & Quinsha |
| **P10** | Course Player (Watch Page) | NOT STARTED | Albariqi + Vascha |
| **P11** | Review & Rating System | NOT STARTED | Quinsha & Vascha |
| **P12** | Admin Panel (Full) | NOT STARTED | Quinsha & Vascha |
| **P13** | Coupon System | NOT STARTED | Ray |
| **P14** | Site Settings | NOT STARTED | Quinsha & Vascha |
| **P15** | Polish, Testing, Deploy | NOT STARTED | ALL |

---

## 4. CODING STANDARDS

### 4.1 Aturan Bahasa

- **Nama fungsi/method:** English only (e.g., `getCoursesByCategory()`)
- **Nama variabel:** English only (e.g., `$courseCount`, `$student`)
- **Database column:** English snake_case (mengikuti schema)
- **Comment & docblock:** English
- **Text UI (label, heading, tombol):** **Bahasa Indonesia** (UX lokal — pakai "Siswa", "Instruktur", "Keranjang", dll)
- **Route URL:** English, kebab-case/snake-case
- **Route name:** English, dot notation

### 4.2 Konvensi Laravel 12

✅ **BENAR — Eloquent relationships + eager loading:**
```php
$courses = Course::with(['category', 'instructor', 'sections.lectures'])
    ->active()
    ->paginate(12);
```

❌ **SALAH — Raw query + N+1:**
```php
$courses = DB::table('courses')->get();
foreach ($courses as $course) {
    $course->category = DB::table('categories')->find($course->category_id);
}
```

### 4.3 Konvensi Controller

```
app/Http/Controllers/
├── Frontend/
│   ├── HomeController.php
│   ├── CourseDetailController.php
│   ├── CourseCatalogController.php
│   ├── CartController.php
│   ├── CheckoutController.php
│   ├── WishlistController.php
│   ├── CouponController.php          ← apply coupon endpoint
│   └── SearchController.php
├── Backend/
│   ├── Admin/                         ← Admin panel controllers
│   ├── Instructor/                    ← Instructor panel controllers
│   └── Student/                       ← Student panel controllers (UI term)
├── Auth/                              ← Breeze auto-generated
└── SocialController.php               ← Google OAuth
```

⚠️ Folder name pakai **"Student"** meski DB value `user`. Lihat `ADR-007`.

### 4.4 Konvensi View / Blade

```
resources/views/
├── layouts/          ← app, admin, instructor, student
├── components/       ← Reusable Blade components
├── frontend/         ← Public-facing pages
├── backend/
│   ├── admin/
│   ├── instructor/
│   └── student/      ← UI term, sesuai controller folder
└── auth/             ← Breeze
```

### 4.5 Security Rules

1. **JANGAN** expose internal ID di URL publik — gunakan slug
2. **SELALU** apply middleware `auth` untuk route yang butuh login
3. **SELALU** validasi input — gunakan Form Requests
4. **JANGAN** hardcode credentials — selalu baca dari `.env`
5. **SELALU** gunakan CSRF token di form (pengecualian: `/payment/callback` webhook Midtrans)
6. **JANGAN** trust user input — sanitize & validate
7. **Midtrans keys** di `.env`, **bukan** di kode atau DB
8. Lihat `01_guides/SECURITY_GUIDELINES.md` untuk detail lengkap

### 4.6 Media Upload Rule (PENTING)

**SEMUA** upload media (gambar, thumbnail, foto profil) masuk ke **Cloudinary**:

```php
// ✅ BENAR
$result = $request->file('thumbnail')->storeOnCloudinary('belajarkuy/courses');
$url = $result->getSecurePath();

// ❌ SALAH — Jangan upload ke public/uploads/
$path = $request->file('thumbnail')->store('uploads/courses', 'public');
```

**Pengecualian:** `public/images/` hanya untuk static assets (logo brand, icon default, dll) yang di-bundle bersama repo.

---

## 5. BUSINESS LOGIC KRITIS

### 5.1 Purchase Flow

```
[Browse Catalog]
      ↓
[Add to Cart]   → (check: not already enrolled)
      ↓
[Checkout]
      ↓
[Midtrans Snap Payment]
      ↓
[Midtrans Webhook Callback]
      ↓
[Transaction: Create Order + Enrollment + Increment Coupon Usage + Clear Cart]
      ↓
[Student Access Course via Enrollment]
```

### 5.2 Midtrans Payment Flow (Sandbox Only — ADR-004)

```php
// config/midtrans.php - is_production HARDCODED false
\Midtrans\Config::$serverKey    = config('midtrans.server_key');
\Midtrans\Config::$isProduction = false;  // ADR-004 — JANGAN ubah
\Midtrans\Config::$isSanitized  = true;
\Midtrans\Config::$is3ds        = true;

$params = [
    'transaction_details' => [
        'order_id'     => 'BKUY-' . time() . '-' . auth()->id(),
        'gross_amount' => (int) $totalAmount, // INTEGER, bukan float
    ],
    'customer_details' => [
        'first_name' => auth()->user()->name,
        'email'      => auth()->user()->email,
    ],
    'item_details' => $items,
];
$snapToken = \Midtrans\Snap::getSnapToken($params);
```

Frontend Snap JS **selalu sandbox URL:**
```html
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
```

Lihat `03_features/F06_PAYMENT_MIDTRANS.md` untuk detail lengkap.

### 5.3 Enrollment Check (PENTING — Schema v2)

**Gunakan tabel `enrollments`**, bukan query Order lama:

```php
// ✅ BENAR — fast, single table lookup
$isEnrolled = Enrollment::where('user_id', auth()->id())
    ->where('course_id', $courseId)
    ->exists();

// ❌ SALAH — Schema v1 pattern, lebih lambat
$isEnrolled = Order::where('user_id', auth()->id())
    ->where('course_id', $courseId)
    ->where('status', 'completed')
    ->exists();
```

`enrollments` table sengaja dibuat untuk menghindari join. Gunakan.

### 5.4 Revenue — Gross Only (ADR-005)

**Payout / revenue split OUT OF SCOPE** untuk MVP akademik (ADR-005).

Yang ada:
- Instructor melihat **gross revenue** mereka (sum of `orders.final_price` with status `completed`)
- Admin melihat **platform gross revenue** (sum of `payments.total_amount` with status `settlement`/`capture`)

Yang **TIDAK** ada:
- Tabel `payouts`
- Perhitungan platform fee / instructor share
- UI approve payout
- Transfer bank

Jangan tulis code yang asumsikan 70/30 split.

### 5.5 Course Status Flow

```
draft ────────→ pending_review ──admin approve──→ active
                       ↓                              ↓
                   admin reject                   admin toggle
                       ↓                              ↓
                   inactive                       inactive
```

- **draft** — instructor baru mulai, belum submit
- **pending_review** — instructor submit, menunggu admin
- **active** — public, muncul di catalog
- **inactive** — ditolak atau di-unpublish

### 5.6 Instructor Lifecycle (ADR-006)

Instructor **langsung aktif** saat register. **Tidak ada** approval flow untuk instructor.

Admin moderasi di level **course** (approve/reject course), bukan level user.

---

## 6. STRICT CONSTRAINTS (NON-NEGOTIABLE)

> ⛔ Aturan ini **TIDAK BOLEH** dilanggar oleh AI agent manapun.

1. **JANGAN** modifikasi migrations yang sudah dibuat tanpa izin PM
2. **JANGAN** install Composer/NPM package baru tanpa izin PM
3. **JANGAN** hapus atau rename file existing tanpa izin
4. **JANGAN** mengubah database column names dari Schema v2
5. **JANGAN** membuat model yang sudah ada — modifikasi saja
6. **SELALU** jalankan `php artisan migrate:status` sebelum propose perubahan schema
7. **JANGAN** hardcode API keys atau credentials — pakai `.env`
8. **SELALU** bekerja di branch feature yang sesuai (lihat `GIT_WORKFLOW.md`)
9. **SELALU** update `PROGRESS_TRACKER.md` + bikin daily report setelah selesai task
10. **JANGAN** menggunakan Stripe — kita menggunakan **Midtrans Snap ONLY** (ADR-001)
11. **JANGAN** set `Config::$isProduction = true` — SELALU sandbox (ADR-004)
12. **JANGAN** upload ke `public/uploads/` — pakai Cloudinary (lihat section 4.6)
13. **JANGAN** cek enrollment via `orders` — pakai `enrollments` table (section 5.3)
14. **JANGAN** asumsikan payout/revenue split ada — OUT OF SCOPE (ADR-005)
15. **JANGAN** pakai field deprecated (`coupons.name`, `coupons.validity`, dll) — lihat `GLOSSARY.md` section "DEPRECATED Terms"

---

## 7. FORMAT KOMUNIKASI AI AGENT

Saat melaporkan progress, gunakan format ini:

```
TASK: [Nama task yang dikerjakan]
ACTION: [Apa yang dilakukan — file yang dibuat/dimodifikasi]
RESULT: [Hasil — berhasil/gagal + detail]
BLOCKERS: [Hal yang menghambat, jika ada]
NEXT: [Task selanjutnya]
```

### Contoh:

```
TASK: Implementasi CartController dengan add/remove/fetch
ACTION: Dibuat app/Http/Controllers/Frontend/CartController.php
        Dibuat resources/views/frontend/cart.blade.php
        Ditambahkan 5 route di routes/web.php
RESULT: Cart berfungsi — add, remove, fetch, coupon apply. Tested manual OK.
BLOCKERS: Tidak ada.
NEXT: [P8] Integrasi Midtrans Snap untuk checkout.
```

---

## 8. CARA UPDATE PROGRESS & LAPORAN

> ⛔ **MANDATORY** — Semua AI agent WAJIB menjalankan semua langkah di bawah ini setelah selesai mengerjakan task, SEBELUM melakukan `git push`. Tidak ada pengecualian.

### Step 1: Update `04_plans/TASK_DISTRIBUTION.md`

Ganti status setiap task yang diselesaikan:

```
☐ Task belum dikerjakan   →   ✅ Task selesai (tambah keterangan singkat)
🔄 Task sedang dikerjakan  →   ✅ Task selesai
```

Contoh:
```
✅ Install Laravel Breeze (sudah terinstall + scaffolded — verified Session 7)
✅ Implement RoleMiddleware (alias 'role' di bootstrap/app.php)
☐ Create separate login pages (admin/login, instructor/login) — NEXT
```

### Step 2: Update `06_reports/PROGRESS_TRACKER.md`

1. **Timestamp** — baris 7: `> **Update terakhir:** DD Mei YYYY — HH:MM WIB oleh [NAMA Agent]`
2. **Summary table** — update persentase modul dan OVERALL
3. **Phase checklist** — `- [ ]` → `- [x]` untuk setiap item selesai
4. **Session Log** — tambahkan entry baru di bagian bawah `## 📝 Session Logs`:

```markdown
### Session N — DD Mei YYYY (Nama / Inisial)
- Created: [file baru]
- Updated: [file yang dimodifikasi]
- Branch: `feature/nama-branch`
- Status: [ringkasan singkat — apa yang selesai]
- Next: [task berikutnya yang harus dikerjakan]
- Report: `06_reports/REPORT_YYYY-MM-DD_TOPIK.md`
```

### Step 3: Buat Daily Report

Wajib dibuat jika menyelesaikan **1 feature atau lebih** dalam satu sesi.

**Format nama file:**
```
REPORT_YYYY-MM-DD_TOPIC.md
```

Contoh:
```
REPORT_2026-05-14_SEEDERS_FACTORIES.md
REPORT_2026-05-17_AUTH_SYSTEM.md
```

TOPIC dalam `SCREAMING_SNAKE_CASE`. Lokasi: `BelajarKUY_docs/06_reports/`.

**Template minimal:** lihat file report yang sudah ada di `06_reports/` sebagai referensi format.

### Step 4: Commit dengan Conventional Commits

Format commit:
```
feat(scope): deskripsi singkat implementasi

docs: update progress tracker and task distribution [session N]
```

Contoh nyata:
```
feat(auth): implement role middleware, dashboard controllers, register role UI
docs: update TASK_DISTRIBUTION + PROGRESS_TRACKER session 7 albariqi
```

### Step 5: Push ke Feature Branch

```
git checkout -b feature/nama-branch
git add .
git commit -m "feat(scope): ..."
git push origin feature/nama-branch
```

Buat PR ke `develop` — **jangan langsung push ke `main`** (lihat `GIT_WORKFLOW.md`).

---

## 9. PETA FILE PROYEK (Actual)

```
BelajarKUY/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Frontend/    
│   │   │   ├── Backend/
│   │   │   │   ├── Admin/
│   │   │   │   ├── Instructor/
│   │   │   │   └── Student/          ← UI term (ADR-007)
│   │   │   └── Auth/                 ← Breeze auto-generated
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/                 ← Form Request classes
│   ├── Models/                       ← 19 Eloquent models (ADA)
│   ├── Services/
│   │   ├── MidtransService.php       
│   │   └── CloudinaryService.php    
│   ├── Events/                       ← Reverb broadcast events
│   └── Mail/                         ← Mail classes
│
├── database/
│   ├── migrations/                   ← 22 migration files (ADA, v2)
│   ├── factories/                    ← 19 factories (ADA)
│   └── seeders/                      ← 5 seeders (ADA)
│
├── resources/views/
│   ├── layouts/
│   ├── components/
│   ├── frontend/
│   ├── backend/
│   │   ├── admin/
│   │   ├── instructor/
│   │   └── student/
│   └── auth/
│
├── routes/
│   ├── web.php
│   └── auth.php
│
├── config/
│   └── midtrans.php
│
├── public/
│   └── build/                        ← Vite compiled assets
│   # ⚠️ TIDAK ADA public/uploads/ — media ke Cloudinary (section 4.6)
│
├── BelajarKUY_docs/                  ← Dokumentasi
│
├── .env / .env.example
├── composer.json
└── package.json
```

---

## 10. GLOSSARY REFERENCE (Singkat)

Lihat `01_guides/GLOSSARY.md` untuk lengkap. Yang paling sering ambigu:

| Term | Meaning | Source of Truth |
|------|---------|-----------------|
| Paid | Midtrans confirmed | `payments.status IN (settlement, capture)` |
| Purchased | Order completed | `orders.status = completed` |
| Enrolled | Access granted | `enrollments` row exists |

**Rule:** Untuk "boleh akses course?" → cek `enrollments`. ALWAYS.

---

*End of AGENT_GUIDELINES.md v2 — Single source of truth untuk semua AI agent di project BelajarKUY.*

*Lihat `GLOSSARY.md`, `DATABASE_SCHEMA.md`, dan `ADR/` untuk detail lebih lanjut.*
