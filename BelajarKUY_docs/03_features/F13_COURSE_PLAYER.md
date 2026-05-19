# 🎬 F13: Course Player (Watch Page)

> Halaman utama student untuk menonton materi course. Ini adalah **core LMS experience**.
> **Priority:** P10 (after Payment flow siap)

---

## Overview

Setelah student **enrolled** (punya row di `enrollments` table), mereka perlu halaman untuk:
1. Menonton lecture (video embed)
2. Navigasi antar section & lecture
3. Track progress belajar (mark lecture as complete)
4. Lihat persentase progress keseluruhan

Ini adalah halaman yang paling sering dibuka oleh student. Harus **cepat, clean, dan fokus**.

---

## URL Structure

```
/user/course/{course-slug}/watch              ← Auto-redirect ke first uncompleted lecture
/user/course/{course-slug}/watch/{lecture-id} ← Specific lecture
```

Alternatif URL (pilih salah satu, konsisten di codebase):
- `/user/learn/{course-slug}/{lecture-id}` — lebih pendek
- `/course/{slug}/watch/{lecture-id}` — tidak perlu prefix `/user/`

**Rekomendasi:** `/user/course/{slug}/watch[/{lecture-id}]` — konsisten dengan pattern lain di student panel.

---

## Layout (3-Column atau 2-Column)

```
┌────────────────────────────────────────────────────────────────────┐
│  Navbar BelajarKUY                                                 │
├──────────────────────────────────┬─────────────────────────────────┤
│                                   │                                 │
│  [VIDEO PLAYER]                   │  📚 Course Content              │
│  (YouTube embed / iframe)         │                                 │
│                                   │  Progress: 45%                  │
│                                   │  [████████░░░░░░░░░░]           │
│                                   │                                 │
│  Lecture 3: Laravel Routing       │  ▼ Bagian 1: Pengenalan        │
│  15:24 mins                       │    ✅ Lecture 1: Install        │
│                                   │    ✅ Lecture 2: Hello World   │
│  [✓ Tandai Selesai]               │    ▶ Lecture 3: Routing (now)  │
│                                   │    ⭕ Lecture 4: Controllers    │
│                                   │                                 │
│  ───── Tabs ─────                 │  ▼ Bagian 2: Database           │
│  [Deskripsi] [Catatan] [Tanya]    │    ⭕ Lecture 5: Migrations     │
│                                   │    ⭕ Lecture 6: Eloquent       │
│  Deskripsi lecture...             │                                 │
│                                   │                                 │
└──────────────────────────────────┴─────────────────────────────────┘
```

**Left (main):** Video player + info + tabs (Deskripsi / Catatan / Q&A)  
**Right (sidebar):** Progress bar + course curriculum (accordion)

---

## Controller

