<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    
public function index()
{
    $products = Product::where('is_sale', 1)->take(10)->get();

    $featured = Product::where('is_featured', 1)->take(10)->get();

    return view('user.index', compact('products', 'featured'));
}
}
