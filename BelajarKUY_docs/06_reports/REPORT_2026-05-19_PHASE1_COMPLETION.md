# 📝 Report — Phase 1 Completion: Routing Fix, Universal Dashboard & Google Auth

> **Tanggal:** 19 Mei 2026
> **Sesi:** Session 8
> **PIC:** Albarqi Tarigan
> **Branch:** `feature/role_selection`
> **Durasi:** ~45 menit

---

## 🎯 Tujuan Sesi

Menyelesaikan sisa item **Phase 1 — Foundation** yang belum selesai setelah `git pull origin main` dari kontribusi tim lain, serta memperbaiki konflik yang ditimbulkan oleh pull tersebut:

1. Fix routing — admin routes tidak diproteksi `role` middleware
2. Tambah route `instructor.dashboard` dan `student.dashboard` yang hilang
3. Buat halaman `/admin/login` terpisah
4. Redesign login page dengan Google OAuth sebagai primary action
5. Rewrite `layouts/app.blade.php` menjadi layout bersih yang benar
6. Buat `HomeController` untuk landing page dengan semua variabel yang dibutuhkan
7. Fix semua broken routes yang ditinggalkan views dari git pull (`user.*` → `student.*`)
8. Daftarkan route placeholder Phase 2 & 3 agar views tidak `RouteNotFoundException`

---

## ✅ Yang Dikerjakan

### 1. Audit Pasca `git pull` — Temuan Kritis

Setelah `git pull origin main`, ditemukan beberapa masalah kritis:

| # | Masalah | Dampak |
|---|---------|--------|
| 1 | Admin routes hanya pakai `['auth', 'verified']` — tanpa `role:admin` | Semua user bisa akses `/admin/*` |
| 2 | Route `instructor.dashboard` dan `student.dashboard` tidak ada | Error 500 saat login sebagai instructor/student |
| 3 | `layouts/app.blade.php` berisi konten homepage hardcoded + `{{ $slot }}` | Student dashboard tampil dengan hero section di atas |
| 4 | Semua sidebar/views student masih pakai `user.*` route prefix | `RouteNotFoundException` saat akses student panel |
| 5 | `PROGRESS_TRACKER.md` di-reset oleh tim lain (progress dihapus) | Dokumentasi tidak akurat |

---

### 2. `routes/web.php` — Rewrite Total ✅

**File dimodifikasi:** `routes/web.php`

**Perubahan utama:**

```php
// BEFORE: Admin tanpa proteksi role
Route::middleware(['auth', 'verified'])->prefix('admin')...

// AFTER: Admin dilindungi role:admin
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')...
```

**Ditambahkan:**

```
GET  /dashboard              → Universal Dashboard (controller per role, URL tetap sama)
GET  /admin/login            → Halaman login khusus admin (guest middleware)
GET  /instructor/dashboard   → InstructorDashboardController (role:instructor)
GET  /student/dashboard      → StudentDashboardController (role:user)
GET  /student/my-courses     → StudentDashboardController@myCourses
GET  /student/wishlist       → StudentDashboardController@wishlist
DELETE /student/wishlist/{id}→ StudentDashboardController@wishlistRemove
GET  /student/profile        → StudentDashboardController@profile
POST /student/profile        → StudentDashboardController@profileUpdate
GET  /student/setting        → StudentDashboardController@setting
POST /student/setting        → StudentDashboardController@settingUpdate
```

**Route placeholder Phase 2 & 3** (agar views tidak throw error):

```
GET  /home                   → HomeController@index
GET  /courses/{slug}         → course.detail (placeholder)
GET  /cart                   → cart.index (placeholder)
POST /cart/{course}          → cart.add (placeholder)
GET  /checkout               → checkout (placeholder)
POST /checkout/process       → checkout.process (placeholder)
GET  /payment/success        → payment.success (placeholder)
GET  /payment/failed         → payment.failed (placeholder)
POST /courses/{id}/reviews   → course.review.store (placeholder)
POST /wishlist/{course}      → wishlist.add (placeholder)
PATCH /admin/reviews/{id}/approve → admin.reviews.approve
PATCH /admin/reviews/{id}/reject  → admin.reviews.reject
```

---

### 3. Universal Dashboard `/dashboard` ✅