```php
// app/Http/Controllers/Frontend/CoursePlayerController.php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\Enrollment;
use App\Models\LectureCompletion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CoursePlayerController extends Controller
{
    /**
     * Entry point — auto-redirect ke first uncompleted lecture.
     */
    public function index(string $slug): RedirectResponse
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        // Must be enrolled
        abort_unless($this->isEnrolled($course->id), 403, 'Anda belum enroll kursus ini.');

        $firstUncompletedLecture = $this->findNextLecture($course);

        return redirect()->route('user.course.watch', [
            'slug' => $slug,
            'lecture' => $firstUncompletedLecture->id,
        ]);
    }

    /**
     * Show specific lecture.
     */
    public function show(string $slug, int $lectureId): View
    {
        $course = Course::where('slug', $slug)
            ->with(['sections.lectures', 'goals', 'instructor'])
            ->firstOrFail();

        abort_unless($this->isEnrolled($course->id), 403);

        $currentLecture = CourseLecture::whereHas('section', fn ($q) => $q->where('course_id', $course->id))
            ->findOrFail($lectureId);

        // Completed lecture IDs untuk sidebar (highlight)
        $completedLectureIds = LectureCompletion::where('user_id', auth()->id())
            ->whereIn('lecture_id', $course->sections->flatMap->lectures->pluck('id'))
            ->pluck('lecture_id');

        // Progress %
        $totalLectures = $course->sections->sum(fn ($s) => $s->lectures->count());
        $progress = $totalLectures > 0
            ? round(($completedLectureIds->count() / $totalLectures) * 100, 1)
            : 0;

        return view('frontend.course-player', compact(
            'course', 'currentLecture', 'completedLectureIds', 'progress'
        ));
    }

    /**
     * Mark lecture as complete (AJAX).
     */
    public function markComplete(int $lectureId)
    {
        $lecture = CourseLecture::with('section.course')->findOrFail($lectureId);
        $courseId = $lecture->section->course->id;

        abort_unless($this->isEnrolled($courseId), 403);

        LectureCompletion::firstOrCreate(
            ['user_id' => auth()->id(), 'lecture_id' => $lectureId],
            ['completed_at' => now()]
        );

        // Recalculate progress
        $totalLectures = CourseLecture::whereHas('section', fn ($q) => $q->where('course_id', $courseId))->count();
        $completedCount = LectureCompletion::where('user_id', auth()->id())
            ->whereIn('lecture_id', CourseLecture::whereHas('section', fn ($q) => $q->where('course_id', $courseId))->pluck('id'))
            ->count();
        $progress = $totalLectures > 0 ? round(($completedCount / $totalLectures) * 100, 1) : 0;

        // Find next lecture
        $course = Course::find($courseId);
        $nextLecture = $this->findNextLecture($course);

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'completed_count' => $completedCount,
            'total_lectures' => $totalLectures,
            'next_lecture_id' => $nextLecture?->id,
        ]);
    }

    // -------- Helpers --------

    private function isEnrolled(int $courseId): bool
    {
        return Enrollment::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->exists();
    }

    private function findNextLecture(Course $course): ?CourseLecture
    {
        $completedIds = LectureCompletion::where('user_id', auth()->id())
            ->pluck('lecture_id');

        // First lecture yang belum completed, ordered by section.sort_order + lecture.sort_order
        foreach ($course->sections()->orderBy('sort_order')->get() as $section) {
            foreach ($section->lectures()->orderBy('sort_order')->get() as $lecture) {
                if (!$completedIds->contains($lecture->id)) {
                    return $lecture;
                }
            }
        }

        // All done → return last lecture
        return $course->sections->flatMap->lectures->last();
    }
}
```

---

## Routes

Tambahkan di `routes/web.php`:

```php
Route::middleware(['auth', 'verified', 'role:student'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Course Player
        Route::get('/course/{slug}/watch', [CoursePlayerController::class, 'index'])
            ->name('course.watch.entry');

        Route::get('/course/{slug}/watch/{lecture}', [CoursePlayerController::class, 'show'])
            ->name('course.watch');

        Route::post('/lecture/{lecture}/complete', [CoursePlayerController::class, 'markComplete'])
            ->name('lecture.complete');
    });
```

**Catatan role:** Pakai `role:student` untuk alias yang lebih natural (lihat ADR-007). Jika masih pakai `role:user`, sesuaikan.

---

## Video Embed Pattern

Lecture URL di `course_lectures.url` bisa berupa:
- YouTube link (mayoritas)
- Vimeo link (optional)

```blade
{{-- resources/views/frontend/course-player.blade.php --}}

@php
    // Extract YouTube video ID
    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|watch\?v=))([^&\?]+)/', $currentLecture->url, $matches);
    $youtubeId = $matches[1] ?? null;
@endphp

<div class="aspect-video bg-black rounded-lg overflow-hidden">
    @if($youtubeId)
        <iframe 
            src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1"
            class="w-full h-full"
            frameborder="0"
            allowfullscreen></iframe>
    @else
        <div class="flex items-center justify-center h-full text-white">
            <p>Video tidak tersedia.</p>
        </div>
    @endif
</div>
```

