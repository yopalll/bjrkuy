# 🔐 F01: Sistem Autentikasi

> Multi-role authentication dengan Laravel Breeze + Google OAuth.

---

## Fitur

1. **Register** — User baru default role `user` (Student)
2. **Login** — Email + password
3. **Google OAuth** — Login via Google (Socialite)
4. **Role-based Access** — `user` / `instructor` / `admin` (lihat `GLOSSARY.md`)
5. **Role Middleware** — Proteksi route berdasarkan role
6. **Password Reset** — Via email (Breeze default)
7. **Email Verification** — Optional (recommended ON)
8. **Profile Management** — Edit nama, foto (Cloudinary), phone, address
9. **Password Change** — Di halaman settings
10. **Separate Login Pages** — Admin (`/admin/login`), Instructor (`/instructor/login`), Student (default `/login`)
11. **Welcome Email** — Dikirim saat register (lihat `F14_NOTIFICATIONS.md`)

---

## Database

Tabel: `users` (extended Schema v2)

```
id, name, email, password, role (enum: user|instructor|admin),
photo, phone, address, bio, website, email_verified_at,
remember_token, created_at, updated_at
```

---

## Flow Registrasi

```
[Register Page]
     ↓
[Pilih Role: Student (default) / Instructor]   ← ADR-006: instructor auto-active
     ↓
[Fill Form: name, email, password]
     ↓
[Validate]
     ↓
[Create User + Hash Password]
     ↓
[Send Welcome Email] (WelcomeMail — F14)
     ↓
[Auto-login]
     ↓
[Redirect to Dashboard per role]
```

**Catatan ADR-006:** Jika user pilih role Instructor, langsung aktif — tidak ada approval flow.

## Flow Login

```
[Login Page]
    ↓
[Validate Credentials]
    ↓
[Check Role]
    ↓
[Redirect to Dashboard berdasarkan role]
```

## Redirect Logic

```php
// Setelah login, redirect berdasarkan role:
match(auth()->user()->role) {
    'admin' => route('admin.dashboard'),
    'instructor' => route('instructor.dashboard'),
    'user' => route('user.dashboard'),    // Student
};
```

## Google OAuth Flow

```
[Klik "Login dengan Google"]
    ↓
[Redirect ke Google Consent Screen]
    ↓
[Google callback ke /auth/google-callback]
    ↓
[Cek email di DB]
  ├── Jika ada: login existing user
  └── Jika tidak:
       ├── Create user (role='user' by default, name dari Google)
       ├── Send WelcomeMail
       └── Login
    ↓
[Redirect ke Dashboard sesuai role]
```

---

## Profile Photo Upload (Cloudinary)

```php
// ProfileController@update
if ($request->hasFile('photo')) {
    $result = $request->file('photo')->storeOnCloudinary('belajarkuy/profiles');
    $user->photo = $result->getSecurePath();
}
```

⚠️ **JANGAN** upload ke `public/uploads/profiles/` — pakai Cloudinary.

---

## Related Docs

- `F14_NOTIFICATIONS.md` — Welcome email, password reset email
- `GLOSSARY.md` — "Student" vs "user" terminology
- `ADR-006` — Instructor auto-active decision

---

## PIC: Albariqi Tarigan
