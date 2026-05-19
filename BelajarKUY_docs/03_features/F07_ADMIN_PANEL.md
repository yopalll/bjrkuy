# 🛡️ F07: Admin Panel

> Dashboard dan management panel untuk administrator.
> **Scope:** Sesuai ADR-005 (no payout) dan ADR-006 (no instructor approval).

---

## Halaman Admin

| # | Halaman | Route | Deskripsi |
|---|---------|-------|-----------|
| 1 | Dashboard | `/admin/dashboard` | Stats overview: total users, courses, orders, revenue |
| 2 | Category Mgmt | `/admin/category` | CRUD categories + images (Cloudinary upload) |
| 3 | SubCategory Mgmt | `/admin/subcategory` | CRUD sub-categories |
| 4 | Course Mgmt | `/admin/course` | List semua kursus, **approve/reject** (pending_review → active/inactive) |
| 5 | Instructor List | `/admin/instructor` | **View only** — list instructor + statistik (ADR-006) |
| 6 | Order Mgmt | `/admin/order` | List orders, filter by status, detail view |
| 7 | User List | `/admin/user` | List students (role=user) + view detail |
| 8 | Slider Mgmt | `/admin/slider` | CRUD hero slider (Cloudinary) |
| 9 | Info Box Mgmt | `/admin/info-box` | CRUD value proposition boxes |
| 10 | Partner Mgmt | `/admin/partner` | CRUD partner logos (Cloudinary) |
| 11 | Site Settings | `/admin/site-setting` | Logo, contact info, social media (key-value pairs) |
| 12 | Review Mgmt | `/admin/reviews` | Approve/reject reviews |
| 13 | Profile | `/admin/profile` | Edit admin profile |

---

## ⚠️ HALAMAN YANG DIHAPUS (Scope Decisions)

Sebelumnya ada halaman ini, **sekarang dihapus**:

| ❌ Dihapus | Alasan |
|------------|--------|
| Mail Settings UI (`/admin/mail-setting`) | Credentials di `.env`, bukan DB. Edit via server, bukan UI. |
| Midtrans Settings UI (`/admin/midtrans-setting`) | Credentials di `.env`. Hardcoded sandbox (ADR-004). |
| Google OAuth Settings UI (`/admin/google-setting`) | Credentials di `.env`. |
| Cloudinary Settings UI | Credentials di `.env`. |
| Instructor Approve/Block button | ADR-006 — instructor auto-active. Tidak ada approval flow. |
| Payout Management | ADR-005 — out of scope untuk MVP. |

**Rationale:** Menyimpan API credentials di DB bisa diedit via UI = security anti-pattern. Untuk project akademik dengan 1 deploy target, edit `.env` + restart cukup.

---

## Dashboard Stats

```php
// AdminDashboardController@index
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Enrollment;

public function index(): View
{
    $stats = [
        'total_students'     => User::students()->count(),
        'total_instructors'  => User::instructors()->count(),
        'total_courses'      => Course::count(),
        'active_courses'     => Course::active()->count(),
        'pending_review'     => Course::where('status', 'pending_review')->count(),
        'total_enrollments'  => Enrollment::count(),
        'total_completed_orders' => Order::completed()->count(),
        'gross_revenue'      => Payment::whereIn('status', ['settlement', 'capture'])->sum('total_amount'),
        'this_month_revenue' => Payment::whereIn('status', ['settlement', 'capture'])
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount'),
    ];

    // Recent activity
    $recentOrders = Order::with(['user', 'course'])
        ->completed()
        ->latest()
        ->take(10)
        ->get();

    $recentStudents = User::students()
        ->latest()
        ->take(5)
        ->get();

    return view('backend.admin.dashboard', compact('stats', 'recentOrders', 'recentStudents'));
}
```

---

## Course Approval Flow (Main Moderation Point)

```
Instructor buat course (status=draft)
    ↓
Instructor klik "Submit for Review"
    ↓
Status → pending_review
    ↓
Admin review di /admin/course
    ↓
  [Approve]              [Reject]
    ↓                      ↓
status=active         status=inactive
Email ke instructor   Email ke instructor (+ alasan)
```

```php
// AdminCourseController
public function approve(Course $course): RedirectResponse
{
    $course->update(['status' => 'active']);

    Mail::to($course->instructor)->queue(new CourseApprovedMail($course));

    Log::info('Course approved', [
        'course_id' => $course->id,
        'admin_id' => auth()->id(),
    ]);

    return back()->with('success', 'Kursus disetujui dan sudah tayang.');
}

public function reject(Course $course, Request $request): RedirectResponse
{
    $reason = $request->validate([
        'rejection_reason' => 'required|string|max:500',
    ])['rejection_reason'];

    $course->update(['status' => 'inactive']);

    Mail::to($course->instructor)->queue(new CourseRejectedMail($course, $reason));

    return back()->with('success', 'Kursus ditolak.');
}
```

---

## Instructor List (View-Only, ADR-006)

Admin bisa lihat instructor + statistiknya, **tapi tidak ada tombol Approve/Block**.

```php
// AdminInstructorController@index
public function index(): View
{
    $instructors = User::instructors()
        ->withCount(['courses', 'coupons'])
        ->withSum('courses as gross_revenue', 'price')  // rough estimate
        ->latest()
        ->paginate(15);

    return view('backend.admin.instructor.index', compact('instructors'));
}
```

Tabel columns: Photo, Name, Email, # Courses, # Coupons, Join Date, **Action: [View Detail]** (no approve/block).

---

## Media Upload (Cloudinary)

Semua upload (slider, info_box, partner, category image) pakai Cloudinary:

```php
// AdminCategoryController@store
public function store(StoreCategoryRequest $request): RedirectResponse
{
    $data = $request->validated();

    if ($request->hasFile('image')) {
        $result = $request->file('image')->storeOnCloudinary('belajarkuy/categories');
        $data['image'] = $result->getSecurePath();
    }

    Category::create($data);

    return redirect()->route('admin.category.index')
        ->with('success', 'Kategori berhasil dibuat!');
}
```

---

## UI Design

- **Sidebar gelap** (`bg-slate-900`) dengan menu group berdasarkan kategori
- **Content area putih** dengan card-based layout
- **Stats cards** di dashboard dengan icon + angka besar
- **Tables dengan pagination** (15 per page default)
- **SweetAlert2** untuk confirm delete
- **Toast notification** untuk flash messages

---

## PIC: Quinsha Ilmi & Vascha U (UI/UX)
