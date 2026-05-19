# 🤖 PROMPT: Generate All Database Migrations

> Copy-paste prompt ini ke AI agent untuk membuat semua migration files.
> **PIC: Yosua Valentino**

---

## PROMPT

```
Kamu adalah senior Laravel developer. Buat SEMUA migration files untuk project BelajarKUY (Udemy clone Indonesia) menggunakan Laravel 12.

## CONTEXT:
- Project: BelajarKUY — platform e-learning Indonesia
- Framework: Laravel 12
- Database: MySQL
- Payment: Midtrans (bukan Stripe)
- 3 user roles: user, instructor, admin

## INSTRUKSI:

Buat migration files berikut dalam URUTAN ini (sesuai dependensi FK):

### 1. Modifikasi create_users_table (sudah ada dari Breeze)
Tambahkan kolom:
- role: enum('user', 'instructor', 'admin'), default 'user'
- photo: varchar(255), nullable
- phone: varchar(20), nullable
- address: text, nullable
- bio: text, nullable
- website: varchar(255), nullable

### 2. create_categories_table
- id (bigint, PK, AI)
- name (varchar 255, NOT NULL)
- slug (varchar 255, UNIQUE)
- image (varchar 255, nullable)
- status (boolean, default true)
- timestamps

### 3. create_sub_categories_table
- id (bigint, PK, AI)
- category_id (FK → categories.id, CASCADE)
- name (varchar 255, NOT NULL)
- slug (varchar 255, UNIQUE)
- timestamps

### 4. create_sliders_table
- id, title, description (text), image, button_text, button_url, status (boolean), sort_order (int), timestamps

### 5. create_info_boxes_table
- id, title, description (text), icon (varchar), sort_order (int), timestamps

### 6. create_partners_table
- id, name, image, status (boolean), sort_order (int), timestamps

### 7. create_site_infos_table
- id, key (varchar 255, UNIQUE), value (text), timestamps

### 8. REMOVED — midtrans_configs table
- DIHAPUS — API keys disimpan di .env, bukan database (security risk)

### 9. create_courses_table
- id (bigint, PK)
- category_id (FK → categories.id, CASCADE)
- subcategory_id (FK → sub_categories.id, SET NULL, nullable)
- instructor_id (FK → users.id, CASCADE)
- title (varchar 255)
- slug (varchar 255, UNIQUE)
- description (text, nullable)
- price (decimal 12,2, default 0)
- discount (tinyint unsigned, default 0)
- thumbnail (varchar 255, nullable)
- video_url (varchar 255, nullable)
- duration (varchar 50, nullable)
- bestseller (boolean, default false)
- featured (boolean, default false)
- status (enum: draft, pending_review, active, inactive, default 'draft')
- timestamps

### 10. create_course_goals_table
- id, course_id (FK CASCADE), goal (varchar 255), timestamps

### 11. create_course_sections_table
- id, course_id (FK CASCADE), title (varchar 255), sort_order (int unsigned, default 0), timestamps

### 12. create_course_lectures_table
- id, section_id (FK → course_sections.id, CASCADE), title, url (varchar 500, nullable), content (text, nullable), duration (varchar 50, nullable), sort_order (int unsigned), timestamps

### 13. create_wishlists_table
- id, user_id (FK CASCADE), course_id (FK CASCADE), timestamps
- UNIQUE constraint pada (user_id, course_id)

### 14. create_carts_table (SIMPLIFIED)
- id, user_id (FK CASCADE), course_id (FK CASCADE), timestamps
- UNIQUE constraint pada (user_id, course_id)
- NOTE: TIDAK ada price atau instructor_id — dihitung real-time dari courses table

### 15. create_coupons_table (ENHANCED)
- id, instructor_id (FK → users.id, CASCADE), course_id (FK → courses.id, SET NULL, nullable), code (varchar 50, UNIQUE), discount_percent (int unsigned), valid_until (date), max_usage (int unsigned, nullable), used_count (int unsigned, default 0), status (boolean, default true), timestamps

### 16. create_payments_table
- id, user_id (FK CASCADE), midtrans_order_id (varchar 100, UNIQUE), midtrans_transaction_id (varchar 100, nullable), payment_type (varchar 50, nullable), total_amount (decimal 12,2), status (enum: pending, settlement, capture, deny, cancel, expire, failure, refund, default 'pending'), midtrans_response (json, nullable), timestamps

### 17. create_orders_table (ENHANCED)
- id, payment_id (FK → payments.id, CASCADE), user_id (FK CASCADE), course_id (FK CASCADE), instructor_id (FK → users.id, CASCADE), coupon_id (FK → coupons.id, SET NULL, nullable), original_price (decimal 12,2), discount_amount (decimal 12,2, default 0), final_price (decimal 12,2), status (enum: pending, completed, cancelled, refunded, default 'pending'), timestamps

### 18. create_reviews_table
- id, user_id (FK CASCADE), course_id (FK CASCADE), rating (tinyint unsigned, 1-5), comment (text, nullable), status (boolean, default true), timestamps
- UNIQUE constraint pada (user_id, course_id)

### 19. create_enrollments_table (✨ NEW)
- id, user_id (FK CASCADE), course_id (FK CASCADE), order_id (FK → orders.id, CASCADE), enrolled_at (timestamp)
- UNIQUE constraint pada (user_id, course_id)

### 20. create_lecture_completions_table (✨ NEW)
- id, user_id (FK CASCADE), lecture_id (FK → course_lectures.id, CASCADE), completed_at (timestamp)
- UNIQUE constraint pada (user_id, lecture_id)

## CONSTRAINT:
- Gunakan anonymous class migration (Laravel 12 style)
- Semua FK harus eksplisit (constrained + cascadeOnDelete / nullOnDelete)
- Gunakan `$table->id()` untuk primary key
- Gunakan `$table->foreignId()` untuk foreign keys
- Timestamp semua file harus berurutan agar Laravel migrate dalam urutan yang benar
- JANGAN buat migration untuk tabel yang sudah ada dari Breeze (users, cache, jobs, sessions, password_reset_tokens) — KECUALI modifikasi users table

## OUTPUT:
Generate setiap migration sebagai file PHP terpisah dengan nama file yang benar.
```

---

*Setelah generate, jalankan `php artisan migrate` untuk verifikasi.*
