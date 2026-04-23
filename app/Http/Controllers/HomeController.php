<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    
public function index(Request $request)
{
    $query = Product::query();

    // 🔍 SEARCH
    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 👟 FILTER SIZE (chuẩn)
    if ($request->size) {
        $query->where(function ($q) use ($request) {
            foreach ($request->size as $size) {
                $q->orWhere('size', 'like', "%,$size,%");
            }
        });
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

    // 👉 SALE
    $products = (clone $query)
        ->where('is_sale', 1)
        ->take(10)
        ->get();

    // 👉 FEATURED
    $featured = (clone $query)
        ->where('is_featured', 1)
        ->take(10)
        ->get();

    return view('user.home', compact('products', 'featured'));
}
}
