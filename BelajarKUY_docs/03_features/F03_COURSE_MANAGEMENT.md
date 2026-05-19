# 📚 F03: Course Management (Instructor)

> CRUD kursus oleh instructor.

---

## Fitur

1. **Create Course** — Title, description, category, subcategory, price, discount, thumbnail, video preview
2. **Edit Course** — Update semua field kursus
3. **Delete Course** — Soft delete / hard delete
4. **Course Goals** — Tambah/hapus learning objectives
5. **Course Sections** — Buat section/bab dalam kursus (orderable)
6. **Course Lectures** — Tambah lecture/video per section (orderable, URL-based)
7. **Course Status** — Draft → Submit for Review → (Admin approve) → Active
8. **Dynamic Subcategory** — Load subcategory berdasarkan category yang dipilih (AJAX)

---

## Tables Involved

- `courses` — Data kursus utama
- `course_goals` — Learning objectives
- `course_sections` — Bab/section
- `course_lectures` — Konten/lecture per section
- `categories` + `sub_categories` — Kategorisasi

---

## Business Rules

- Instructor hanya bisa manage kursusnya sendiri (`instructor_id = auth()->id()`)
- Harga dalam Rupiah (decimal 12,2)
- Discount dalam persen (0-100)
- Slug auto-generated dari title (unique)
- **Thumbnail upload ke Cloudinary** (`belajarkuy/courses` folder)
- Video URL = link YouTube/Vimeo (bukan upload file)

---

## PIC: Albariqi Tarigan
