<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubCategoryRequest;
use App\Http\Requests\Admin\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the sub-categories.
     */
    public function index()
    {
        $subCategories = SubCategory::with('category')->latest()->paginate(15);
        
        return view('admin.sub_categories.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new sub-category.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.sub_categories.create', compact('categories'));
    }

    /**
     * Store a newly created sub-category in storage.
     */
    public function store(StoreSubCategoryRequest $request)
    {
        $validated = $request->validated();
        
        SubCategory::create($validated);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'Sub-Category created successfully.');
    }

    /**
     * Show the form for editing the specified sub-category.
     */
    public function edit(SubCategory $subCategory)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.sub_categories.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified sub-category in storage.
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        $validated = $request->validated();

        $subCategory->update($validated);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'Sub-Category updated successfully.');
    }

    /**
     * Remove the specified sub-category from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'Sub-Category deleted successfully.');
    }
}
