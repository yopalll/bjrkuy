<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\InfoBox;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing / home page.
     * Provides all variables required by frontend/home.blade.php.
     */
    public function index(Request $request)
    {
        $sliders   = Slider::where('status', true)->orderBy('sort_order')->get();
        $infoBoxes = InfoBox::orderBy('id')->get();
        $categories = Category::where('status', true)
            ->withCount(['courses' => fn($q) => $q->where('status', 'active')])
            ->orderByDesc('courses_count')
            ->take(8)
            ->get();

        $isSearchingOrFiltering = $request->filled('search') || $request->filled('category');

        if ($isSearchingOrFiltering) {
            $filteredCourses = Course::where('status', 'active')
                ->with(['instructor', 'category', 'reviews'])
                ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
                ->when($request->category, fn($q) => $q->whereHas('category', fn($c) => $c->where('slug', $request->category)))
                ->latest()
                ->get();
            $featuredCourses   = collect();
            $bestsellerCourses = collect();
        } else {
            $filteredCourses   = collect();
            $featuredCourses   = Course::where('status', 'active')
                ->where('featured', true)
                ->with(['instructor', 'category', 'reviews'])
                ->latest()
                ->take(8)
                ->get();
            $bestsellerCourses = Course::where('status', 'active')
                ->where('bestseller', true)
                ->withCount('enrollments')
                ->with(['instructor', 'category', 'reviews'])
                ->orderByDesc('enrollments_count')
                ->take(8)
                ->get();
        }

        return view('frontend.home', compact(
            'sliders',
            'infoBoxes',
            'categories',
            'featuredCourses',
            'bestsellerCourses',
            'filteredCourses',
            'isSearchingOrFiltering'
        ));
    }
}
