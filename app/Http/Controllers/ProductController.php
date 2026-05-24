<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function shop(Request $request)
    {
        $products = Product::all();
        return view('shop', compact('products'));
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%$search%")
                    ->orWhere('brand', 'like', "%$search%");
                }); 
            })
            ->orderBy('id', 'asc')
            ->get();

        $featured = Product::where('is_featured', 1)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%$search%")
                        ->orWhere('brand', 'like', "%$search%");
                });
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.products.index', compact('products', 'featured'));
    }

    public function store(Request $request)
    {
        $is_sale = 0;
        $is_featured = 0;

    if($request->display_type == 'sale'){
        $is_sale = 1;
    }elseif($request->display_type == 'featured'){
        $is_featured = 1;
    }

    Product::create([
        'name' => $request->name,
        'brand' => $request->brand,
        'size' => $request->size ? implode(',', $request->size) . ',' : null,
        'price' => $request->price,
        'image' => $request->image,
        'is_sale' => $is_sale,
        'is_featured' => $is_featured,
    ]);

    return back()->with('success', 'Thêm sản phẩm thành công');
}

    public function delete($id)
    {
        Product::find($id)->delete();
        return back()->with('success', 'Xóa sản phẩm thành công');
    }
    
    public function update(Request $request, $id)
    {
        $p = Product::find($id);

        $is_sale = 0;
        $is_featured = 0;

        if($request->display_type == 'sale'){
            $is_sale = 1;
        }elseif($request->display_type == 'featured'){
            $is_featured = 1;
        }

        $p->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'size' => $request->size ? implode(',', $request->size) . ',' : null,
            'price' => $request->price,
            'image' => $request->image,
            'is_sale' => $is_sale,
            'is_featured' => $is_featured,
        ]);

        return back()->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function inventory()
    {
        $products = Product::with('inventory')->get();

        return view('staff.inventory', compact('products'));
    }

    public function userProducts(Request $request)
    {
        $query = Product::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 🏷️ FILTER BRAND
        if ($request->brand) {
            $query->whereIn('brand', $request->brand);
        }

        // 💰 FILTER PRICE
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // 👟 FILTER SIZE
        if ($request->size) {
            $query->where(function ($q) use ($request) {
        foreach ($request->size as $size) {
            $q->orWhere('size', 'like', "%,$size,%")
              ->orWhere('size', 'like', "$size,%")
              ->orWhere('size', 'like', "%,$size")
              ->orWhere('size', '=', "$size");
            }
        });
    }

        $products = $query->with(['inventory','reviews.user'])->paginate(12);

        // giữ query khi phân trang
        $products->appends($request->all());

        return view('user.products', compact('products'));
    }

    public function revenueReport(Request $request)
    {
        // ===== CHỌN THÁNG =====
        $month = $request->month ?? now()->month;
        $year = now()->year;

        // ===== DOANH THU THEO THÁNG =====
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        // ===== DOANH THU THEO NGÀY =====
        $dailyRevenue = Order::selectRaw('DAY(created_at) as day, SUM(total_price) as total')
            ->where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('day')
            ->pluck('total', 'day');

        // ===== TỔNG =====
        $totalRevenue = Order::where('status', 'completed')
            ->sum('total_price');

        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_price');

        $thisMonthRevenue = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'completed')
            ->sum('total_price');

        // ===== ĐƠN GẦN ĐÂY =====
        $latestOrders = Order::latest()
            ->take(10)
            ->get();

        return view('admin.reports.revenue', compact(
            'monthlyRevenue',
            'dailyRevenue',
            'totalRevenue',
            'todayRevenue',
            'thisMonthRevenue',
            'latestOrders',
            'month'
        ));
    }

    public function bestSellingProducts()
    {
        $topProducts = OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold')
        )
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->paginate(10);

        return view('admin.reports.best_selling', compact('topProducts'));
    }

    public function approveReturn($id)
{
    $return = ReturnRequest::with('orderItem.product.inventory')
        ->findOrFail($id);

    if ($return->status == 'approved') {
        return back();
    }

    $inventory =
        $return->orderItem->product->inventory;

    $qty = $return->quantity;

    // cộng kho lại
    $inventory->quantity += $qty;

    // giảm đã bán
    $inventory->sold_quantity -= $qty;

    if ($inventory->sold_quantity < 0) {
        $inventory->sold_quantity = 0;
    }

    $inventory->updateStatus();

    $inventory->save();

    $return->status = 'approved';

    $return->save();

    return back()->with(
        'success',
        'Đã duyệt trả hàng'
    );
}

    
    // public function promotion()
    // {
    //     $products = Product::all();
    //     return view('staff.promotion', compact('products'));
    // }

    // public function applyPromotion($id)
    // {
    //     $product = Product::find($id);

    //     // giảm 10%
    //     $product->price = $product->price * 0.9;
    //     $product->save();

    //     return back()->with('success', 'Đã áp dụng khuyến mãi');
    // }

}