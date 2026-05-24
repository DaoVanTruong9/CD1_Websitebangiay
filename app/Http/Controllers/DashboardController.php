<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

public function index()
{
    // ====== TỔNG QUAN ======
    $totalOrders = Order::count();

    $totalProducts = Product::count();

    $totalCustomers = User::where('role', 'user')->count();

    $revenue = Order::where('status', 'completed')
        ->where('payment_status', 'paid')
        ->sum('total_price');

    // ====== HÔM NAY ======
    $todayOrders = Order::whereDate('created_at', today())
        ->count();

    $todayRevenue = Order::whereDate('created_at', today())
        ->where('status', 'completed')
        ->where('payment_status', 'paid')
        ->sum('total_price');

    // ====== DOANH THU THEO THÁNG ======
    $monthlyRevenue = Order::where('status', 'completed')
        ->where('payment_status', 'paid')
        ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    // ====== TOP SẢN PHẨM ======
    $topProducts = OrderItem::selectRaw('product_id, SUM(quantity) as total_sold')
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->with('product')
        ->take(10)
        ->get();

    // ====== ĐƠN GẦN NHẤT ======
    $latestOrders = Order::latest()
        ->take(5)
        ->get();

    // ====== TRẠNG THÁI ĐƠN ======
    $orderStatus = Order::selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    // ====== TỒN KHO THẤP ======
    $lowStockProducts = Inventory::where('quantity', '<', 10)
        ->with('product')
        ->take(5)
        ->get();

    // ====== TỔNG TỒN KHO ======
    $totalStock = Inventory::sum('quantity');

    return view('admin.dashboard', compact(
        'totalOrders',
        'totalProducts',
        'totalCustomers',
        'revenue',
        'todayOrders',
        'todayRevenue',
        'monthlyRevenue',
        'topProducts',
        'orderStatus',
        'lowStockProducts',
        'totalStock',
        'latestOrders'
    ));
}


}