**Konsep:** Satu URL `/dashboard` untuk semua role — tidak redirect, tapi render konten yang sesuai.

```php
Route::get('/dashboard', function () {
    $user = Auth::user();
    return match($user->role) {
        'admin'      => app(AdminDashboardController::class)->index(),
        'instructor' => app(InstructorDashboardController::class)->index(),
        default      => app(StudentDashboardController::class)->index(),
    };
})->middleware(['auth', 'verified'])->name('dashboard');
```

**Keuntungan:**
- URL konsisten: `/dashboard` untuk semua role
- Semua controller redirect (`AuthenticatedSessionController`, `RegisteredUserController`, `GoogleController`) cukup ke `route('dashboard')`
- Role-specific URL (`/student/dashboard`, `/admin/dashboard`, dll) tetap ada untuk navigasi internal

---

### 4. Halaman Admin Login (`/admin/login`) ✅

**File dibuat:** `resources/views/auth/admin-login.blade.php`

- Dark theme (slate-800/900) berbeda dari login biasa
- Badge "Akses Terbatas — Admin Only" dengan dot merah animasi
- Tidak ada tombol Google OAuth (admin tidak login via Google)
- Auto-redirect ke `admin.dashboard` jika sudah login sebagai admin
- Link kembali ke halaman login biasa

**Route:**
```php
Route::get('/admin/login', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.admin-login');
})->name('admin.login.page')->middleware('guest');
```

---

### 5. Login Page Redesign — Google OAuth Prominent ✅

**File dimodifikasi:** `resources/views/auth/login.blade.php`

**Perubahan:**
- Google OAuth button **dipindah ke atas** sebagai pilihan utama
- Divider "atau masuk dengan email" memisahkan Google dan form manual
- Form email/password tetap tersedia di bawah
- Link ke halaman register di bagian bawah
- Teks diubah ke Bahasa Indonesia

**Alur Google OAuth:**
```
User klik "Masuk dengan Google"
  → /auth/google → Google consent screen
  → /auth/google-callback
  → User baru: role otomatis = 'user' (Student)
  → User lama: role sesuai yang tersimpan di DB
  → Redirect ke /dashboard (universal)
```

---

### 6. `layouts/app.blade.php` — Rewrite Bersih ✅

**File dimodifikasi:** `resources/views/layouts/app.blade.php`

**Masalah sebelumnya:**
```
[Navbar] + [Hero Section] + [Popular Courses] + [Mentor Section] + {{ $slot }} + [Footer]
               ↑ Ini hardcoded di layout! Tampil di SEMUA halaman yang extends layouts.app
```

**Setelah rewrite:**
```
[Navbar auth-aware] + @yield('content') + [Footer bersih]
```

**Navbar auth-aware:**

| State | Yang Tampil |
|-------|------------|
| Guest | Tombol "Masuk", "Daftar", ikon Google kecil |
| Admin | Badge merah "Admin", link ke dashboard, tombol Keluar |
| Instructor | Badge kuning "Instruktur", link ke dashboard, tombol Keluar |
| Student | Badge biru "Siswa", link ke dashboard, tombol Keluar |

---

### 7. `HomeController` — Buat Baru ✅

**File dibuat:** `app/Http/Controllers/Frontend/HomeController.php`

Menyediakan semua variabel yang dibutuhkan `frontend/home.blade.php`:

| Variabel | Query |
|----------|-------|
| `$sliders` | `Slider::where('status', true)->orderBy('sort_order')` |
| `$infoBoxes` | `InfoBox::orderBy('id')` |
| `$categories` | `Category::withCount(courses)->orderByDesc(courses_count)->take(8)` |
| `$featuredCourses` | `Course::where('featured', true)->take(8)` |
| `$bestsellerCourses` | `Course::where('bestseller', true)->orderByDesc(enrollments_count)->take(8)` |
| `$filteredCourses` | Hasil pencarian/filter (jika ada query param) |
| `$isSearchingOrFiltering` | `true` jika ada `?search=` atau `?category=` |

**Kolom DB yang dipakai sesuai migrasi aktual:**
- `sliders.sort_order` (bukan `serial`)
- `courses.featured` dan `courses.bestseller` (bukan `is_featured`)

---

### 8. Fix Broken Route References (`user.*` → `student.*`) ✅

