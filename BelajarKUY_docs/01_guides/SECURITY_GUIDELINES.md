# 🛡️ BelajarKUY — Security Guidelines

> Checklist security minimum untuk seluruh code BelajarKUY.
> Wajib diikuti sebelum feature dianggap "done".

---

## 1. Authentication & Authorization

### ✅ Required

- [ ] Gunakan `auth` middleware untuk semua route yang butuh login
- [ ] Gunakan `role:admin` / `role:instructor` / `role:student` middleware untuk route per-role
- [ ] Controller method yang modify data user-scoped: **SELALU** cek `auth()->id()` match dengan resource owner

```php
// ✅ BENAR — cek ownership
public function update(Course $course)
{
    abort_unless($course->instructor_id === auth()->id(), 403);
    // ... update logic
}

// ❌ SALAH — siapa saja yang login bisa edit
public function update(Course $course)
{
    $course->update(request()->validated());
}
```

### ❌ Forbidden

- ❌ JANGAN expose internal ID di URL publik — pakai `slug`
- ❌ JANGAN trust `role` dari request body — baca dari `auth()->user()->role`
- ❌ JANGAN hardcode credentials di code — pakai `.env`

---

## 2. Input Validation

### ✅ Required

Gunakan **Form Request** classes untuk validasi kompleks:

```php
// ✅ BENAR
public function store(StoreCourseRequest $request): RedirectResponse
{
    $course = Course::create($request->validated());
    // ...
}

// ❌ SALAH — inline validation untuk logic kompleks
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        // 20 rules more...
    ]);
}
```

### Rules Wajib per Field Type

| Field | Minimum Rule |
|-------|--------------|
| `email` | `required\|email\|max:255` |
| `password` (register) | `required\|min:8\|confirmed` |
| `name` (user-facing) | `required\|string\|max:255` |
| `image` (upload) | `nullable\|image\|mimes:jpg,jpeg,png,webp\|max:2048` (2MB) |
| `video_url` | `nullable\|url\|max:500` |
| `price` (IDR) | `required\|numeric\|min:0\|max:99999999999.99` |
| `discount_percent` | `required\|integer\|between:0,100` |
| `slug` | `required\|string\|regex:/^[a-z0-9-]+$/\|max:255` |
| `enum status` | `required\|in:draft,pending_review,active,inactive` |

---

## 3. CSRF Protection

### ✅ Required

- [ ] Semua form POST/PUT/DELETE **SELALU** include `@csrf` di Blade
- [ ] Semua AJAX POST **SELALU** kirim CSRF token via `X-CSRF-TOKEN` header

```blade
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
window.axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').content;
</script>
```

### ⚠️ CSRF Exclusion (Hanya Webhook)

**Hanya** `/payment/callback` (Midtrans webhook) yang boleh di-exclude. Alasannya: Midtrans server tidak kirim CSRF token.

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        'payment/callback',
    ]);
})
```

**Verifikasi alternatif untuk webhook:**
- Midtrans kirim signature key di callback body
- Gunakan `Midtrans\Notification` yang internally verify signature ke Midtrans API
- Implementation di `MidtransService::handleNotification()`

---

## 4. SQL Injection Prevention

### ✅ Required

Laravel Eloquent otomatis escape. Pakai query builder / Eloquent:

```php
// ✅ BENAR — parameterized
$users = User::where('email', $email)->get();
DB::select('SELECT * FROM users WHERE email = ?', [$email]);

// ❌ SALAH — string concat
DB::select("SELECT * FROM users WHERE email = '$email'");
```

---

## 5. XSS Prevention

### ✅ Required

Blade **otomatis escape** dengan `{{ }}`:

```blade
{{-- ✅ BENAR — escaped --}}
<p>{{ $user->name }}</p>

{{-- ❌ SALAH — raw output — berbahaya jika $comment dari user --}}
<div>{!! $review->comment !!}</div>
```

### ⚠️ Untuk Raw HTML

Jika **harus** output raw HTML (rare — misal Markdown render), pakai:
- Laravel `str()->markdown()` (built-in, safe)
- Atau library `league/commonmark` dengan `ALLOWED_HOST_SCHEMES`

**Jangan** pernah langsung `{!! $userInput !!}`.

### JSON Encoding

```blade
{{-- ✅ BENAR --}}
<script>
    var data = @json($data);
</script>

{{-- ❌ SALAH --}}
<script>
    var data = {!! json_encode($data) !!};  // bypass XSS protection
</script>
```

---

## 6. File Upload Security

### ✅ Required

- [ ] Validate mime type (`mimes:jpg,png,webp`)
- [ ] Validate max size (`max:2048` untuk image, `max:10240` untuk video)
- [ ] Store ke **Cloudinary**, bukan `public/uploads/` (aturan BelajarKUY)
- [ ] Jangan trust extension dari filename user

```php
// ✅ BENAR
$request->validate([
    'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
]);
$result = $request->file('thumbnail')->storeOnCloudinary('belajarkuy/courses');

