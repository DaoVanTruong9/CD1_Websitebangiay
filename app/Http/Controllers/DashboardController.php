<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{

public function index()
{
    // ====== TỔNG QUAN ======
    $totalOrders = \App\Models\Order::count();
    $totalProducts = \App\Models\Product::count();
    $totalCustomers = \App\Models\User::where('role', 'user')->count();

    $revenue = \App\Models\Order::where('status', 'completed')
        ->where('payment_status', 'paid')
        ->sum('total_price');

    // ====== HÔM NAY ======
    $todayOrders = \App\Models\Order::whereDate('created_at', today())->count();

    $todayRevenue = \App\Models\Order::whereDate('created_at', today())
        ->where('status', 'completed')
        ->where('payment_status', 'paid')
        ->sum('total_price');

    // ====== THEO THÁNG (CHART) ======
    $monthlyRevenue = \App\Models\Order::where('status', 'completed')
        ->where('payment_status', 'paid')
        ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    // ====== TOP SẢN PHẨM ======
    $topProducts = \App\Models\OrderItem::selectRaw('product_id, SUM(quantity) as total_sold')
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->with('product')
        ->take(5)
        ->get();

    // ====== ĐƠN GẦN NHẤT ======
    $latestOrders = \App\Models\Order::latest()->take(5)->get();

    return view('admin.dashboard', compact(
        'totalOrders',
        'totalProducts',
        'totalCustomers',
        'revenue',
        'todayOrders',
        'todayRevenue',
        'monthlyRevenue',
        'topProducts',
        'latestOrders'
    ));
}

}