Views dari git pull masih memakai route prefix `user.*` yang tidak ada di `web.php` kita.

**File yang difix:**

| File | Route Lama | Route Baru |
|------|-----------|-----------|
| `layouts/sidebar.blade.php` | `user.dashboard`, `user.my-courses`, `user.wishlist`, `user.profile`, `user.setting` | `student.*` |
| `components/navbar.blade.php` | `user.wishlist` | `student.wishlist` |
| `student/wishlist.blade.php` | `user.wishlist.remove` | `student.wishlist.remove` |
| `student/setting.blade.php` | `user.setting.update` | `student.setting.update` |
| `student/profile.blade.php` | `user.profile.update` | `student.profile.update` |
| `student/dashboard.blade.php` | `user.my-courses`, `user.profile` | `student.my-courses`, `student.profile` |

---

## 📁 File Summary

### Dibuat Baru
```
app/Http/Controllers/Frontend/HomeController.php
resources/views/auth/admin-login.blade.php
```

### Dimodifikasi
```
routes/web.php                                                ← rewrite total (routing fix + placeholders)
resources/views/layouts/app.blade.php                        ← rewrite bersih (bukan layout homepage)
resources/views/auth/login.blade.php                         ← Google OAuth prominent
app/Http/Controllers/Auth/AuthenticatedSessionController.php ← redirect ke /dashboard universal
app/Http/Controllers/Auth/RegisteredUserController.php       ← redirect ke /dashboard universal
app/Http/Controllers/Auth/GoogleController.php               ← redirect ke /dashboard universal
resources/views/backend/student/layouts/sidebar.blade.php    ← route user.* → student.*
resources/views/components/navbar.blade.php                  ← route user.wishlist → student.wishlist
resources/views/backend/student/wishlist.blade.php           ← route fix
resources/views/backend/student/setting.blade.php            ← route fix
resources/views/backend/student/profile.blade.php            ← route fix
resources/views/backend/student/dashboard.blade.php          ← route fix
BelajarKUY_docs/06_reports/PROGRESS_TRACKER.md              ← session log + checkbox restored
```

---

## 🐛 Issues & Notes

| # | Issue | Status |
|---|-------|--------|
| 1 | `layouts/app.blade.php` dari pull berisi konten homepage hardcoded | ✅ Fixed — rewrite bersih |
| 2 | Admin routes tidak diproteksi `role:admin` | ✅ Fixed |
| 3 | `instructor.dashboard` dan `student.dashboard` tidak ada | ✅ Fixed |
| 4 | Duplikasi migrations (`2026_05_13_*` dan `2026_05_16_*`) | ⚠️ Belum dihandle — perlu `migrate:fresh` jika setup fresh |
| 5 | Route placeholder Phase 2 & 3 masih closure — belum ada controller | 🔜 Akan digantikan saat Phase 2 dikerjakan |
| 6 | WelcomeMail (F14) belum diimplementasi | 🔜 Phase email |
| 7 | Instructor dashboard masih pakai `x-app-layout` Breeze default | 🔜 Phase 4 — perlu redesign |

---

## 🔑 Akun Demo

Semua akun menggunakan password: **`password`**

| Role | Email |
|------|-------|
| 🔴 Admin | `admin@belajarkuy.test` |
| 🟡 Instructor | `ray@belajarkuy.test` |
| 🟡 Instructor | `yosua@belajarkuy.test` |
| 🟢 Student | `student@belajarkuy.test` |

> Login via Google OAuth → otomatis jadi role **Student** (`user`)

---

## 🔜 Next Steps

1. **Phase 2 kickoff** — Landing page (`/home` dengan HomeController sudah siap ✅), navbar component, course card
2. **Course CRUD Instructor** — `InstructorCourseController`, routes di bawah `/instructor/courses`
3. **Fix duplikasi migration** — koordinasi dengan tim untuk `migrate:fresh --seed`
4. **Instructor login page** — analog dengan `/admin/login` yang sudah dibuat

---

## 📊 Progress Update

| Modul | Before (Session 7) | After (Session 8) |
|-------|-------------------|-------------------|
| Auth System | 70% | **100%** ✅ |
| OVERALL | 25% | **30%** |

**Phase 1: Foundation — SELESAI 100%** ✅

---

*Report dibuat oleh Albariqi Tarigan — 19 Mei 2026*
