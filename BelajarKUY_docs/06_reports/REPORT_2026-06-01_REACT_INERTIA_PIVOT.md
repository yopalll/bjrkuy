# Report — React + Inertia Pivot: Dokumentasi, Scaffold Fase 1, Jadwal Migrasi

> **Tanggal:** 1 Juni 2026
> **Sesi:** Session 9
> **PIC:** Yosua Valentino (PM) + Claude (AI Agent)
> **Branch:** `feature/inertia-scaffold` (kode), dokumen di `main`
> **Durasi:** ~3 jam

---

## Tujuan Sesi

Melakukan tiga hal sekaligus sebagai persiapan sprint migrasi frontend:

1. Menyelaraskan **seluruh dokumentasi** (`BelajarKUY_docs/`) terhadap `Kode_Nyata` — memperbaiki klaim usang tentang Laravel 12, Filament v5, Tailwind v4, dan "BUKAN Inertia".
2. Mengerjakan **scaffold Fase 1 React + Inertia** di kode: entry point, layout, komponen dasar, dua halaman publik, dan routing — sehingga `npm run build` langsung PASS.
3. Menyiapkan **artefak operasional** untuk sprint selanjutnya: prompt per anggota, jadwal push, atribusi desain, dan README.

---

## Yang Dikerjakan

### 1. Audit Progres Nyata vs Tracker

`PROGRESS_TRACKER.md` terakhir diperbarui 19 Mei 2026 (overall 30%). Setelah inspeksi langsung kode (`app/Http/Controllers`, `resources/views`, `routes/web.php`), kondisi nyata jauh lebih maju:

| Modul | Tracker (19 Mei) | Kondisi nyata (1 Juni) |
|---|---|---|
| Admin Panel CRUD | 0% | ~90% (11 controller + view Blade) |
| Landing + Course detail | 0% | ~90% (HomeController + frontend views) |
| Student Panel | 0% | ~85% (dashboard, my-courses, wishlist, profile) |
| Cart / Wishlist add | 0% | 15% (route masih placeholder) |
| Payment Midtrans | 0% | 25% (MidtransService ada; checkout stub) |
| **Overall** | **30%** | **~55%** |

`PROGRESS_TRACKER.md` diperbarui ke kondisi nyata, termasuk status migrasi React + Inertia (0% di kode) dan known issues (cart/player/Midtrans belum end-to-end).

---

### 2. Penyelarasan 25 Dokumen ke Kode Nyata (ADR-008)

Ditemukan inkonsistensi sistemik antara dokumentasi lama dan `Kode_Nyata` (`composer.json`, `package.json`, `HandleInertiaRequests.php`). Seluruh perbedaan diidentifikasi, didokumentasikan di `DOCS_UPDATE_PLAN_REACT_INERTIA.md`, lalu diselesaikan.

**Fakta kode nyata** yang menjadi acuan (verbatim dari `composer.json`/`package.json`):

| Item | Klaim dokumen lama | Nilai kode nyata |
|---|---|---|
| Laravel | `12.x` / `^12.0` | `^13.7` |
| Filament | terpasang (`filament/filament ^5.6`) | tidak ada di `composer.json` |
| Tailwind CSS | `v4` | `tailwindcss ^3.1.0` + `@tailwindcss/vite ^4.0.0` |
| Frontend stack | Blade + Alpine.js; Inertia ditolak (ADR-002) | `inertiajs/inertia-laravel ^3.1`, `@inertiajs/react ^3.3.0`, `react ^19.2.6` |
| Cloudinary | `cloudinary-labs/cloudinary-laravel ^2.0` | `cloudinary/cloudinary_php ^3.1` |
| Alpine.js (peran) | lapisan utama (TALL) | `devDependencies`, diturunkan |

**Berkas yang diperbarui (25 file `Update`):**

```
02_architecture/TECH_STACK.md        — blok composer/npm ditulis ulang verbatim
03_features/F07_ADMIN_PANEL.md       — Filament → halaman React+Inertia
PRD_BelajarKUY.md                    — tech stack, narasi admin
00_INDEX.md                          — baris tech stack, daftar dokumen baru
CHANGELOG.md                         — entri baru + koreksi Filament historis
07_extras/TECH_STACK_EXTRAS.md       — versi diperbarui
01_guides/{AGENT_GUIDELINES, CODING_STANDARDS, TESTING_STRATEGY}
01_guides/{SETUP_GUIDE, UI_UX_GUIDELINES}
02_architecture/FOLDER_STRUCTURE.md
02_architecture/ADR/README.md
04_plans/{MASTER_ROADMAP, SPRINT_PLAN, TASK_DISTRIBUTION}
05_prompts/{SETUP_PROJECT, FRONTEND, ADMIN_PANEL, STITCH_REDESIGN,
            AUTH, MODELS, MIDTRANS, MIGRATIONS}
06_reports/PROGRESS_TRACKER.md
```

