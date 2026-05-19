# 🎨 UI/UX Design Guidelines & Workflow

Dokumen ini berisi panduan mengenai halaman apa saja yang perlu didesain (sketsa/wireframe/mockup) untuk proyek **BelajarKUY** dan bagaimana *workflow* untuk mengimplementasikan desain tersebut ke dalam *codebase* Laravel.

---

## 📋 1. Daftar Sketsa/Desain yang Perlu Dibuat

Tidak perlu mendesain semuanya di awal. Fokuslah pada halaman-halaman utama (MVP). Berikut adalah daftar halaman yang wajib didesain:

### A. Halaman Publik (Frontend)
1. **Landing Page (Beranda):** Hero banner, pencarian cepat, daftar kategori populer, *featured/top courses*, testimoni, dan footer.
2. **Katalog Course (Pencarian & Filter):** Halaman hasil pencarian course dengan sidebar filter (harga, kategori, rating) dan list/grid *card course*.
3. **Detail Course:** Halaman informasi course sebelum dibeli. Berisi video *preview*, deskripsi, kurikulum/silabus, profil instruktur, harga, tombol "Add to Cart" / "Buy Now", dan *reviews*.
4. **Keranjang (Cart) & Checkout:** Halaman rincian pesanan, input kupon diskon, dan ringkasan total biaya.

### B. Sistem Autentikasi
5. **Login & Register:** Form masuk dan daftar (pastikan ada area untuk tombol "Login with Google").

### C. Panel Siswa (Student Dashboard)
6. **Dashboard Siswa:** Ringkasan progres belajar dan statistik ringkas.
7. **My Courses:** Daftar course yang sudah dibeli oleh siswa.
8. **Course Player (Halaman Belajar):** Halaman utama untuk menonton materi. Terdiri dari area video utama, *sidebar* daftar modul/kurikulum, dan tab konten (Deskripsi, Q&A/Komentar) di bawah video.

### D. Panel Instruktur (Instructor Dashboard)
9. **Dashboard & Statistik:** Ringkasan pendapatan, total siswa, dan performa course.
10. **Manajemen Course (CRUD):** Form interaktif untuk membuat/mengedit course (judul, harga, deskripsi) dan form khusus untuk meng-upload materi (Video/PDF) per modul.

### E. Panel Admin (Admin Dashboard)
11. **Dashboard Admin:** Statistik keseluruhan *platform* (total user aktif, transaksi, dll).
12. **Tabel Manajemen Data:** Tampilan antarmuka tabel CRUD standar untuk mengelola User, Kategori, Transaksi, dan persetujuan Payout.

---

## 🚀 2. Workflow Menerapkan Desain ke Project Laravel

Proyek BelajarKUY menggunakan stack modern: **Laravel 12 + TailwindCSS v4 + Alpine.js**. Berikut adalah langkah teknis (*workflow*) untuk memindahkan desain dari Figma ke *codebase*:

### Langkah 1: Ekstrak Desain ke Komponen *Reusable* (Blade)
Desain halaman tidak boleh dibuat sebagai satu kesatuan kode yang panjang (monolitik).
*   **Layouts:** Pisahkan kerangka utama (contoh: Navbar + Area Konten + Footer) menjadi file layout seperti `resources/views/layouts/app.blade.php`.
*   **Components:** Identifikasi bagian desain yang diulang-ulang (seperti *Card Course*, *Tombol*, *Input Form*) dan buat menjadi Blade Components (contoh: `<x-course-card />`).

### Langkah 2: Gunakan Tailwind CSS untuk Styling (Tanpa Custom CSS)
**DILARANG keras menulis custom CSS** di file `app.css` kecuali sangat mendesak dan spesifik.
*   Terjemahkan desain UI secara langsung ke elemen HTML menggunakan *utility classes* bawaan TailwindCSS.
*   *Contoh:* Untuk tombol berwarna biru melengkung: `<button class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2">Tombol</button>`.

### Langkah 3: Gunakan Alpine.js untuk Interaktivitas UI
Jika desain memuat elemen interaktif seperti **Dropdown, Modal (Pop-up), Tab, Accordion, atau Sidebar Toggle**, JANGAN gunakan jQuery. Gunakan **Alpine.js**.
*   *Contoh implementasi Dropdown dengan Alpine.js:*
    ```html
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="btn">Buka Menu</button>
        <div x-show="open" @click.away="open = false" class="absolute bg-white shadow-md">
            Isi Menu Dropdown
        </div>
    </div>
    ```

### Langkah 4: Manfaatkan AI untuk "Image to Code"
Untuk mempercepat konversi *wireframe/mockup* menjadi kode jadi:
1. Export/ambil *screenshot* dari desain UI yang sudah dibuat (misalnya di Figma).
2. Lampirkan gambar tersebut ke AI (Agent) dan berikan instruksi. *Contoh: "Tolong buatkan kode Laravel Blade dengan TailwindCSS dan Alpine.js untuk desain halaman ini."*
3. Salin (*copy*) kode yang dihasilkan AI ke dalam file `.blade.php` di dalam proyek.

---
*Dokumen ini dibuat untuk menjadi panduan tim UI/UX (Quinsha Ilmi) dan Frontend (Vascha U).*
