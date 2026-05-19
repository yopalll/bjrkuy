# 🎓 F09: Student Panel

> Dashboard dan halaman untuk student (user dengan role='user').
> **Terminology:** "Student" adalah business term; DB pakai `role='user'`. Lihat `01_guides/GLOSSARY.md`.

---

## Halaman Student

| # | Halaman | Route | Deskripsi |
|---|---------|-------|-----------|
| 1 | Dashboard | `/user/dashboard` | Overview: enrolled courses count, recent activity, progress |
| 2 | My Courses (enrolled) | `/user/my-courses` | List course yang sudah di-enroll |
| 3 | **Course Player** | `/user/course/{slug}/watch` | Watch page — lihat `F13_COURSE_PLAYER.md` |
| 4 | Wishlist | `/user/wishlist` | Course yang di-wishlist |
| 5 | Profile | `/user/profile` | Edit nama, foto, phone, address |
| 6 | Settings | `/user/setting` | Change password |

---

## Enrolled Courses Logic

⚠️ **Pakai tabel `enrollments`**, bukan query Order (lihat `GLOSSARY.md`):

```php
// StudentDashboardController@myCourses
public function myCourses(): View
{
    $enrollments = Enrollment::where('user_id', auth()->id())
        ->with(['course.instructor', 'course.sections.lectures'])
        ->latest('enrolled_at')
        ->paginate(12);

    // Calculate progress per course
    $enrollments->each(function ($enrollment) {
        $allLectureIds = $enrollment->course->sections
            ->flatMap(fn ($s) => $s->lectures)
            ->pluck('id');

        $completedCount = LectureCompletion::where('user_id', auth()->id())
            ->whereIn('lecture_id', $allLectureIds)
            ->count();

        $totalCount = $allLectureIds->count();

        $enrollment->progress = $totalCount > 0
            ? round(($completedCount / $totalCount) * 100, 1)
            : 0;

        $enrollment->completed_count = $completedCount;
        $enrollment->total_count = $totalCount;
    });

    return view('backend.student.my-courses', compact('enrollments'));
}
```

---

## Course Card in My Courses

Berbeda dari catalog course card, di sini tampilkan progress:

```blade
<div class="bg-white rounded-lg shadow overflow-hidden">
    <img src="{{ $enrollment->course->thumbnail }}" class="w-full h-40 object-cover">

    <div class="p-4">
        <h3 class="font-medium line-clamp-2">{{ $enrollment->course->title }}</h3>
        <p class="text-sm text-gray-500 mt-1">{{ $enrollment->course->instructor->name }}</p>

        {{-- Progress bar --}}
        <div class="mt-3">
            <div class="flex justify-between text-xs mb-1">
                <span>{{ $enrollment->completed_count }}/{{ $enrollment->total_count }} lectures</span>
                <span class="font-bold text-indigo-600">{{ $enrollment->progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full"
                     style="width: {{ $enrollment->progress }}%"></div>
            </div>
        </div>

        <a href="{{ route('user.course.watch.entry', $enrollment->course->slug) }}"
           class="mt-4 block w-full bg-indigo-600 text-white py-2 rounded text-center hover:bg-indigo-700">
            {{ $enrollment->progress > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar' }}
        </a>
    </div>
</div>
```

---

## Dashboard Widgets

```php
// StudentDashboardController@index
public function index(): View
{
    $userId = auth()->id();

    $stats = [
        'enrolled_count'   => Enrollment::where('user_id', $userId)->count(),
        'completed_lectures' => LectureCompletion::where('user_id', $userId)->count(),
        'wishlist_count'   => Wishlist::where('user_id', $userId)->count(),
    ];

    // Last watched course (dari lecture_completions terbaru)
    $lastCompletion = LectureCompletion::where('user_id', $userId)
        ->with('lecture.section.course.instructor')
        ->latest('completed_at')
        ->first();

    $continueCourse = $lastCompletion?->lecture?->section?->course;

    // Recent enrollments (5 terakhir)
    $recentEnrollments = Enrollment::where('user_id', $userId)
        ->with('course.instructor')
        ->latest('enrolled_at')
        ->take(5)
        ->get();

    return view('backend.student.dashboard', compact('stats', 'continueCourse', 'recentEnrollments'));
}
```

---

## Role Middleware Note

Student routes pakai middleware `role:user` (DB value) atau alias `role:student` (jika di-setup):

```php
// routes/web.php
Route::middleware(['auth', 'verified', 'role:user'])  // atau role:student
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Semua route student di sini
    });
```

Untuk alias yang lebih natural, bisa tambahkan di `RoleMiddleware`:

```php
// app/Http/Middleware/RoleMiddleware.php
public function handle(Request $request, Closure $next, string $role): Response
{
    // Alias: 'student' → 'user' (lihat ADR-007)
    $role = $role === 'student' ? 'user' : $role;

    if (!auth()->check() || auth()->user()->role !== $role) {
        abort(403);
    }

    return $next($request);
}
```

---

## PIC: Vascha U & Quinsha Ilmi (Frontend)
