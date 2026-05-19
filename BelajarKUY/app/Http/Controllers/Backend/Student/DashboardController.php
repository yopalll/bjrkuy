<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\LectureCompletion;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Student dashboard.
     * Shows student-specific: enrolled courses, recent activity, progress.
     * Enrollment checked via enrollments table — ADR schema v2.
     */
    public function index(): View
    {
        $student = Auth::user();

        // Enrollment check via enrollments table (NOT orders — see AGENT_GUIDELINES section 5.3)
        $enrollments = Enrollment::where('user_id', $student->id)
                                  ->with(['course.instructor', 'course.category'])
                                  ->latest()
                                  ->take(5)
                                  ->get();

        $stats = [
            'total_enrolled' => Enrollment::where('user_id', $student->id)->count(),
            'total_completed_lectures' => LectureCompletion::where('user_id', $student->id)->count(),
        ];

        return view('backend.student.dashboard', compact('stats', 'enrollments'));
    }
}