---

## Sidebar Curriculum (Alpine.js accordion)

```blade
<aside class="w-96 bg-white border-l overflow-y-auto">
    {{-- Progress bar --}}
    <div class="p-4 border-b bg-indigo-50">
        <div class="flex justify-between mb-2">
            <span class="font-medium">Progress Belajar</span>
            <span class="text-indigo-600 font-bold">{{ $progress }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
        </div>
    </div>

    {{-- Sections accordion --}}
    @foreach($course->sections as $section)
        <div x-data="{ open: {{ $section->lectures->pluck('id')->contains($currentLecture->id) ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center border-b hover:bg-gray-50">
                <span class="font-medium">{{ $section->title }}</span>
                <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform">...</svg>
            </button>

            <div x-show="open" x-collapse>
                @foreach($section->lectures as $lecture)
                    @php $isCompleted = $completedLectureIds->contains($lecture->id); @endphp
                    @php $isActive = $lecture->id === $currentLecture->id; @endphp

                    <a href="{{ route('user.course.watch', ['slug' => $course->slug, 'lecture' => $lecture->id]) }}"
                       class="flex items-center gap-3 p-3 pl-6 border-b {{ $isActive ? 'bg-indigo-50 border-l-4 border-indigo-600' : 'hover:bg-gray-50' }}">

                        {{-- Status icon --}}
                        @if($isCompleted)
                            <span class="text-green-500">✅</span>
                        @elseif($isActive)
                            <span class="text-indigo-600">▶</span>
                        @else
                            <span class="text-gray-300">⭕</span>
                        @endif

                        <div class="flex-1 text-sm">
                            <p class="{{ $isActive ? 'font-medium' : '' }}">{{ $lecture->title }}</p>
                            <p class="text-xs text-gray-500">{{ $lecture->duration }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</aside>
```

---

## Mark Complete (Alpine.js + AJAX)

```blade
<button
    x-data="{ loading: false, completed: {{ $completedLectureIds->contains($currentLecture->id) ? 'true' : 'false' }} }"
    @click="
        if (completed) return;
        loading = true;
        fetch('{{ route('user.lecture.complete', $currentLecture->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            completed = true;
            // Update progress UI...
            if (data.next_lecture_id) {
                // Auto-navigate (optional UX)
                // window.location.href = ...
            }
        })
        .finally(() => loading = false);
    "
    :disabled="loading || completed"
    class="w-full py-3 rounded-lg font-medium transition"
    :class="completed ? 'bg-green-500 text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white'"
>
    <span x-show="!completed">✓ Tandai Selesai</span>
    <span x-show="completed">✅ Sudah Selesai</span>
</button>
```

---

## Access Control

- Route ini **hanya untuk role `user` (student)** yang sudah **enrolled** ke course
- Middleware stack: `auth` + `verified` + `role:student`
- Controller cek `Enrollment::where(...)->exists()` — abort 403 jika tidak enrolled
- Admin dan Instructor **tidak** bisa akses halaman ini (mereka pakai preview di panel masing-masing)

---

## Edge Cases

| Scenario | Behavior |
|----------|----------|
| Course tidak ada | 404 |
| Student belum enrolled | 403 "Anda belum enroll kursus ini" |
| Lecture ID bukan milik course | 404 |
| Semua lecture sudah complete | Redirect ke lecture terakhir + banner "Selamat, kamu sudah menyelesaikan kursus!" |
| Student mark complete 2x | Idempotent (firstOrCreate) — return success, no duplicate |

---

## Future Enhancements (Out of MVP)

- Video progress tracking (resume dari detik terakhir) — butuh HTML5 video events
- Notes & bookmarks per lecture
- Q&A / comment per lecture
- Download materials (PDF, slides)
- Auto-play next lecture setelah complete

---

## PIC: Albariqi Tarigan (backend) + Vascha U (frontend)
