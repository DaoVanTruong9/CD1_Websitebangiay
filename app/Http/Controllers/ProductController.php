<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function shop()
    {
        $products = Product::all();
        return view('shop', compact('products'));
        if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products','public');
        }
    }

public function index(Request $request)
{
    $search = $request->search;

    $products = Product::where('is_sale', 1)
        ->when($search, function ($q) use ($search) {
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

    return view('user.index', compact('products', 'featured'));
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
        'price' => $request->price,
        'image' => $request->image,
        'is_sale' => $is_sale,
        'is_featured' => $is_featured,
    ]);

    return back();
}

    public function delete($id)
    {
        \App\Models\Product::find($id)->delete();
        return redirect('/products');
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
        'price' => $request->price,
        'image' => $request->image,
        'is_sale' => $is_sale,
        'is_featured' => $is_featured,
    ]);

    return back();
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
}
