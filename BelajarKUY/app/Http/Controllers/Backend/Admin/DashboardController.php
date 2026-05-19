<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin dashboard.
     * Shows platform-wide stats: users, courses, orders, revenue.
     */
    public function index(): View
    {
        $stats = [
            'total_students'    => User::students()->count(),
            'total_instructors' => User::instructors()->count(),
            'total_courses'     => Course::count(),
            'total_orders'      => Order::where('status', 'completed')->count(),
        ];

        return view('backend.admin.dashboard', compact('stats'));
    }
}
