<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'course'])->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Update the status of the specified review.
     */
    public function updateStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status ulasan berhasil diperbarui.');
    }
}
