# 📝 Report — Auth System (P2): Middleware, Breeze & Dashboard

> **Tanggal:** 17 Mei 2026
> **Sesi:** Session 7
> **PIC:** Albariqi Tarigan
> **Branch:** `feature/auth-system`
> **Durasi:** ~1 jam

---

## 🎯 Tujuan Sesi

Mengerjakan task Albariqi Tarigan dalam scope **Phase P2 — Auth System**:

1. Verifikasi Database Seeders (sebelumnya diklaim DONE, perlu konfirmasi)
2. Verifikasi & konfigurasi Laravel Breeze
3. Implementasi RoleMiddleware (register alias, protect routes)
4. Role selection di form register (Student / Instructor)
5. Post-login & post-register redirect per role
6. Buat Dashboard Controllers & Views untuk setiap role

---

## ✅ Yang Dikerjakan

### 1. Database Seeders — Verified ✅

**Status:** SUDAH DONE dari session sebelumnya (Session 4, 14 Mei 2026).

| Seeder | File | Status |
|--------|------|--------|
| `UserSeeder` | `database/seeders/UserSeeder.php` | ✅ Ada |
| `CategorySeeder` | `database/seeders/CategorySeeder.php` | ✅ Ada |
| `CourseSeeder` | `database/seeders/CourseSeeder.php` | ✅ Ada |
| `TransactionSeeder` | `database/seeders/TransactionSeeder.php` | ✅ Ada |
| `CmsSeeder` | `database/seeders/CmsSeeder.php` | ✅ Ada |
| `DatabaseSeeder` | `database/seeders/DatabaseSeeder.php` | ✅ Ada (orchestrator) |

Demo accounts yang sudah diseed:
```
Admin    : admin@belajarkuy.test    / password
Instruktur: ray@belajarkuy.test     / password
Instruktur: yosua@belajarkuy.test   / password
Siswa    : student@belajarkuy.test  / password
```

Progress tracker line 58 yang duplicate `- [ ] Create database seeders` sudah dibersihkan.

---

### 2. Install & Configure Breeze — Verified ✅

**Status:** Laravel Breeze sudah terinstall dari session sebelumnya.

**Verifikasi:**
- `composer.json` → `"laravel/breeze": "^2.4"` di `require-dev` ✅
- `app/Http/Controllers/Auth/` → 9 controllers Breeze (Register, Login, Password, Verify, dll) ✅
- `resources/views/auth/` → 6 views (login, register, forgot-password, dll) ✅
- `routes/auth.php` → semua auth routes tersedia ✅
- `resources/views/layouts/` → `app.blade.php`, `guest.blade.php`, `navigation.blade.php` ✅

Tidak perlu jalankan ulang `php artisan breeze:install` — sudah scaffolded dengan benar.

---

### 3. RoleMiddleware — Implemented ✅

**File dimodifikasi:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    // Register RoleMiddleware dengan alias 'role'
    // Usage: middleware('role:admin'), middleware('role:instructor'), middleware('role:user')
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

**RoleMiddleware logic** (`app/Http/Middleware/RoleMiddleware.php`) — sudah ada dari sebelumnya:
- Cek `$request->user()` → redirect ke `login` jika belum auth
- Cek `$request->user()->role` in `$roles` → lanjut jika match
- Redirect ke dashboard role masing-masing jika akses denied

**Usage di routes:**
```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(...)
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(...)
Route::middleware(['auth', 'role:user'])->prefix('student')->name('student.')->group(...)
```

---

### 4. RegisteredUserController — Updated ✅

**File dimodifikasi:** `app/Http/Controllers/Auth/RegisteredUserController.php`

**Perubahan:**
- Tambah validasi field `role` → `in:user,instructor` (admin tidak bisa self-register)
- Default role `user` (Student) jika tidak dipilih
- Redirect post-register sesuai role via `match()`

```php
$role = $request->input('role', 'user'); // Default: Student

$user = User::create([
    'name'     => $request->name,
    'email'    => $request->email,
    'password' => Hash::make($request->password),
    'role'     => $role,
]);

// Redirect ke dashboard sesuai role (F01)
return match ($user->role) {
    'instructor' => redirect()->intended(route('instructor.dashboard', absolute: false)),
    default      => redirect()->intended(route('student.dashboard', absolute: false)),
};
```

> ⚠️ `TODO`: WelcomeMail (F14) sudah ditandai dengan komentar, akan diimplementasi di fase email.

---

