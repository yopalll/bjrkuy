<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminInstructorController extends Controller
{
    /**
     * Display a listing of instructors.
     */
    public function index()
    {
        $instructors = User::instructors()
            ->withCount(['courses', 'coupons'])
            ->latest()
            ->paginate(15);
            
        return view('admin.instructors.index', compact('instructors'));
    }

    /**
     * Display the specified instructor details.
     */
    public function show(User $instructor)
    {
        // Ensure the user is actually an instructor
        abort_if(!$instructor->isInstructor(), 404);

        $instructor->loadCount(['courses', 'coupons']);

        // Fetch their courses for the detailed view
        $courses = $instructor->courses()->with('category')->latest()->paginate(10);
        
        return view('admin.instructors.show', compact('instructor', 'courses'));
    }
}
