<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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

        return view('products.index', compact('products', 'featured'));
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
        $products = \App\Models\Product::all();
        return view('staff.inventory', compact('products'));
    }
    

    public function promotion()
    {
        $products = Product::all();
        return view('staff.promotion', compact('products'));
    }

    public function applyPromotion($id)
    {
        $product = Product::find($id);

        // giảm 10%
        $product->price = $product->price * 0.9;
        $product->save();

        return back()->with('success', 'Đã áp dụng khuyến mãi');
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

    $products = $query->latest()->paginate(12);

    // giữ query khi phân trang
    $products->appends($request->all());

    return view('user.products', compact('products'));
}
}
