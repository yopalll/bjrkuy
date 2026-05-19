<?php

namespace App\Http\Controllers\Backend\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Instructor dashboard.
     * Shows instructor-specific stats: their courses, enrollments, gross revenue.
     */
    public function index(): View
    {
        $instructor = Auth::user();

        $courses = Course::where('instructor_id', $instructor->id)->withCount('enrollments')->get();

        $stats = [
            'total_courses'     => $courses->count(),
            'total_enrollments' => $courses->sum('enrollments_count'),
            // Gross revenue — ADR-005: no payout split
            'gross_revenue'     => Order::whereIn('course_id', $courses->pluck('id'))
                                        ->where('status', 'completed')
                                        ->sum('final_price'),
        ];

        return view('backend.instructor.dashboard', compact('stats', 'courses'));
    }
}
