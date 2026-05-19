<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CloudinaryService;

class CategoryController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinaryService)
    {
    }

    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(15);
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('image')) {
            try {
                $uploadResult = $this->cloudinaryService->uploadImage($request->file('image'), 'categories');
                $validated['image_url'] = $uploadResult['url'];
                $validated['image_public_id'] = $uploadResult['public_id'];
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Failed to upload image. Please try again.');
            }
        }
        
        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('image')) {
            try {
                $uploadResult = $this->cloudinaryService->replaceImage(
                    $request->file('image'),
                    $category->image_public_id,
                    'categories'
                );
                $validated['image_url'] = $uploadResult['url'];
                $validated['image_public_id'] = $uploadResult['public_id'];
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Failed to update image. Please try again.');
            }
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image_public_id) {
            try {
                $this->cloudinaryService->deleteImage($category->image_public_id);
            } catch (\Exception $e) {
                // Log exception if necessary, but proceed to delete category from DB
            }
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}