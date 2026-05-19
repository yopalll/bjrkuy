<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\LectureCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard overview.
     */
    public function index()
    {
        $user = auth()->user();

        // Get enrollments count
        $enrollmentsCount = Enrollment::where('user_id', $user->id)->count();

        // Get wishlist count
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        // Get reviews count
        $reviewsCount = Review::where('user_id', $user->id)->count();

        // Get enrolled courses with progress
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course.instructor', 'course.sections.lectures'])
            ->latest('enrolled_at')
            ->take(3)
            ->get();

        $enrolledCoursesData = [];
        $totalCompletedLectures = 0;
        $totalLectures = 0;

        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            if (!$course) continue;

            $lectures = $course->sections->flatMap(function($section) {
                return $section->lectures;
            });
            $courseLecturesCount = $lectures->count();
            $totalLectures += $courseLecturesCount;

            $completedCount = 0;
            if ($courseLecturesCount > 0) {
                $lectureIds = $lectures->pluck('id')->toArray();
                $completedCount = LectureCompletion::where('user_id', $user->id)
                    ->whereIn('lecture_id', $lectureIds)
                    ->count();
                $totalCompletedLectures += $completedCount;
            }

            $progress = $courseLecturesCount > 0 ? round(($completedCount / $courseLecturesCount) * 100) : 0;

            $enrolledCoursesData[] = [
                'course' => $course,
                'progress' => $progress,
                'lectures_count' => $courseLecturesCount,
                'completed_count' => $completedCount,
                'enrolled_at' => $enrollment->enrolled_at,
            ];
        }

        $overallProgress = $totalLectures > 0 ? round(($totalCompletedLectures / $totalLectures) * 100) : 0;

        return view('backend.student.dashboard', compact(
            'user', 
            'enrollmentsCount', 
            'wishlistCount', 
            'reviewsCount', 
            'enrolledCoursesData', 
            'overallProgress'
        ));
    }

    /**
     * Display enrolled courses for the student.
     */
    public function myCourses()
    {
        $user = auth()->user();
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course.instructor', 'course.sections.lectures'])
            ->latest('enrolled_at')
            ->get();

        $enrolledCoursesData = [];

        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            if (!$course) continue;

            $lectures = $course->sections->flatMap(function($section) {
                return $section->lectures;
            });
            $courseLecturesCount = $lectures->count();

            $completedCount = 0;
            if ($courseLecturesCount > 0) {
                $lectureIds = $lectures->pluck('id')->toArray();
                $completedCount = LectureCompletion::where('user_id', $user->id)
                    ->whereIn('lecture_id', $lectureIds)
                    ->count();
            }

            $progress = $courseLecturesCount > 0 ? round(($completedCount / $courseLecturesCount) * 100) : 0;

            $enrolledCoursesData[] = [
                'course' => $course,
                'progress' => $progress,
                'lectures_count' => $courseLecturesCount,
                'completed_count' => $completedCount,
                'enrolled_at' => $enrollment->enrolled_at,
            ];
        }

        return view('backend.student.my_courses', compact('user', 'enrolledCoursesData'));
    }

    /**
     * Display wishlisted courses.
     */
    public function wishlist()
    {
        $user = auth()->user();
        $wishlists = Wishlist::where('user_id', $user->id)
            ->with(['course.instructor', 'course.category'])
            ->latest()
            ->get();

        return view('backend.student.wishlist', compact('user', 'wishlists'));
    }

    /**
     * Remove item from wishlist.
     */
    public function wishlistRemove($id)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $wishlist->delete();

        return redirect()->back()->with('success', 'Kursus berhasil dihapus dari daftar keinginan Anda.');
    }

    /**
     * Display edit profile page.
     */
    public function profile()
    {
        $user = auth()->user();
        return view('backend.student.profile', compact('user'));
    }

    /**
     * Handle profile update.
     */
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $data = $request->only(['name', 'phone', 'website', 'address', 'bio']);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store file in public/uploads/profile
            $file->move(public_path('uploads/profile'), $filename);
            
            // Delete old photo if it exists and is local
            if ($user->photo && !Str::startsWith($user->photo, 'http')) {
                $oldPath = public_path($user->photo);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['photo'] = 'uploads/profile/' . $filename;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Display settings page.
     */
    public function setting()
    {
        $user = auth()->user();
        return view('backend.student.setting', compact('user'));
    }

    /**
     * Handle password change.
     */
    public function settingUpdate(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
