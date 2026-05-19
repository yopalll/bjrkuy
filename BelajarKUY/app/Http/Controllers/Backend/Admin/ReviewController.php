<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Check if user is admin.
     */
    protected function checkAdmin()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display a listing of reviews.
     */
    public function index()
    {
        $this->checkAdmin();

        $reviews = Review::with(['user', 'course'])
            ->latest()
            ->paginate(10);

        return view('backend.admin.reviews.index', compact('reviews'));
    }

    /**
     * Approve the specified review.
     */
    public function approve(Review $review)
    {
        $this->checkAdmin();

        $review->update(['status' => true]);

        return redirect()->back()->with('success', 'Ulasan berhasil disetujui.');
    }

    /**
     * Reject/disapprove the specified review.
     */
    public function reject(Review $review)
    {
        $this->checkAdmin();

        $review->update(['status' => false]);

        return redirect()->back()->with('success', 'Ulasan berhasil dinonaktifkan.');
    }
}
