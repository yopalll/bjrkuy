# 👥 BelajarKUY — Task Distribution

> Pembagian tugas per anggota tim.

---

## Summary Per Anggota

| Anggota | Role | Area Lead | Collaborator Role |
|---------|------|-----------|-------------------|
| **Yosua Valentino** | PM & Architect | Setup, DB, integrasi, code review, testing oversight | — |
| **Albariqi Tarigan** | Backend Lead (Auth + Course) | Auth, Course CRUD, Instructor panel, Course Player (backend) | — |
| **Ray Nathan** | Backend Lead (Commerce) | Cart, Wishlist, Payment (Midtrans), Coupon | — |
| **Vascha U** | Frontend Lead | Landing page, course detail, student panel, Course Player (frontend) | Admin panel UI |
| **Quinsha Ilmi** | UI/UX Lead (Admin) | Admin panel, admin dashboard, admin CRUD | Frontend components |

### Konvensi "Lead vs Collaborator"

- **Lead** = owner fitur. Decision-maker, pastikan spec terpenuhi, buat PR utama.
- **Collaborator** = membantu lead saat diminta. Bukan owner, tidak perlu hadir setiap meeting fitur tsb.

---

## Detail Per Anggota

### 1. Yosua Valentino (PM)

```
☐ Init Laravel 12 project
☐ Setup TailwindCSS + Vite config
☐ Create ALL database migrations (~20 files)
☐ Create ALL Eloquent models (~18 models)
☐ Create database seeders (admin, categories, demo courses)
☐ Setup config/midtrans.php
☐ Create MidtransService.php
☐ Setup .env.example with all variables
☐ Code review semua PR
☐ Performance optimization & bug fix final
```

### 2. Albariqi Tarigan (Backend Lead — Auth & Course)

```
Lead:
✅ Install Laravel Breeze (sudah terinstall + scaffolded — verified Session 7)
✅ Customize registration (add role selection — Siswa/Instruktur radio card)
✅ Implement RoleMiddleware (alias 'role' di bootstrap/app.php)
✅ Setup Google OAuth (Socialite — Session 6, GoogleController.php)
✅ Post-login redirect logic by role (match() → admin/instructor/student dashboard)
✅ Post-register redirect logic by role
✅ Dashboard Controllers (Admin, Instructor, Student — placeholder)
✅ Dashboard Views (backend/admin|instructor|student/dashboard.blade.php)
☐ Create separate login pages (admin/login, instructor/login) — NEXT
☐ Course CRUD controller (instructor)
☐ Course form + validation (StoreCourseRequest) — Cloudinary upload
☐ Dynamic subcategory loading (AJAX)
☐ Course Section CRUD
☐ Course Lecture CRUD
☐ Instructor dashboard + profile (detail — expand existing placeholder)
☐ Submit for review flow (draft → pending_review)
☐ Course Player backend (F13)
☐ Lecture completion tracking
☐ Email integrations (Welcome, Course Approved, Course Rejected)
```

### 3. Ray Nathan (Backend — Commerce)

```
☐ Wishlist add/remove (AJAX controller)
☐ Wishlist page UI
☐ Cart system — add, remove, fetch (AJAX)
☐ Cart page UI with pricing
☐ Checkout page
☐ Midtrans Snap integration (frontend JS)
☐ Payment controller (create snap token)
☐ Payment callback handler (notification URL)
☐ Order creation after successful payment
☐ Coupon CRUD (instructor)
☐ Coupon apply logic at checkout
```

### 4. Vascha U (Frontend Lead)

```
Lead:
☐ Main layout (app.blade.php) with TailwindCSS + Alpine
☐ Admin layout (admin.blade.php) — collaborate with Quinsha
☐ Instructor layout (instructor.blade.php)
☐ Student layout (student.blade.php)
☐ Navbar component (responsive, cart badge, user menu)
☐ Footer component
☐ Hero slider section
☐ Category card component
☐ Course card component
☐ Featured courses section
☐ Course detail page (full)
☐ Cart & Checkout page UI
☐ Student dashboard
☐ Student enrolled courses page (with progress bars)
☐ Course Player frontend (F13) — video embed, sidebar, progress
☐ Live search component (Meilisearch + Alpine)
☐ Toast/notification listener (Reverb events)
```

