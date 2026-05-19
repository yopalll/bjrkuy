# 🤖 PROMPT: Build Auth System

> Copy-paste prompt ini ke AI agent untuk membangun sistem autentikasi.
> **PIC: Albariqi Tarigan**

---

## PROMPT

```
Kamu adalah senior Laravel 12 developer. Bangun sistem autentikasi lengkap untuk project BelajarKUY (Udemy clone Indonesia).

## CONTEXT:
- Laravel Breeze SUDAH terinstall (Blade stack)
- User model SUDAH punya kolom: role (enum: user, instructor, admin)
- RoleMiddleware SUDAH terdaftar dengan alias 'role'
- Google OAuth keys ada di .env

## PREREQUISITE: Baca file-file berikut terlebih dahulu:
- BelajarKUY_docs/01_guides/AGENT_GUIDELINES.md
- BelajarKUY_docs/02_architecture/DATABASE_SCHEMA.md (tabel users)
- BelajarKUY_docs/03_features/F01_AUTH_SYSTEM.md

## TASKS:

### 1. Modifikasi Register
- Tambahkan field "Daftar sebagai" dropdown (Student / Instructor) di form register
- Default role = 'user'
- Jika pilih Instructor, set role = 'instructor'
- File: resources/views/auth/register.blade.php
- File: app/Http/Controllers/Auth/RegisteredUserController.php

### 2. Post-Login Redirect
- Override method di AuthenticatedSessionController atau via RouteServiceProvider
- Redirect berdasarkan role:
  - admin → /admin/dashboard
  - instructor → /instructor/dashboard
  - user → /user/dashboard
- File: app/Http/Controllers/Auth/AuthenticatedSessionController.php

### 3. Separate Login Pages
- Admin login: /admin/login → resources/views/backend/admin/login.blade.php
- Instructor login: /instructor/login → resources/views/backend/instructor/login.blade.php
- Default login: /login → (Breeze default)
- Semua halaman login punya UI yang berbeda (branding BelajarKUY)

### 4. Google OAuth (Socialite)
- Route: GET /auth/google → redirect ke Google
- Route: GET /auth/google-callback → handle callback
- Logic:
  - Jika email sudah ada di DB → login
  - Jika email belum ada → create user (role=user) + login
  - Set nama dari Google profile
- File: app/Http/Controllers/SocialController.php

### 5. Logout per Role
- Admin logout → POST /admin/logout → redirect ke /admin/login
- Instructor logout → POST /instructor/logout → redirect ke /instructor/login
- User logout → POST /user/logout → redirect ke /login

### 6. Profile Management (per role)
- Admin profile: GET /admin/profile, POST /admin/profile/update
- Instructor profile: GET /instructor/profile, POST /instructor/profile/update
- User profile: GET /user/profile, POST /user/profile/update
- Fields: name, email, photo (upload), phone, address, bio (instructor only)

### 7. Password Change (per role)
- GET /{role}/setting → form change password
- POST /{role}/password/update → validate old password, update new

## CONSTRAINT:
- UI text dalam Bahasa Indonesia
- Code dalam English
- Gunakan TailwindCSS untuk styling
- Validasi menggunakan Form Request classes
- **Photo upload ke Cloudinary** (`belajarkuy/profiles` folder) — JANGAN ke public/uploads/
- JANGAN modifikasi migration users (sudah final)

## OUTPUT:
- Semua controller files
- Semua view files (Blade)
- Route additions untuk routes/web.php
- Form Request files jika ada
```
