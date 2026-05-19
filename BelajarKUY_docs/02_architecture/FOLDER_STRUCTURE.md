# 🗂️ BelajarKUY — Folder Structure (Laravel 12)

> Struktur folder lengkap project BelajarKUY.

---

```
BelajarKUY/
│
├── app/
│   ├── Console/
│   │   └── Commands/                   # Custom artisan commands
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php          # Base controller
│   │   │   ├── SocialController.php    # Google OAuth
│   │   │   │
│   │   │   ├── Frontend/
│   │   │   │   ├── HomeController.php              # Landing page
│   │   │   │   ├── CourseDetailController.php       # Detail kursus
│   │   │   │   ├── CartController.php               # Keranjang
│   │   │   │   ├── CheckoutController.php           # Checkout + Midtrans
│   │   │   │   ├── WishlistController.php           # Wishlist
│   │   │   │   └── SearchController.php             # Search (Meilisearch)
│   │   │   │
│   │   │   ├── Backend/
│   │   │   │   ├── Admin/
│   │   │   │   │   ├── DashboardController.php      # Admin dashboard
│   │   │   │   │   ├── CategoryController.php       # CRUD kategori
│   │   │   │   │   ├── SubcategoryController.php    # CRUD sub-kategori
│   │   │   │   │   ├── CourseController.php          # Manage courses
│   │   │   │   │   ├── OrderController.php           # Manage orders
│   │   │   │   │   ├── UserController.php            # Manage users
│   │   │   │   │   ├── InstructorController.php      # Manage instructors
│   │   │   │   │   ├── SliderController.php          # CRUD slider
│   │   │   │   │   ├── InfoBoxController.php         # CRUD info box
│   │   │   │   │   ├── PartnerController.php         # CRUD partner
│   │   │   │   │   ├── SettingController.php         # SMTP, Midtrans, Google
│   │   │   │   │   ├── SiteSettingController.php     # Site info
│   │   │   │   │   └── ProfileController.php         # Admin profile
│   │   │   │   │
│   │   │   │   ├── Instructor/
│   │   │   │   │   ├── DashboardController.php
│   │   │   │   │   ├── CourseController.php
│   │   │   │   │   ├── SectionController.php
│   │   │   │   │   ├── LectureController.php
│   │   │   │   │   ├── CouponController.php
│   │   │   │   │   └── ProfileController.php
│   │   │   │   │
│   │   │   │   └── Student/
│   │   │   │       ├── DashboardController.php
│   │   │   │       ├── ProfileController.php
│   │   │   │       └── WishlistController.php
│   │   │   │
│   │   │   └── Auth/                   # Laravel Breeze generated
│   │   │       ├── AuthenticatedSessionController.php
│   │   │       ├── RegisteredUserController.php
│   │   │       ├── PasswordResetLinkController.php
│   │   │       └── ... (other auth controllers)
│   │   │
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php      # Multi-role check
│   │   │
│   │   └── Requests/
│   │       ├── StoreCourseRequest.php
│   │       ├── UpdateCourseRequest.php
│   │       ├── StoreCategoryRequest.php
│   │       ├── StoreCouponRequest.php
│   │       └── ... (other form requests)
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── SubCategory.php
│   │   ├── Course.php
│   │   ├── CourseGoal.php
│   │   ├── CourseSection.php
│   │   ├── CourseLecture.php
│   │   ├── Wishlist.php
│   │   ├── Cart.php
│   │   ├── Coupon.php
│   │   ├── Payment.php
│   │   ├── Order.php
│   │   ├── Review.php
│   │   ├── Enrollment.php
│   │   ├── LectureCompletion.php
│   │   ├── Slider.php
│   │   ├── InfoBox.php
│   │   ├── Partner.php
│   │   └── SiteInfo.php
│   │
│   ├── Helpers/
│   │   └── helpers.php                 # Global helper functions
│   │
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   │
│   └── Services/
│       ├── MidtransService.php         # Midtrans payment logic
│       └── CloudinaryService.php       # Cloudinary upload helper
│
│   ├── Events/
│   │   └── PaymentSuccessful.php       # Broadcast payment success
│
│   ├── Mail/
│   │   ├── OrderConfirmationMail.php   # Email konfirmasi pembelian
│   │   ├── WelcomeMail.php             # Email selamat datang
│   │   ├── NewSaleNotification.php     # Notif ke instructor
│   │   ├── CourseApprovedMail.php      # Kursus disetujui
│   │   └── CourseRejectedMail.php      # Kursus ditolak
│
├── bootstrap/
│   └── app.php                         # Register middleware di sini
│
├── config/
│   ├── midtrans.php                    # Midtrans config
│   ├── services.php                    # Google OAuth config
│   └── ... (default Laravel configs)
│
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── CategoryFactory.php
│   │   ├── CourseFactory.php
│   │   └── ... (other factories)
│   │
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── xxxx_create_categories_table.php
│   │   ├── xxxx_create_courses_table.php
│   │   └── ... (~20 migrations)
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── CategorySeeder.php
│       ├── AdminSeeder.php
│       └── ... (other seeders)
│
├── public/
│   └── build/                          # Vite compiled assets
│   # NOTE: Media (gambar, thumbnail, foto profil) disimpan di Cloudinary (lihat MODERN_TECH_STACK_RECOMMENDATIONS.md)
│   # public/uploads/ TIDAK DIPAKAI — hanya public/images/ untuk static assets brand
│
├── resources/
│   ├── css/
│   │   └── app.css                     # Tailwind imports
│   │
│   ├── js/
│   │   ├── app.js                      # Main JS (Alpine, Echo, Reverb)
│   │   ├── echo.js                     # Laravel Echo setup (WebSocket)
│   │   └── midtrans.js                 # Midtrans Snap handler
│   │
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php           # Main public layout
│       │   ├── admin.blade.php         # Admin panel layout
│       │   └── instructor.blade.php    # Instructor panel layout
│       │
│       ├── components/
│       │   ├── navbar.blade.php
│       │   ├── footer.blade.php
│       │   ├── sidebar.blade.php
│       │   ├── course-card.blade.php
│       │   ├── category-card.blade.php
│       │   ├── alert.blade.php
│       │   └── pagination.blade.php
│       │
│       ├── frontend/
│       │   ├── home.blade.php
│       │   ├── course-detail.blade.php
│       │   ├── cart.blade.php
│       │   ├── checkout.blade.php
│       │   └── partials/
│       │       ├── hero-slider.blade.php
│       │       ├── featured-courses.blade.php
│       │       ├── categories-section.blade.php
│       │       ├── info-boxes.blade.php
│       │       └── partners-section.blade.php
│       │
│       ├── backend/
│       │   ├── admin/
│       │   │   ├── dashboard.blade.php
│       │   │   ├── category/
│       │   │   ├── course/
│       │   │   ├── order/
│       │   │   ├── user/
│       │   │   ├── slider/
│       │   │   ├── setting/
│       │   │   └── profile/
│       │   │
│       │   ├── instructor/
│       │   │   ├── dashboard.blade.php
│       │   │   ├── course/
│       │   │   ├── section/
│       │   │   ├── coupon/
│       │   │   └── profile/
│       │   │
│       │   └── student/
│       │       ├── dashboard.blade.php
│       │       ├── enrolled-courses.blade.php
│       │       ├── wishlist.blade.php
│       │       └── profile/
│       │
│       └── auth/
│           ├── login.blade.php
│           ├── register.blade.php
│           ├── forgot-password.blade.php
│           └── reset-password.blade.php
│
├── routes/
│   ├── web.php                         # ALL web routes
│   ├── auth.php                        # Breeze auth routes
│   └── api.php                         # API routes (if needed)
│
├── storage/                            # Laravel storage
├── tests/                              # PHPUnit / Pest tests
│
├── BelajarKUY_docs/                    # Folder dokumentasi proyek
│
├── .env                                # Environment variables
├── .env.example                        # Template environment
├── .gitignore
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
└── README.md
```

---

*Ikuti struktur folder ini secara konsisten. Jangan membuat folder baru tanpa alasan yang jelas.*
