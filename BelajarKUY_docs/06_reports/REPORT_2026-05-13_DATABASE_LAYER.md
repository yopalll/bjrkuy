# 📝 Daily Report — 13 Mei 2026

> **Author:** Kiro (AI Agent)  
> **Session:** Database Layer Foundation  
> **Branch:** `feature/database-migrations`  
> **Commit:** `b9da447` + follow-up

---

## 🎯 Objective

Membangun database layer BelajarKUY secara lengkap — migrations, ERD, dan Eloquent models — sesuai `DATABASE_SCHEMA.md v2` dan `PROMPT_MIGRATIONS.md` / `PROMPT_MODELS.md`.

---

## ✅ Yang Dikerjakan

### 1. Database Migrations (19 files)

Membaca `DATABASE_SCHEMA.md v2` lalu generate semua migration files sesuai urutan dependensi FK:

| # | Migration | Status |
|---|-----------|--------|
| 1 | `add_fields_to_users_table` | ✅ role, photo, phone, address, bio, website + index role |
| 2 | `create_categories_table` | ✅ + index status |
| 3 | `create_sub_categories_table` | ✅ FK category_id CASCADE |
| 4 | `create_sliders_table` | ✅ sort_order, button_text, button_url |
| 5 | `create_info_boxes_table` | ✅ |
| 6 | `create_partners_table` | ✅ |
| 7 | `create_site_infos_table` | ✅ key UNIQUE |
| 8 | `create_courses_table` | ✅ FK kategori/subkategori/instructor + composite index (status, featured/bestseller) |
| 9 | `create_course_goals_table` | ✅ |
| 10 | `create_course_sections_table` | ✅ sort_order |
| 11 | `create_course_lectures_table` | ✅ url 500 char, content text |
| 12 | `create_wishlists_table` | ✅ UNIQUE (user_id, course_id) |
| 13 | `create_carts_table` | ✅ SIMPLIFIED — tanpa price/instructor_id |
| 14 | `create_coupons_table` | ✅ ENHANCED — course_id, max_usage, used_count |
| 15 | `create_payments_table` | ✅ midtrans_order_id UNIQUE, json response |
| 16 | `create_orders_table` | ✅ ENHANCED — coupon_id + price snapshot |
| 17 | `create_enrollments_table` | ✨ NEW — quick enrollment lookup |
| 18 | `create_lecture_completions_table` | ✨ NEW — progress tracking |
| 19 | `create_reviews_table` | ✅ UNIQUE (user_id, course_id) |

### 2. Bug yang Ditemukan & Diperbaiki

**🐛 Duplicate Index Bug**  
Laravel `foreignId()->constrained()` otomatis membuat index pada kolom FK. Awalnya saya menambahkan `$table->index('fk_column')` secara manual → akan error "Duplicate key name" saat `php artisan migrate`.

**Fix:** hapus semua manual `index()` pada kolom FK di 12 tabel: `sub_categories`, `courses`, `course_goals`, `course_sections`, `course_lectures`, `wishlists`, `carts`, `coupons`, `payments`, `orders`, `enrollments`, `lecture_completions`, `reviews`. Composite index (misal `(user_id, status)`) tetap dipertahankan karena memang belum ada.

**🐛 dropIndex Syntax**  
`$table->dropIndex(['role'])` diganti ke `$table->dropIndex('users_role_index')` (nama index eksplisit) di migration `add_fields_to_users_table`.

### 3. ERD HTML Visual

File: `BelajarKUY_docs/07_extras/ERD_BelajarKUY.html` (~57KB, 780 baris).

Konten:
- 19 tabel sebagai card visual berwarna per kategori (hijau/ungu/amber/pink/teal)
- Badge `NEW` untuk `enrollments` dan `lecture_completions`
- Ikon 🔑 PK dan 🔗 FK, badge UNIQUE/NULL/DEFAULT/CASCADE/SET NULL/ENUM
- Foreign Key Summary table (28 relasi)
- Changelog v1.0 → v1.1
- Responsive grid layout + background gradient ungu

### 4. Eloquent Models (19 files)

Generate semua model di `app/Models/` sesuai `PROMPT_MODELS.md`:

| # | Model | Key Features |
|---|-------|--------------|
| 1 | `User` | fillable lengkap, scope students/instructors/admins, helper isAdmin/isInstructor/isStudent, 9 relationships |
| 2 | `Category` | scopeActive, hasMany subCategories & courses |
| 3 | `SubCategory` | belongsTo category, hasMany courses |
| 4 | `Course` | 10 relationships, scope active/featured/bestseller, accessor discountedPrice & averageRating |
| 5 | `CourseGoal` | belongsTo course |
| 6 | `CourseSection` | hasMany lectures (auto-ordered by sort_order) |
| 7 | `CourseLecture` | belongsTo section, hasMany completions |
| 8 | `Wishlist` | belongsTo user & course |
| 9 | `Cart` | SIMPLIFIED — tanpa price/instructor field, dokumentasi kenapa |
| 10 | `Coupon` | scopeActive (status + valid_until + usage limit check), belongsTo instructor/course |
| 11 | `Payment` | cast midtrans_response → array, scope completed/pending |
| 12 | `Order` | 6 relationships termasuk instructor + coupon, scope completed/pending |
| 13 | `Review` | scopeApproved, cast rating → int |
| 14 | `Slider` | scopeActive (active + ordered) |
| 15 | `InfoBox` | fillable standar |
| 16 | `Partner` | scopeActive |
| 17 | `SiteInfo` | static helper `SiteInfo::get('key', 'default')` untuk quick lookup |
| 18 | `Enrollment` | ✨ NEW — timestamps false, hanya enrolled_at |
| 19 | `LectureCompletion` | ✨ NEW — timestamps false, hanya completed_at |

