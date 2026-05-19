<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\InfoBox;
use App\Models\Partner;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch sliders, info boxes, partners
        $sliders = Slider::where('status', true)->orderBy('sort_order')->get();
        $infoBoxes = InfoBox::orderBy('sort_order')->get();
        $partners = Partner::where('status', true)->orderBy('sort_order')->get();

        // 2. Fetch active categories with their active course count
        $categories = Category::active()
            ->withCount(['courses' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        // 3. Handle filters (search & category)
        $isSearchingOrFiltering = false;
        $coursesQuery = Course::active()->with(['category', 'instructor', 'reviews']);

        if ($request->filled('search')) {
            $isSearchingOrFiltering = true;
            $search = $request->search;
            $coursesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $isSearchingOrFiltering = true;
            $categorySlug = $request->category;
            $coursesQuery->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // 4. Fetch the courses based on filters
        if ($isSearchingOrFiltering) {
            $filteredCourses = $coursesQuery->latest()->get();
            $featuredCourses = collect();
            $bestsellerCourses = collect();
        } else {
            $filteredCourses = collect();
            // Fetch Featured and Bestsellers separately for standard homepage view
            $featuredCourses = Course::active()
                ->featured()
                ->with(['category', 'instructor', 'reviews'])
                ->latest()
                ->take(8)
                ->get();

            $bestsellerCourses = Course::active()
                ->bestseller()
                ->with(['category', 'instructor', 'reviews'])
                ->latest()
                ->take(8)
                ->get();
        }

        return view('frontend.home', compact(
            'sliders',
            'infoBoxes',
            'partners',
            'categories',
            'featuredCourses',
            'bestsellerCourses',
            'filteredCourses',
            'isSearchingOrFiltering'
        ));
    }
}
