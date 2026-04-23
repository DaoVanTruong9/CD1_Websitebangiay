<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
public function index()
{
    $orders = Order::with('items.product')->latest()->get();
    return view('staff.orders', compact('orders'));
}

public function updateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $new = $request->status;
    $current = $order->status;

    $flow = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['shipping', 'cancelled'],
        'shipping' => ['completed'],
    ];

    if (!isset($flow[$current]) || !in_array($new, $flow[$current])) {
        return back()->with('error','Status không hợp lệ');
    }

    $order->status = $request->status;
    $order->save();

    return back()->with('success','Cập nhật thành công');
}

public function returns()
{
    $orders = Order::where('status','delivered')->get();
    return view('staff.returns', compact('orders'));
}

public function processReturn($id)
{
    $order = Order::find($id);
    $order->status = 'returned';
    $order->save();

    return back()->with('success','Đã xử lý trả hàng');
}


public function checkout(Request $request)
{
    if (!Auth::check()) {
        return redirect('/login')->with('error', 'Vui lòng đăng nhập để thanh toán');
    }
    $request->validate([
        'customer_name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'payment' => 'required'
    ]);

    $cart = session('cart');

    if (!$cart || count($cart) == 0) {
        return back()->with('error', 'Giỏ hàng trống');
    }

    // ===== TÍNH TIỀN =====
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // ===== ÁP MÃ =====
    if ($request->coupon == 'SALE10') {
        $total *= 0.9;
    }

    // ===== TẠO ORDER =====
    $order = Order::create([
        'user_id' => Auth::id(),
        'customer_name' => $request->customer_name,
        'phone' => $request->phone,
        'address' => $request->address,
        'total_price' => $total,
        'status' => 'pending'
]);

    // ===== LƯU ORDER ITEMS =====
    foreach ($cart as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
        // 🔥 TRỪ KHO
    $inventory = Inventory::where('product_id', $item['id'])->first();

    if ($inventory) {
        $inventory->quantity -= $item['quantity'];
        $inventory->sold_quantity += $item['quantity'];
        $inventory->updateStatus();
        $inventory->save();
    }
    }

    session()->forget('cart');

    // ===== XỬ LÝ THANH TOÁN COD=====
    if ($request->payment == 'cod') {
        return redirect('/orders/my')->with('success', 'Đặt hàng thành công (COD)');
    }

    // ===== CHUYỂN KHOẢN QR =====
    if ($request->payment == 'bank') {
        return redirect('/payment/qr/' . $order->id);
    }
}

// PDF
public function invoice($id)
{
    $order = \App\Models\Order::with('items.product')->find($id);

    $pdf = Pdf::loadView('orders.invoice', compact('order'));

    return $pdf->download('invoice_'.$id.'.pdf');
}

// REPORT
public function report()
{
    $revenue = \App\Models\Order::where('status','completed')
        ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
        ->groupBy('date')
        ->get();

    return view('reports.index', compact('revenue'));
}
public function bankPayment($id)
{
    $order = Order::find($id);

    return view('user.bank_payment', compact('order'));
}

public function myOrders()
{
    $orders = Order::with('items.product')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('user.orders', compact('orders'));
}

public function markPaid($id)
{
    $order = Order::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $order->payment_status = 'paid';
    $order->save();

    return redirect('/orders/my')
        ->with('success', 'Đã gửi yêu cầu xác nhận thanh toán');
}

public function qrPayment($id)
{
    $order = Order::findOrFail($id);

    // 🔥 THÔNG TIN NGÂN HÀNG CỦA BẠN
    $bank = "MB"; // MB, VCB, ACB...
    $account = "0123456789"; // STK của bạn

    $amount = $order->total_price;
    $info = "DH" . $order->id;

    $qrUrl = "https://img.vietqr.io/image/{$bank}-{$account}-compact2.png?amount={$amount}&addInfo={$info}";

    return view('user.qr_payment', compact('order', 'qrUrl'));
}

public function confirmPayment($id)
{
    $order = Order::findOrFail($id);
    $order->payment_status = 'paid';
    $order->status = 'confirmed';
    $order->save();

    return back()->with('success','Đã xác nhận thanh toán');
}

public function paymentSuccess($id)
{
    return redirect('/orders/my')
        ->with('success', 'Đặt hàng thành công, vui lòng chờ xác nhận thanh toán');
}
public function confirmOrder($id)
{
    $order = Order::findOrFail($id);

    if ($order->payment_status == 'paid') {
        $order->status = 'confirmed';
        $order->save();
    }

    return back()->with('success','Đã duyệt đơn');
}

public function shipOrder($id)
{
    $order = Order::findOrFail($id);
    $order->status = 'shipping';
    $order->save();

    return back()->with('success','Đang giao hàng');
}

public function markReceived($id)
{
    $order = Order::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    if ($order->status == 'shipping') {
        $order->status = 'completed';
        $order->save();
    }

    return back()->with('success','Đã xác nhận nhận hàng');
}

public function history()
{
    $orders = Order::with('items.product')
        ->where('user_id', auth()->id())
        ->where('status', 'completed')
        ->latest()
        ->get();

    return view('user.history', compact('orders'));
}

}
