<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminInstructorController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminInfoBoxController;
use App\Http\Controllers\Admin\AdminPartnerController;
use App\Http\Controllers\Admin\AdminSiteSettingController;
use App\Http\Controllers\Admin\AdminReviewController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('sub-categories', SubCategoryController::class)->except(['show']);
    
    Route::resource('courses', AdminCourseController::class)->only(['index', 'show']);
    Route::patch('courses/{course}/status', [AdminCourseController::class, 'updateStatus'])->name('courses.update-status');

    Route::resource('instructors', AdminInstructorController::class)->only(['index', 'show']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
    Route::resource('users', AdminUserController::class)->only(['index']);
    Route::resource('sliders', AdminSliderController::class)->except(['show']);
    Route::resource('info-boxes', AdminInfoBoxController::class)->except(['show']);
    Route::resource('partners', AdminPartnerController::class)->except(['show']);
    
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.update-status');

    Route::get('settings', [AdminSiteSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google-callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

require __DIR__.'/auth.php';