### 5. Verifikasi

- ✅ `php -l` semua 19 model — lulus syntax check
- ✅ `php artisan about` — Laravel 13.8.0 boot bersih
- ✅ Runtime test — instantiate semua 19 model + invoke semua relationship methods → **19/19 PASS**

### 6. Git & GitHub

- Branch baru: `feature/database-migrations`
- Commit: `feat: add database migrations (Schema v2) + ERD HTML` (21 files, +1477 lines)
- Push ke `origin/feature/database-migrations`
- PR URL: <https://github.com/yopalll/BelajarKUY/pull/new/feature/database-migrations>

### 7. Progress Tracker Update

`BelajarKUY_docs/06_reports/PROGRESS_TRACKER.md`:
- Database (Migrations + Models): 0% → **100%** (migration + model level)
- Overall: 5% → **15%**
- Session log entry baru

---

## 🎯 Catatan Kesesuaian Business Model

### Audit migrations vs `PROMPT_MIGRATIONS.md`
✅ 19 tabel semuanya ada (users modified + 18 create tables)  
✅ Urutan migrasi benar sesuai FK dependencies  
✅ Semua FK dengan constraint CASCADE/SET NULL sesuai spec  
✅ UNIQUE constraints di semua user-action tables  
✅ Schema v2: tidak ada `midtrans_configs`, `carts` simplified, `coupons` enhanced, `orders` dengan coupon tracking  
✅ Field `price` pakai `decimal(12,2)` — mendukung Rupiah hingga miliaran  

### Konflik Prompt vs Schema
`PROMPT_MIGRATIONS.md` → `reviews.status` default `false`. `DATABASE_SCHEMA.md v2` → default `true`. 

**Keputusan:** Mengikuti `DATABASE_SCHEMA.md v2` karena schema adalah dokumen authoritative (ditandai "FINAL v2, jangan diubah tanpa persetujuan PM"). Prompt lebih lama dan belum mengikuti Schema v2. Review baru otomatis aktif konsisten dengan pattern data lain.

---

## 🔄 Business Flow yang Sudah Didukung

Dengan migrations + models ini, flow bisnis core BelajarKUY sudah bisa di-implement:

| Flow | Tabel Terlibat | Status |
|------|----------------|--------|
| Register sebagai student/instructor | `users` | 🟢 Ready |
| Browse katalog kursus | `categories`, `sub_categories`, `courses` | 🟢 Ready |
| Instructor buat kursus + sections + lectures | `courses`, `course_sections`, `course_lectures`, `course_goals` | 🟢 Ready |
| Student add ke wishlist/cart | `wishlists`, `carts` (tanpa price stale) | 🟢 Ready |
| Instructor buat kupon | `coupons` (dengan usage limit) | 🟢 Ready |
| Checkout + Midtrans Snap | `payments` (+ midtrans_response json) | 🟢 Ready |
| Payment callback → create orders | `orders` (dengan price snapshot + coupon) | 🟢 Ready |
| Auto-enrollment setelah settlement | `enrollments` (NEW — quick lookup) | 🟢 Ready |
| Track progress belajar per lecture | `lecture_completions` (NEW) | 🟢 Ready |
| Rating & review kursus | `reviews` (unique per user-course) | 🟢 Ready |
| CMS: slider, info box, partner, site config | `sliders`, `info_boxes`, `partners`, `site_infos` | 🟢 Ready |

---

## 📦 Artifacts

| File | Path |
|------|------|
| 19 migrations | `BelajarKUY/database/migrations/2026_05_13_000001...000019_*.php` |
| 19 models | `BelajarKUY/app/Models/*.php` |
| ERD HTML | `BelajarKUY_docs/07_extras/ERD_BelajarKUY.html` |
| Progress update | `BelajarKUY_docs/06_reports/PROGRESS_TRACKER.md` |

---

## ▶️ Next Steps

1. **Seeders** — bikin `DatabaseSeeder` + factory untuk tiap model (testing & demo data)
2. **Install Breeze + RoleMiddleware** — auth system dengan separate login per role
3. **Google OAuth** — third-party auth
4. **Admin CRUD** — categories, sub_categories, sliders, info_boxes, partners, site_infos
5. **Menjalankan migrations di local DB** — verifikasi semua FK constraints bekerja di MySQL

---

## ⚠️ Known Issues / Pending

- Belum ada database seeders → belum bisa test data flow end-to-end
- `php artisan migrate` belum di-run terhadap MySQL (test verifikasi manual pending)
- Factory untuk tiap model belum dibuat

---

*Generated: 13 Mei 2026 · Kiro (Claude Opus 4.7)*