### 5. Quinsha Ilmi (UI/UX Lead — Admin & Frontend)

```
Lead:
☐ Admin layout (sidebar, topbar, content area)
☐ Admin dashboard (stats + recent activity)
☐ Category CRUD (admin) + image upload (Cloudinary)
☐ SubCategory CRUD (admin)
☐ Admin course management (approve/reject flow) — ADR-006
☐ Admin instructor LIST (view-only, no approve/block) — ADR-006
☐ Admin order management (list + detail + filter)
☐ Admin user list (view-only)
☐ Slider CRUD (admin) — Cloudinary
☐ Info Box CRUD (admin)
☐ Partner CRUD (admin) — Cloudinary
☐ Site Settings CRUD (key-value pairs)
☐ Admin review management (approve/reject)

Collaborator (with Vascha):
☐ Review & Rating system backend logic
☐ Course card component polish
```

---

## Task Status Legend

```
☐  = Belum dikerjakan
🔄 = Sedang dikerjakan
✅ = Selesai
❌ = Blocked / bermasalah
```

---

## Aturan Kolaborasi

1. **Sebelum mulai task baru** → Pull latest dari `develop`
2. **Setelah selesai task** → Push, buat PR, update `PROGRESS_TRACKER.md`
3. **Jika blocked** → Tulis di PROGRESS_TRACKER dan notify PM (Yosua)
4. **Jika perlu modifikasi file orang lain** → Koordinasi dulu via chat
5. **Jika menggunakan AI agent** → Pastikan agent membaca `AGENT_GUIDELINES.md` terlebih dahulu

---

## ⚠️ WAJIB Setelah Setiap Sesi Kerja

> Aturan ini berlaku untuk **semua anggota tim** dan **semua AI agent**.

Setelah selesai mengerjakan task, WAJIB melakukan hal berikut **sebelum push**:

### A. Update `TASK_DISTRIBUTION.md` (file ini)

Ganti status checkbox task yang diselesaikan:
```
☐ Task yang belum dikerjakan
→ ✅ Task yang selesai (tambahkan catatan singkat jika perlu)
```

### B. Update `PROGRESS_TRACKER.md`

1. Update timestamp di baris pertama: `> **Update terakhir:** DD Mei YYYY — HH:MM WIB oleh [NAMA]`
2. Update persentase modul di tabel Summary
3. Centang checkbox di section Phase yang sesuai: `- [ ]` → `- [x]`
4. Pindahkan item yang selesai ke section `## 🟢 SELESAI` jika modul penuh selesai
5. Tambahkan entry baru di `## 📝 Session Logs` dengan format:
   ```
   ### Session N — DD Mei YYYY (Nama/Inisial)
   - Created/Updated: [daftar file yang dibuat/dimodifikasi]
   - Branch: [nama branch]
   - Status: [ringkasan status]
   - Next: [task berikutnya]
   - Report: [link ke daily report jika ada]
   ```

### C. Buat Daily Report

Jika menyelesaikan milestone besar (1 feature atau lebih), buat file:
```
BelajarKUY_docs/06_reports/REPORT_YYYY-MM-DD_TOPIK.md
```
Lihat format di `AGENT_GUIDELINES.md` section 8 atau contoh di `06_reports/`.

### D. Commit & Push

Gunakan Conventional Commits:
```
feat(auth): implement role middleware and dashboard controllers
docs: update progress tracker and task distribution session 7
```

---

*Pembagian tugas ini bisa berubah sesuai progress. Update jika ada perubahan.*
*Last updated: 17 Mei 2026 — Session 7 — Antigravity (AI Agent) untuk Albariqi Tarigan*