### 5. Register Form — Role Selection UI ✅

**File dimodifikasi:** `resources/views/auth/register.blade.php`

Tambah radio card pilihan role di bagian atas form:

```
┌─────────────────────────────┐
│  Daftar sebagai             │
│  ┌──────────┐ ┌──────────┐  │
│  │    🎓    │ │    📖    │  │
│  │  Siswa   │ │Instruktur│  │
│  │(default) │ │          │  │
│  └──────────┘ └──────────┘  │
└─────────────────────────────┘
```

- Default: Siswa (`role=user`)
- TailwindCSS `has-[:checked]` untuk highlight kartu yang dipilih
- Label UI Indonesia: "Siswa", "Instruktur"

---

### 6. Dashboard Controllers — Created ✅

| File | Role | Stats |
|------|------|-------|
| `Backend/Admin/DashboardController.php` | admin | total students, instructors, courses, orders |
| `Backend/Instructor/DashboardController.php` | instructor | courses count, enrollments, gross revenue |
| `Backend/Student/DashboardController.php` | user/student | enrolled count, completed lectures |

> ⚠️ Student dashboard menggunakan `enrollments` table (bukan `orders`) sesuai **Schema v2** & `AGENT_GUIDELINES.md` section 5.3.

> ⚠️ Instructor revenue menggunakan **gross only** — tidak ada payout split per **ADR-005**.

---

### 7. Dashboard Views — Created ✅

| File | Keterangan |
|------|------------|
| `resources/views/backend/admin/dashboard.blade.php` | Stats grid (4 card), menu placeholder |
| `resources/views/backend/instructor/dashboard.blade.php` | Stats grid (3 card) + tabel kursus dengan status badge |
| `resources/views/backend/student/dashboard.blade.php` | Stats grid (2 card) + enrolled courses list |

---

### 8. routes/web.php — Updated ✅

Routing sekarang terstruktur dengan jelas per role:

```
GET /                    → welcome.blade.php            (public)
GET /auth/google         → GoogleController@redirect    (public)
GET /auth/google-callback→ GoogleController@callback    (public)
GET /dashboard           → smart redirect per role      (auth)
GET /profile             → ProfileController@edit       (auth)
GET /student/dashboard   → StudentDashboardController   (auth + role:user)
GET /instructor/dashboard→ InstructorDashboardController(auth + role:instructor)
GET /admin/dashboard     → AdminDashboardController     (auth + role:admin)
+ semua routes dari routes/auth.php
```

---

## 📁 File Summary

### Dibuat Baru
```
app/Http/Controllers/Backend/Admin/DashboardController.php
app/Http/Controllers/Backend/Instructor/DashboardController.php
app/Http/Controllers/Backend/Student/DashboardController.php
resources/views/backend/admin/dashboard.blade.php
resources/views/backend/instructor/dashboard.blade.php
resources/views/backend/student/dashboard.blade.php
```

### Dimodifikasi
```
bootstrap/app.php                                         ← RoleMiddleware alias
app/Http/Controllers/Auth/RegisteredUserController.php   ← role field + redirect
resources/views/auth/register.blade.php                  ← role selection UI
routes/web.php                                            ← role-protected routes
BelajarKUY_docs/06_reports/PROGRESS_TRACKER.md           ← session log + checkbox
```

---

## 🐛 Issues & Notes

| # | Issue | Status |
|---|-------|--------|
| 1 | `courses.index` route belum ada (dikomen di student dashboard) | 🔜 Phase P2/P3 |
| 2 | WelcomeMail (F14) belum diimplementasi | 🔜 Phase email |
| 3 | Separate login pages admin/instructor belum dibuat | 🔜 Next Albariqi |
| 4 | `dashboard` route tidak pakai `'verified'` middleware | ✅ Sengaja — email verify optional (F01) |

---

## 🔜 Next Steps (Albariqi Tarigan)

1. **Separate login pages** — `/admin/login`, `/instructor/login` dengan view terpisah
2. **Course CRUD** (Phase P5) — `StoreCourseRequest`, `CourseController` (Instructor)
3. **Course Section & Lecture CRUD** (Phase P6)
4. **Instructor Profile** — edit bio, website, photo (Cloudinary)

---

## 📊 Progress Update

| Modul | Before | After |
|-------|--------|-------|
| Auth System | 20% | **70%** |
| OVERALL | 15% | **25%** |

---

*Report dibuat oleh Albariqi Tarigan — 17 Mei 2026*
