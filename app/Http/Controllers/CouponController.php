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

public function update(Request $request, $id)
{
    $coupon = Coupon::findOrFail($id);

    $request->validate([
        'discount' => 'required|numeric|min:1|max:100',
        'quantity' => 'required|numeric|min:1',
        'expired_at' => 'required|date'
    ]);

    $coupon->update([
        'discount' => $request->discount,
        'quantity' => $request->quantity,
        'expired_at' => $request->expired_at
    ]);

    return redirect()->back()
        ->with('success', 'Cập nhật mã thành công');
}

public function store(Request $request)
{
    Coupon::create([
        'code' => strtoupper($request->code),
        'discount' => $request->discount,
        'quantity' => $request->quantity,
        'expired_at' => $request->expired_at,
        'status' => 'inactive'
    ]);

    return back()->with('success', 'Tạo mã thành công');
}

public function delete($id)
{
    Coupon::find($id)->delete();
    return back();
}

}