ADR-002 (`Frontend: Blade + Alpine.js`) di-supersede oleh ADR-008 (`Frontend: React + Inertia`). ADR-008 sudah dibuat pada sesi sebelumnya.

**Validasi setelah selesai:**
- V2 registry: 70 baris, setiap item tepat sekali, selisih = 0.
- V3 versi verbatim: seluruh angka versi cocok karakter-demi-karakter dengan `composer.json`/`package.json`.
- V8 scope: 0 diff pada `app/`, `routes/`, `database/`, `config/` — hanya `.md` di `BelajarKUY_docs/` yang berubah.

---

### 3. Scaffold Fase 1 React + Inertia (Kode)

`npm run build` **PASS** — Vite 8.0.12, 2332 modul, 1.19 detik.

**Prinsip koeksistensi:** `app.js` (Alpine, Blade lama) tetap berjalan selama migrasi. Halaman React menggunakan `app.jsx` (entry baru). Keduanya dikompilasi Vite secara paralel.

**Berkas baru/diubah:**

| Berkas | Aksi |
|---|---|
| `vite.config.js` | Tambah `@vitejs/plugin-react`, input `app.jsx`, alias `@` ke `resources/js` |
| `resources/js/app.jsx` | Entry point Inertia (`resolvePageComponent`, root view `app`) |
| `resources/js/Layouts/AppLayout.jsx` | Layout publik React (Konteks_A: Indigo-Purple, Plus Jakarta Sans) |
| `resources/js/Components/AppHeader.jsx` | Navbar responsif, mengonsumsi shared prop `auth.user` |
| `resources/js/Components/CourseCard.jsx` | Kartu kursus reusable; field sesuai model `Course` nyata |
| `resources/js/Components/FlashToast.jsx` | Mengonsumsi shared prop `flash` dari `HandleInertiaRequests.php` |
| `resources/js/Pages/Welcome.jsx` | Halaman landing `/` (layar `landing_page_welcome`) |
| `resources/js/Pages/Home.jsx` | Halaman katalog `/home` (layar `katalog_kursus_home`) |
| `routes/web.php` | `'/'` → `Inertia::render('Welcome')` |
| `app/Http/Controllers/Frontend/HomeController.php` | `view()` → `Inertia::render('Home', …)` — data tidak berubah |

**Tidak diubah:** model, migrasi, skema DB, nama route, middleware peran — sesuai scope migrasi presentasi.

---

### 4. Prompt Khusus per Anggota

Tiga prompt dibuat untuk menyelesaikan sisa pekerjaan masing-masing anggota:

| Berkas | PIC | Cakupan |
|---|---|---|
| `05_prompts/PROMPT_INSTRUCTOR_PANEL.md` | Albariqi Tarigan | Course/Section/Lecture CRUD instruktur, submit-for-review, Course Player (F13), lecture completion, email CourseApproved/Rejected/NewSale |
| `05_prompts/PROMPT_COMMERCE.md` | Ray Nathan | Cart controller, Wishlist add/remove, Coupon CRUD, Midtrans Snap end-to-end, payment callback, Enrollment otomatis |
| `05_prompts/PROMPT_ADMIN_REACT_MIGRATION.md` | Quinsha Ilmi | Migrasi 11 area admin dari Blade ke `Pages/Admin/*` React + Inertia; logika controller/route tidak diubah |

Setiap prompt menyertakan: context kode nyata, constraint stack (React+Inertia), Definition of Done, dan nama branch yang direkomendasikan.

---

### 5. Atribusi Aset Redesign (Google Stitch)

`BelajarKuy_Design_Revisi.zip` dikerjakan berdua oleh **Vascha U** dan **Quinsha Ilmi**, masing-masing 2 folder ekspor:

| Ekspor | Pembuat | Fokus layar |
|---|---|---|
| (5) | Vascha U | Landing, katalog, detail kursus, keranjang, checkout, dashboard student & instructor |
| (7) | Vascha U | Login/registrasi, profil, notifikasi, riwayat transaksi, kursus saya, course player mobile |
| (6) | Quinsha Ilmi | Admin dashboard, moderasi kursus/review, kategori, kupon, pengaturan situs, pembayaran |
| (8) | Quinsha Ilmi | Admin final polish, halaman error 403–503, katalog & dashboard final polish |

