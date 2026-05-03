<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    
public function index()
{
    $coupons = Coupon::orderBy('id', 'desc')->get();
    return view('admin.coupons.index', compact('coupons'));
}

public function store(Request $request)
{
    Coupon::create([
        'code' => strtoupper($request->code),
        'discount' => $request->discount,
        'quantity' => $request->quantity,
        'expired_at' => $request->expired_at,
    ]);

    return back()->with('success', 'Tạo mã thành công');
}

public function delete($id)
{
    Coupon::find($id)->delete();
    return back();
}

}
