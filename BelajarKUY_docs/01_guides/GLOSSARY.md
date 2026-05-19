# 📖 BelajarKUY — Glossary & Terminology

> Kamus istilah resmi untuk seluruh codebase, dokumentasi, dan UI BelajarKUY.
> Wajib dibaca oleh semua AI agent dan developer sebelum menulis kode.
> **Version:** 1.0 | **Updated:** 14 Mei 2026

---

## 🧑 Roles & User Types

| Term | Meaning | Where to Use |
|------|---------|--------------|
| **User** (generic) | Baris di tabel `users` | Code: DB table name, variable names (`$user`, `User::find()`) |
| **Student** | User dengan `role = 'user'` | UI text ("Siswa"), folder `Backend/Student/`, controller `StudentDashboardController` |
| **Instructor** | User dengan `role = 'instructor'` | UI text ("Instruktur"), folder `Backend/Instructor/`, controller `InstructorCourseController` |
| **Admin** | User dengan `role = 'admin'` | UI text ("Admin"), folder `Backend/Admin/`, controller `AdminDashboardController` |

### ⚠️ Clarification on "User"

"User" memiliki makna ganda yang **intentional**:
- **As DB value:** `users.role = 'user'` adalah enum value untuk Student. Dipertahankan karena konsistensi dengan tabel `users` (semua pengguna sistem, bukan hanya student). Mengubah ke `'student'` akan butuh Schema v3 + refactor semua query — cost > benefit untuk project akademik.
- **As UI/Business term:** Kita selalu katakan **"Student"** / **"Siswa"**. User dengan role `admin` atau `instructor` bukan "Student".

**Rule:**
- Code DB-related: `user` (DB enum value)
- Code business logic: `student` (method names like `scopeStudents()`, `isStudent()`)
- UI text Indonesia: "Siswa"
- UI text English: "Student"
- Route prefix: `/user/*` (legacy, match DB)
- Folder & controller name: `Student` (business term)

Lihat `02_architecture/ADR/ADR-007-role-naming.md` untuk reasoning.

---

## 💳 Purchase & Access States

Istilah ini **paling sering salah pakai**. Gunakan dengan presisi:

| Term | Meaning | Source of Truth |
|------|---------|-----------------|
| **Paid** | Midtrans sudah konfirmasi pembayaran berhasil | `payments.status IN ('settlement', 'capture')` |
| **Purchased** | Transaksi pembelian tercatat sebagai "completed" | `orders.status = 'completed'` |
| **Enrolled** | Student punya akses ke konten course | Ada row di `enrollments` |

### Flow

```
[Student klik Bayar]
      ↓
Payment record created (status=pending)
      ↓
Midtrans Snap processes
      ↓
Webhook callback fired → status=settlement
      ↓ (di handleSuccess())
Order record created (status=completed)
      ↓ (atomic transaction)
Enrollment record created
      ↓
Clear cart items
```

### ⚠️ Rule of Thumb

**"Apakah student ini boleh menonton lecture?"** → Cek `Enrollment`, bukan `Order` atau `Payment`.

```php
// ✅ BENAR — fastest, simplest, single table lookup
$canWatch = Enrollment::where('user_id', $userId)
    ->where('course_id', $courseId)
    ->exists();

// ❌ SALAH — unnecessary joins, slower
$canWatch = Order::where('user_id', $userId)
    ->where('course_id', $courseId)
    ->where('status', 'completed')
    ->exists();
```

Tabel `enrollments` sengaja dibuat untuk menghindari join `payments → orders → status` yang mahal saat student buka halaman course player.

---

## 🎓 Course Terminology

| Term | Meaning |
|------|---------|
| **Course** | Unit pembelajaran utama yang dijual (punya harga, thumbnail, dll) |
| **Section** | Bab/bagian di dalam course (contoh: "Bagian 1: Pengenalan") |
| **Lecture** | Unit konten terkecil (video, PDF, text) di dalam section |
| **Goal** | Learning objective course ("Setelah kursus, kamu akan bisa...") |
| **Enrollment** | Record bahwa student sudah punya akses ke course |
| **Completion** | Record bahwa student sudah menyelesaikan 1 lecture |
| **Progress** | `completions.count() / lectures.count() * 100` — dihitung on-the-fly |

### Hierarchy

