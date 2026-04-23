<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'order_id' => 'required|exists:orders,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
    ]);

    // CHẶN REVIEW TRÙNG
    $exists = Review::where('user_id', auth()->id())
        ->where('product_id', $request->product_id)
        ->where('order_id', $request->order_id)
        ->exists();

    if ($exists) {
        return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi');
    }

    Review::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'order_id' => $request->order_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return back()->with('success', 'Cảm ơn bạn đã đánh giá');
}

}