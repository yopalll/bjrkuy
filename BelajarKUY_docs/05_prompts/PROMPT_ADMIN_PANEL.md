# 🤖 PROMPT: Build Admin Panel

> Copy-paste prompt ini ke AI agent untuk membangun admin panel.
> **PIC: Quinsha Ilmi & Vascha U (UI/UX)**

---

## PROMPT

```
Kamu adalah senior Laravel 12 developer. Bangun admin panel lengkap untuk project BelajarKUY (Udemy clone Indonesia).

## PREREQUISITE: Baca file-file berikut terlebih dahulu:
- BelajarKUY_docs/01_guides/AGENT_GUIDELINES.md
- BelajarKUY_docs/03_features/F07_ADMIN_PANEL.md
- BelajarKUY_docs/02_architecture/API_ROUTES.md (section Admin Routes)

## CONTEXT:
- Semua model sudah ada
- RoleMiddleware sudah terdaftar (alias 'role')
- Admin route prefix: /admin, middleware: auth + role:admin
- Layout admin terpisah dari layout publik

## TASKS:

### 1. Admin Layout (resources/views/layouts/admin.blade.php)
- Sidebar navigasi (collapsible)
- Top bar (admin name, notifications, logout)
- Content area
- TailwindCSS dark sidebar style
- Menu items: Dashboard, Kategori, Sub-kategori, Kursus, Instructor, Order, User, Slider, Info Box, Partner, Review, Settings, Profile

### 2. Admin Dashboard (resources/views/backend/admin/dashboard.blade.php)
- Stats cards: Total Users, Total Instructors, Total Courses, Active Courses, Total Orders, Total Revenue, This Month Revenue
- Recent orders table (last 10)
- Recent registrations (last 5 users)
- Revenue chart (optional — simple bar chart)

### 3. Category CRUD
- Index: Table dengan columns (Image, Name, Slug, Status, Actions)
- Create: Form (name, image upload)
- Edit: Form pre-filled
- Delete: Confirm dialog (SweetAlert2)
- Toggle status via AJAX

### 4. SubCategory CRUD
- Same as category, tapi punya dropdown pilih parent category

### 5. Course Management
- Index: Table (Thumbnail, Title, Instructor, Category, Status, Actions)
- Show: Detail view
- Approve/Reject: Toggle status (pending_review → active / inactive)
- TIDAK ada create/edit — itu tugas instructor

### 6. Instructor Management (View Only — ADR-006)
- Index: Table (Photo, Name, Email, # Courses, # Coupons, Actions)
- View: Detail instructor + list kursusnya + statistik
- ❌ TIDAK ADA Approve/Block button — instructor auto-active sesuai ADR-006
- ❌ TIDAK ADA payout UI — out of scope sesuai ADR-005

### 7. Order Management
- Index: Table (Order ID, Student, Course, Amount, Status, Date, Actions)
- Show: Detail order + payment info
- Filter by status

### 8. User Management
- Index: Table (Photo, Name, Email, Role, Date, Actions)
- TIDAK ada create — user register sendiri

### 9. Slider CRUD, Info Box CRUD, Partner CRUD
- Standard CRUD dengan image upload
- Reorder support (drag or order field)

### 10. Site Settings
- Key-value pairs (logo, site_name, phone, email, address, facebook, instagram, twitter, youtube)
- Edit form: loop through all settings

### 11. Settings

Halaman "Settings" ditempatkan di satu entry menu sidebar: **Site Settings**.

Halaman ini edit konten **yang disimpan di DB**:
- Logo, site_name, tagline, email, phone, address, footer text
- Social media URLs (facebook, instagram, twitter, youtube)
- Disimpan sebagai key-value di tabel `site_infos`

⚠️ **HAPUS** halaman-halaman ini (sebelumnya ada di spec, sekarang di-remove):
- ❌ Mail Setting UI — credentials di .env, bukan DB
- ❌ Midtrans Setting UI — credentials di .env, hardcoded sandbox (ADR-004)
- ❌ Google OAuth Setting UI — credentials di .env
- ❌ Cloudinary Setting UI — credentials di .env

Project ini tidak memerlukan edit API keys via UI — credentials cukup di `.env` server.

### 12. Review Management
- Index: Table (Student, Course, Rating, Comment, Status, Actions)
- Approve/Reject toggle

## DESIGN:
- Sidebar gelap (bg-gray-800 / bg-slate-900)
- Content area putih
- Cards untuk stats
- Tables dengan pagination
- Forms dengan validation feedback
- SweetAlert2 untuk delete confirmation
- Toast notification untuk success/error

## CONSTRAINT:
- Semua route di prefix 'admin' dengan middleware auth + role:admin
- Image upload ke **Cloudinary** (BUKAN ke public/uploads/)
- Validasi menggunakan Form Request classes
- Pagination 10-15 items per halaman
- Text UI dalam Bahasa Indonesia
- Kode dalam English

## OUTPUT:
- Admin layout file
- Semua controller files (di Backend/Admin/)
- Semua view files (di backend/admin/)
- Form Request files
- Route additions
```