Dicatat di `SCREEN_MAPPING_STITCH_REACT.md` §1.0 dan `TASK_DISTRIBUTION.md`.

---

### 6. Jadwal Pengerjaan + Rencana Push per Bagian

Dokumen `04_plans/MIGRATION_SCHEDULE_REACT_INERTIA.md` dibuat, mencakup:

- **Timeline W1–W4** (1–28 Juni 2026) dengan fokus per minggu.
- **Tabel push per anggota** (branch → PR → target minggu → dependensi antar bagian).
- **Aturan push:** 1 bagian = 1 branch = 1 PR → `develop`; push setelah DoD + `npm run build` sukses.
- **Dependensi lintas tim:** Course Player (Albariqi) bergantung Enrollment dari Commerce (Ray); NewSaleNotification (Albariqi) dipicu callback Midtrans (Ray).
- **DoD global:** 0 `.blade.php` presentasi yang masih dirujuk router (kecuali `app.blade.php` & email); skema DB & integrasi tidak berubah.

---

### 7. Repository GitHub + README + Git LFS

| Aksi | Detail |
|---|---|
| README.md dibuat | Bahasa Indonesia, akademis, tanpa emotikon; mengikuti panduan freeCodeCamp |
| Repo baru | `https://github.com/yopalll/bjrkuy.git` |
| Push | Branch `main` berhasil (150 file, history penuh) |
| Zip dihapus | `BelajarKuy_Design_Revisi.zip` (62 MB) di-untrack + dihapus dari disk; `.gitignore` root ditambahkan |
| Git LFS | 52 object (44 PNG + 8 PDF, total 19 MB) dimigrasikan ke LFS; `@.gitattributes` mencakup `*.png`, `*.jpg`, `*.jpeg`, `*.pdf`, `*.zip`, `*.rar`, `*.7z`, `*.tar.gz` |

Anggota tim wajib `git lfs install` sebelum clone. Setelah itu `git clone` berjalan normal — LFS object diunduh otomatis.

---

## Berkas Baru yang Dihasilkan Sesi Ini

```
README.md
.gitignore                                     (root repo)
.gitattributes                                 (LFS tracking)
BelajarKUY_docs/04_plans/MIGRATION_SCHEDULE_REACT_INERTIA.md
BelajarKUY_docs/05_prompts/PROMPT_INSTRUCTOR_PANEL.md
BelajarKUY_docs/05_prompts/PROMPT_COMMERCE.md
BelajarKUY_docs/05_prompts/PROMPT_ADMIN_REACT_MIGRATION.md
BelajarKUY/resources/js/app.jsx
BelajarKUY/resources/js/Layouts/AppLayout.jsx
BelajarKUY/resources/js/Components/AppHeader.jsx
BelajarKUY/resources/js/Components/CourseCard.jsx
BelajarKUY/resources/js/Components/FlashToast.jsx
BelajarKUY/resources/js/Pages/Welcome.jsx
BelajarKUY/resources/js/Pages/Home.jsx
```

---

## Kondisi Setelah Sesi

- `git log --oneline` (4 commit baru di `bjrkuy/main`):
  ```
  7657112  chore: migrate all PNG and PDF blobs to Git LFS
  1719775  chore: configure Git LFS tracking (*.png, *.pdf, …)
  f69f787  chore: remove large zip from tracking, add root .gitignore
  b5c6232  docs+feat: align docs to React+Inertia, scaffold Fase 1, add README
  ```
- `npm run build` PASS (Vite 8.0.12, 2332 modul).
- Tidak ada perubahan model/migrasi/skema DB.
- `PROGRESS_TRACKER.md` diperbarui (overall ~55%; migrasi React+Inertia 0% di kode — Fase 1 baru dirintis).

---

## Langkah Berikutnya

| Prioritas | Bagian | PIC | Branch |
|---|---|---|---|
| 1 | Port `Courses/Show.jsx` (detail kursus, masih placeholder) | Vascha | `feature/public-react` |
| 2 | Cart + Wishlist add/remove | Ray | `feature/cart-wishlist` |
| 3 | Instructor Course CRUD | Albariqi | `feature/instructor-courses` |
| 4 | Admin panel React (shell + halaman) | Quinsha | `feature/admin-shell-react` |

Prompt siap pakai untuk setiap bagian di `BelajarKUY_docs/05_prompts/`. Jadwal lengkap di `04_plans/MIGRATION_SCHEDULE_REACT_INERTIA.md`.
