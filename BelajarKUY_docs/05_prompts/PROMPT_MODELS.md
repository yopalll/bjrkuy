# 🤖 PROMPT: Generate All Eloquent Models

> Copy-paste prompt ini ke AI agent untuk membuat semua model.
> **PIC: Yosua Valentino**

---

## PROMPT

```
Kamu adalah senior Laravel 12 developer. Buat SEMUA Eloquent models untuk project BelajarKUY (Udemy clone Indonesia).

## CONTEXT:
- Baca file BelajarKUY_docs/02_architecture/DATABASE_SCHEMA.md untuk detail schema
- Baca file BelajarKUY_docs/01_guides/CODING_STANDARDS.md untuk konvensi kode

## INSTRUKSI:

Buat 19 model berikut di `app/Models/`:

### 1. User.php (modifikasi existing)
- fillable: name, email, password, role, photo, phone, address, bio, website
- casts: email_verified_at → datetime, password → hashed
- relationships: courses() hasMany Course (as instructor), wishlists() hasMany, carts() hasMany, orders() hasMany, reviews() hasMany, payments() hasMany
- scopes: scopeStudents, scopeInstructors, scopeAdmins

### 2. Category.php
- fillable: name, slug, image, status
- casts: status → boolean
- relationships: subCategories() hasMany, courses() hasMany
- scopes: scopeActive

### 3. SubCategory.php
- fillable: category_id, name, slug
- relationships: category() belongsTo

### 4. Course.php
- fillable: category_id, subcategory_id, instructor_id, title, slug, description, price, discount, thumbnail, video_url, duration, bestseller, featured, status
- casts: price → decimal:2, discount → integer, bestseller → boolean, featured → boolean
- traits: HasFactory, Searchable (Laravel Scout)
- relationships: category(), subCategory(), instructor() belongsTo User, sections() hasMany, goals() hasMany, wishlists() hasMany, carts() hasMany, orders() hasMany, reviews() hasMany, enrollments() hasMany
- scopes: scopeActive, scopeFeatured, scopeBestseller
- accessors: getDiscountedPriceAttribute, getAverageRatingAttribute
- toSearchableArray(): id, title, description, price, status, category_id, subcategory_id, bestseller, featured, instructor_name, category_name

### 5. CourseGoal.php
- fillable: course_id, goal
- relationships: course() belongsTo

### 6. CourseSection.php
- fillable: course_id, title, sort_order
- relationships: course() belongsTo, lectures() hasMany (default ordering by sort_order)

### 7. CourseLecture.php
- fillable: section_id, title, url, content, duration, sort_order
- relationships: section() belongsTo CourseSection, completions() hasMany LectureCompletion

### 8. Wishlist.php
- fillable: user_id, course_id
- relationships: user() belongsTo, course() belongsTo

### 9. Cart.php
- fillable: user_id, course_id
- relationships: user(), course() belongsTo
- NOTE: Harga dihitung real-time dari course.price, TIDAK disimpan di cart

### 10. Coupon.php
- fillable: instructor_id, course_id, code, discount_percent, valid_until, max_usage, used_count, status
- casts: valid_until → date, status → boolean
- relationships: instructor() belongsTo User, course() belongsTo Course (nullable)
- scopes: scopeActive (status=true AND valid_until >= today AND (max_usage IS NULL OR used_count < max_usage))

### 11. Payment.php
- fillable: user_id, midtrans_order_id, midtrans_transaction_id, payment_type, total_amount, status, midtrans_response
- casts: total_amount → decimal:2, midtrans_response → array
- relationships: user() belongsTo, orders() hasMany
- scopes: scopeCompleted (status in settlement, capture), scopePending

### 12. Order.php
- fillable: payment_id, user_id, course_id, instructor_id, coupon_id, original_price, discount_amount, final_price, status
- casts: original_price → decimal:2, discount_amount → decimal:2, final_price → decimal:2
- relationships: payment() belongsTo, user() belongsTo, course() belongsTo, instructor() belongsTo User, coupon() belongsTo Coupon (nullable), enrollment() hasOne
- scopes: scopeCompleted, scopePending

### 13. Review.php
- fillable: user_id, course_id, rating, comment, status
- casts: status → boolean
- relationships: user() belongsTo, course() belongsTo
- scopes: scopeApproved (status=true)

### 14. Slider.php
- fillable: title, description, image, button_text, button_url, status, sort_order
- casts: status → boolean

### 15. InfoBox.php
- fillable: title, description, icon, sort_order

### 16. Partner.php
- fillable: name, image, status, sort_order
- casts: status → boolean

### 17. SiteInfo.php
- fillable: key, value

### 18. Enrollment.php (✨ NEW)
- fillable: user_id, course_id, order_id, enrolled_at
- casts: enrolled_at → datetime
- `$timestamps = false` — hanya punya kolom enrolled_at, bukan created_at/updated_at
- relationships: user() belongsTo, course() belongsTo, order() belongsTo

### 19. LectureCompletion.php (✨ NEW)
- fillable: user_id, lecture_id, completed_at
- casts: completed_at → datetime
- `$timestamps = false` — hanya punya kolom completed_at, bukan created_at/updated_at
- relationships: user() belongsTo, lecture() belongsTo CourseLecture

## CONSTRAINT:
- Gunakan PHP 8.3 typed properties
- Gunakan return type declarations di semua relationships
- Gunakan HasFactory trait di semua models
- JANGAN buat custom primary keys — gunakan default 'id'
- Semua relationship methods harus di-declare return type (BelongsTo, HasMany, etc)
- Group methods dalam model: RELATIONSHIPS, SCOPES, ACCESSORS (dengan comment separator)

## OUTPUT:
Generate setiap model sebagai file PHP terpisah dengan namespace yang benar.
```

---

*Setelah generate, verifikasi relationships dengan `php artisan tinker`.*