// ❌ SALAH
$file = $request->file('thumbnail');
$file->move(public_path('uploads'), $file->getClientOriginalName());
```

---

## 7. Password Security

### ✅ Required

- [ ] Password **minimum 8 chars** (Laravel default)
- [ ] **Hashed** — pakai `Hash::make()` atau `casts = ['password' => 'hashed']`
- [ ] Rate limit login attempts — Breeze default: 5 attempts per minute
- [ ] Password reset via email (Breeze built-in)

```php
// ✅ BENAR — di User model
protected function casts(): array
{
    return [
        'password' => 'hashed',
    ];
}

// Saat create/update:
User::create([
    'email' => $email,
    'password' => $plainPassword,  // auto-hashed oleh cast
]);
```

### ❌ Forbidden

- ❌ JANGAN log password ke `storage/logs/laravel.log`
- ❌ JANGAN tampilkan password di response JSON
- ❌ JANGAN kirim password via email (plain text)

---

## 8. Session Security

### Config Wajib (`.env` / `config/session.php`)

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false  # true untuk production HTTPS
SESSION_SAME_SITE=lax
SESSION_SECURE_COOKIE=false  # true untuk production HTTPS
SESSION_HTTP_ONLY=true
```

### Session Invalidation

Setelah logout **WAJIB** invalidate session:

```php
// app/Http/Controllers/Auth/AuthenticatedSessionController.php
public function destroy(Request $request): RedirectResponse
{
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
}
```

Ini sudah default Breeze — jangan hapus.

---

## 9. Payment Security (Midtrans)

### ✅ Required

- [ ] Server key **HANYA** di `.env`, JANGAN di code repo
- [ ] Client key boleh expose di frontend (by design — untuk Snap)
- [ ] **HARUS** verify signature di callback webhook via `Midtrans\Notification`
- [ ] Create Payment record **sebelum** Snap token (bukan setelah)
- [ ] Cart **JANGAN** di-clear sebelum payment confirmed
- [ ] `fraud_status` **harus** di-handle untuk credit card

```php
// ✅ BENAR — verify signature
public function callback(Request $request)
{
    $notification = new \Midtrans\Notification();
    // SDK otomatis verify signature + fetch ke Midtrans API

    $orderId = $notification->order_id;
    $status = $notification->transaction_status;
    $fraudStatus = $notification->fraud_status ?? null;

    $payment = Payment::where('midtrans_order_id', $orderId)->firstOrFail();

    // Handle status...
}
```

---

## 10. Rate Limiting

### ✅ Required Minimum

Di `routes/web.php`:

```php
// Login attempts
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', ...);
});

// Apply coupon — prevent brute force coupon code guessing
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('/coupon/apply', ...);
});

// Search — prevent abuse
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/api/search', ...);
});
```

---

## 11. Audit Logging

Untuk admin action yang destructive (delete, block, approve/reject), log ke `storage/logs/laravel.log`:

```php
// ✅ BENAR
public function approve(Course $course)
{
    $course->update(['status' => 'active']);

    Log::info('Course approved', [
        'course_id' => $course->id,
        'admin_id' => auth()->id(),
        'timestamp' => now(),
    ]);
}
```

Future: bisa ditambahkan tabel `audit_logs` (tidak dalam MVP).

---

## 12. Environment Separation

| Env | APP_DEBUG | APP_ENV | Mail | Midtrans |
|-----|-----------|---------|------|----------|
| Local dev | `true` | `local` | `log` or Mailtrap | Sandbox |
| Production demo | `false` | `production` | Resend | Sandbox (hardcoded) |

**NEVER** deploy dengan `APP_DEBUG=true` ke public URL — leaks environment info.

---

## 13. Dependencies

- Jalankan `composer audit` sebelum deploy — periksa CVE
- Jalankan `npm audit` — periksa JS deps
- Update Laravel patch version rutin (`composer update laravel/framework`)

---

## Pre-Deploy Checklist

Sebelum deploy ke public URL:

- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` sudah generate (`php artisan key:generate`)
- [ ] Semua `.env` variable terisi (cek `php artisan about`)
- [ ] `SESSION_SECURE_COOKIE=true` dan `SESSION_ENCRYPT=true`
- [ ] HTTPS enforced (redirect HTTP → HTTPS via server / middleware)
- [ ] CSRF token tested di form register & login
- [ ] Midtrans webhook URL diset di dashboard sandbox
- [ ] `composer audit` clean
- [ ] `php artisan route:cache && php artisan config:cache`

---

*Security adalah responsibility semua developer. Jangan assumsikan framework handle semuanya.*
