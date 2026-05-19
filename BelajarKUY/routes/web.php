<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Backend\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============================================================
// AUTH — Google OAuth (Socialite)
// ============================================================

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google-callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// ============================================================
// AUTHENTICATED — Shared Profile Routes
// ============================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Generic /dashboard → redirect ke dashboard sesuai role
    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            default      => redirect()->route('student.dashboard'),
        };
    })->name('dashboard');
});

// ============================================================
// STUDENT (role: 'user') — F09 Student Panel
// PIC: Vascha U
// ============================================================

Route::middleware(['auth', 'role:user'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        // TODO: enrolled courses, profile, progress — Phase P9
    });

// ============================================================
// INSTRUCTOR (role: 'instructor') — F08 Instructor Panel
// PIC: Albariqi Tarigan
// ============================================================

Route::middleware(['auth', 'role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
        // TODO: course CRUD, section/lecture CRUD — Phase P5, P6
    });

// ============================================================
// ADMIN (role: 'admin') — F07 Admin Panel
// PIC: Quinsha Ilmi
// ============================================================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // TODO: category, course, user, order, CMS management — Phase P12
    });

require __DIR__.'/auth.php';

