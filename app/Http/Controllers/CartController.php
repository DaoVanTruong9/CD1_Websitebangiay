<?php

namespace App\Http\Controllers;
use App\Models\Product;
abstract class Controller
{
    
public function add($id)
{
    $product = Product::findOrFail($id);

    $cart = session()->get('cart', []);

    if(isset($cart[$id])){
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            "name"=>$product->name,
            "price"=>$product->price,
            "image"=>$product->image,
            "quantity"=>1
        ];
    }

    session()->put('cart',$cart);
    return back();
}
}