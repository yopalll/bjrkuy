<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseDetailController extends Controller
{
    public function show($slug)
    {
        // 1. Fetch course with necessary relationships
        $course = Course::where('slug', $slug)
            ->active()
            ->with([
                'category', 
                'subCategory', 
                'instructor', 
                'sections.lectures', 
                'goals', 
                'reviews' => function($q) {
                    $q->where('status', true)->latest();
                },
                'reviews.user'
            ])
            ->firstOrFail();

        // 2. Fetch related courses (same category, excluding this one)
        $relatedCourses = Course::active()
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->with(['category', 'instructor', 'reviews'])
            ->take(4)
            ->get();

        // 3. Business rule checks for review eligibility
        $showReviewForm = false;
        if (auth()->check()) {
            $canReview = \App\Models\Order::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->where('status', 'completed')
                ->exists();

            $alreadyReviewed = \App\Models\Review::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->exists();

            $showReviewForm = $canReview && !$alreadyReviewed;
        }

        return view('frontend.course-detail', compact('course', 'relatedCourses', 'showReviewForm'));
    }

    /**
     * Store student review for a course.
     */
    public function storeReview(Request $request, Course $course)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Double check business rules
        $canReview = \App\Models\Order::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->exists();

        if (!$canReview) {
            return redirect()->back()->with('error', 'Anda harus membeli kursus ini terlebih dahulu sebelum memberikan ulasan.');
        }

        $alreadyReviewed = \App\Models\Review::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk kursus ini.');
        }

        \App\Models\Review::create([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => false, // Pending admin approval by default
        ]);

        return redirect()->back()->with('success', 'Ulasan Anda berhasil dikirim dan menunggu persetujuan admin.');
    }
}