```
Course
 ├── CourseGoal (many, flat list)
 └── CourseSection (many, ordered)
       └── CourseLecture (many, ordered)
             └── LectureCompletion (per-user tracking)
```

---

## 🛒 Commerce Terminology

| Term | Meaning |
|------|---------|
| **Cart** | Keranjang belanja, isi sementara sebelum checkout |
| **Wishlist** | Daftar keinginan — course yang disimpan untuk nanti |
| **Checkout** | Proses konfirmasi pembelian + inisiasi payment |
| **Coupon** | Kode promo untuk diskon checkout (global atau per-course) |
| **Order** | Record transaksi (1 row per course per payment) |
| **Payment** | Record pembayaran Midtrans (1 payment bisa punya >1 order) |

### Pricing Snapshot

Kenapa `orders` punya `original_price`, `discount_amount`, `final_price` terpisah?
Karena **harga course bisa berubah** setelah transaksi. Order harus ingat harga **saat** checkout, bukan harga sekarang.

```
original_price = courses.price SAAT CHECKOUT
discount_amount = dari coupon yang diapply
final_price = original - discount
```

---

## 📢 Notification Types

Lihat `03_features/F14_NOTIFICATIONS.md` untuk mapping lengkap.

| Term | Meaning |
|------|---------|
| **Mail Notification** | Email via Resend (prod) / Mailtrap (dev) |
| **Real-time Notification** | Push via Laravel Reverb (WebSocket) |
| **Flash Message** | Feedback UI setelah redirect (`session()->flash()`) |
| **Toast** | Popup sementara di kanan atas (SweetAlert2) |

---

## 🛡️ Admin Actions Terminology

| Term | Meaning |
|------|---------|
| **Approve (Course)** | Ubah `courses.status` dari `pending_review` ke `active` |
| **Reject (Course)** | Ubah `courses.status` dari `pending_review` ke `inactive` + notify instructor |
| **Approve (Review)** | Ubah `reviews.status` dari `false` ke `true` (jarang dipakai karena default `true`) |
| **Reject (Review)** | Ubah `reviews.status` ke `false` — review tidak tampil ke publik |
| **Block (User)** | **Not in MVP.** Out of scope — lihat ADR-006. |

### Catatan Penting (ADR-006)

**Instructor tidak perlu di-approve sebelum bisa membuat course.** Saat register sebagai instructor, langsung aktif. Yang di-approve oleh admin adalah **course-nya**, bukan instructor-nya.

Approval flow untuk user (block/unblock) **tidak di-scope MVP**.

---

## 💰 Revenue Terminology

| Term | Meaning |
|------|---------|
| **Gross Revenue** | Total `payments.total_amount` dengan status settlement/capture |
| **Net Revenue** | Not applicable — **Payout/revenue split out of scope** (ADR-005) |
| **Platform Share** | Not applicable (ADR-005) |
| **Instructor Share** | Not applicable (ADR-005) |

### Catatan Penting (ADR-005)

Project akademik tidak punya sistem payout. Instructor **tidak menerima uang real** — simulasi murni. Semua revenue tercatat di `payments` untuk reporting.

---

## 🚫 DEPRECATED Terms

Jangan pakai istilah ini — gunakan alternatif:

| ❌ Deprecated | ✅ Use Instead | Reason |
|---|---|---|
| "Member" | Student / Instructor | Ambigu |
| "Buy" (dalam code) | Purchase / Enroll | "Buy" terlalu loose |
| "Complete course" | "Finish all lectures" atau "100% progress" | Ambigu — "complete" bisa berarti apa saja |
| "Active instructor" | Instructor dengan role='instructor' | Tidak ada status aktif terpisah |
| "Approved instructor" | Tidak ada — semua instructor langsung aktif (ADR-006) | — |
| `courses.order` | `courses.sort_order` | Konsistensi naming di Schema v2 |
| `coupons.name` | `coupons.code` | Field renamed di Schema v2 |
| `coupons.validity` | `coupons.valid_until` | Field renamed di Schema v2 |
| `coupons.discount` | `coupons.discount_percent` | Field renamed di Schema v2 |
| `orders.price` | `original_price` + `discount_amount` + `final_price` | Schema v2 pakai snapshot |

---

*Jika menemukan istilah baru yang sering dipakai, tambahkan di sini. Ini adalah single source of truth untuk terminology.*
