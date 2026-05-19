<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Order::with(['user', 'course', 'payment'])->latest();

        if ($status && in_array($status, ['pending', 'completed', 'cancelled', 'refunded'])) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'course', 'instructor', 'payment', 'coupon']);

        return view('admin.orders.show', compact('order'));
    }
}
