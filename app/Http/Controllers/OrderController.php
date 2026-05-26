<?php

namespace App\Http\Controllers;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\ReturnRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RevenueExport;
use App\Exports\BestSellingExport;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::latest()->get();
        return view('staff.orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items.product.inventory')->findOrFail($id);

        $new = $request->status;
        $current = $order->status;

        $flow = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['shipping', 'cancelled'],
            'shipping' => ['completed'],
        ];

        if (!isset($flow[$current]) || !in_array($new, $flow[$current])) {
            return back()->with('error', 'Chuyển trạng thái không hợp lệ');
        }

    /*
    |--------------------------------------------------------------------------
    | REALTIME TỒN KHO
    |--------------------------------------------------------------------------
    | shipping -> completed
    | mới chính thức trừ kho
    */
    if ($new == 'completed') {

        foreach ($order->items as $item) {

            $inventory = $item->product->inventory;

            if ($inventory) {

                // trừ tồn kho
                $inventory->quantity -= $item->quantity;

                // tăng đã bán
                $inventory->sold_quantity += $item->quantity;

                // tránh âm kho
                if ($inventory->quantity < 0) {
                    $inventory->quantity = 0;
                }

                // cập nhật trạng thái kho
                $inventory->updateStatus();

                $inventory->save();
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HOÀN KHO KHI HỦY
    |--------------------------------------------------------------------------
    */
    if ($new == 'cancelled' && $current != 'pending') {

        foreach ($order->items as $item) {

            $inventory = $item->product->inventory;

            if ($inventory) {

                $inventory->quantity += $item->quantity;

                $inventory->sold_quantity -= $item->quantity;

                if ($inventory->sold_quantity < 0) {
                    $inventory->sold_quantity = 0;
                }

                $inventory->updateStatus();

                $inventory->save();
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | COD -> PAID khi hoàn thành
    |--------------------------------------------------------------------------
    */
    if ($new == 'completed' && $order->payment_method == 'cod') {
        $order->payment_status = 'paid';
    }

    $order->status = $new;

    $order->save();

    return back()->with('success', 'Cập nhật thành công');
}

    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        $request->validate([
            'customer_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'payment' => 'required|in:cod,bank'
        ]);

        $cart = session('cart');

        if (!$cart || count($cart) == 0) {
            return back()->with('error', 'Giỏ hàng trống');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        $finalTotal = $total;

        if (session()->has('coupon')) {

            $couponData = session('coupon');

            $coupon = Coupon::where( 'code',
            $couponData['code'])->first();

        if ($coupon) {

            // tính giảm
            $discount = $total * $coupon->discount / 100;

            // tổng sau giảm
            $finalTotal = $total - $discount;

            // giảm số lượng
            $coupon->quantity -= 1;

            if ($coupon->quantity <= 0) {
                $coupon->status = 'inactive';
            }

            $coupon->save();
        }
    }

        // KHÔNG trừ kho ở đây nữa
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_price' => $finalTotal,

            'coupon_code' => session('coupon.code'),
            'discount_amount' => $discount,

            'status' => 'pending',
            'payment_method' => $request->payment ?? 'cod',
            'payment_status' => $request->payment == 'bank' ? 'pending' : 'unpaid'
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
               'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'size' => $item['size'] ?? null
            ]);
        }

        session()->forget('cart');
        session()->forget('coupon');

        if ($request->payment == 'cod') {
            return redirect('/orders/my')->with('success', 'Đặt hàng COD thành công');
        }

        return redirect('/payment/qr/' . $order->id);
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

    public function bankPayment($id)
    {
        $order = Order::find($id);

        return view('user.bank_payment', compact('order'));
    }

    public function qrPayment($id)
    {
        $order = Order::findOrFail($id);

        // THÔNG TIN NGÂN HÀNG CỦA BẠN
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

        if ($order->payment_method != 'bank') {
            return back()->with('error', 'Không phải đơn chuyển khoản');
        }

        $order->payment_status = 'paid';
        $order->status = 'confirmed';

        $order->save();

        return back()->with('success','Đã xác nhận thanh toán');
    }

    public function confirmOrder($id)
{

    $order = Order::with('items.product.inventory')
        ->findOrFail($id);

    
    if ($order->status != 'pending') {
    return back()->with(
        'error',
        'Đơn đã được xử lý'
    );
}    
    // ===== COD =====
    if ($order->payment_method == 'cod') {

        // trừ kho
        foreach ($order->items as $item) {

            $inventory = $item->product->inventory;

            if (!$inventory) {
                return back()->with(
                    'error',
                    'Sản phẩm chưa có tồn kho'
                );
            }

            if ($inventory->quantity < $item->quantity) {
                return back()->with(
                    'error',
                    'Không đủ hàng: ' . $item->product->name
                );
            }

            $inventory->quantity -= $item->quantity;

            $inventory->sold_quantity += $item->quantity;

            $inventory->updateStatus();

            $inventory->save();
        }

        $order->status = 'confirmed';
    }

    // ===== BANK =====
    if (
        $order->payment_method == 'bank'
        && $order->payment_status == 'paid'
    ) {

        // trừ kho
        foreach ($order->items as $item) {

            $inventory = $item->product->inventory;

            if (!$inventory) {
                return back()->with(
                    'error',
                    'Sản phẩm chưa có tồn kho'
                );
            }

            if ($inventory->quantity < $item->quantity) {
                return back()->with(
                    'error',
                    'Không đủ hàng: ' . $item->product->name
                );
            }

            $inventory->quantity -= $item->quantity;

            $inventory->sold_quantity += $item->quantity;

            $inventory->updateStatus();

            $inventory->save();
        }

        $order->status = 'confirmed';
    }

    $order->save();

    return back()->with(
        'success',
        'Đã duyệt đơn'
    );
}

        public function markReceived($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->status == 'shipping') {
            $order->status = 'completed';

            // 🔥 COD → lúc này mới PAID
            if ($order->payment_method == 'cod') {
                $order->payment_status = 'paid';
            }

            $order->save();
        }

        return back()->with('success','Đã nhận hàng');
    }

    public function history()
{
    $orders = Order::with([
        'items.product',
        'items.product.reviews'
    ])
    ->where('user_id', auth()->id())
    ->whereIn('status', [
    'completed',
    'pending_return',
    'pending_exchange',
    'returned',
    'exchange'])
    ->latest()
    ->get();

    return view('user.history', compact('orders'));
}

    public function applyCoupon(Request $request)
{
    $request->validate([
        'code' => 'required'
    ]);

    $coupon = Coupon::where('code', $request->code)->first();

    // không tồn tại
    if (!$coupon) {
        return response()->json([
            'success' => false,
            'message' => 'Mã không tồn tại'
        ]);
    }

    // chưa kích hoạt
    if ($coupon->status != 'active') {
        return response()->json([
            'success' => false,
            'message' => 'Mã chưa được kích hoạt'
        ]);
    }

    // hết lượt
    if ($coupon->quantity <= 0) {
        return response()->json([
            'success' => false,
            'message' => 'Mã đã hết lượt'
        ]);
    }

    // hết hạn
    if (now()->gt($coupon->expired_at)) {
        return response()->json([
            'success' => false,
            'message' => 'Mã đã hết hạn'
        ]);
    }

    session([
        'coupon' => [
            'code' => $coupon->code,
            'discount' => $coupon->discount
        ]
    ]);

    return response()->json([
        'success' => true,
        'code' => $coupon->code,
        'discount' => $coupon->discount
    ]);
}

    public function applyPromotion(Request $request)
{
    $coupon = Coupon::find($request->coupon_id);

    if (!$coupon) {
        return back()->with('error', 'Mã không tồn tại');
    }

    if (now()->gt($coupon->expired_at)) {
        return back()->with('error', 'Mã đã hết hạn');
    }

    if ($coupon->quantity <= 0) {
        return back()->with('error', 'Mã đã hết lượt');
    }

    // kích hoạt
    $coupon->status = 'active';
    $coupon->save();

    return back()->with(
        'success',
        'Đã kích hoạt mã: ' . $coupon->code
    );
}

    public function promotion()
    {
        $coupons = Coupon::all();
        return view('staff.promotion', compact('coupons'));
    }

    public function storeReturn(Request $request)
    {
        ReturnRequest::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);
        $order = Order::find($request->order_id);

        if ($request->type == 'return') {
            $order->status = 'pending_return';
        } else {
            $order->status = 'pending_exchange';
        }

        $order->save();
        return back()->with('success', 'Đã gửi yêu cầu thành công');
    }

    public function returns()
    {
        $returns = ReturnRequest::where('type','return')
                ->latest()
                ->get();

        return view('staff.returns', compact('returns'));
    }

    public function exchanges()
    {
        $returns = ReturnRequest::where('type','exchange')
                ->latest()
                ->get();

        return view('staff.exchanges', compact('returns'));
    }

    public function processReturn(Request $request, $id)
{
    $return = ReturnRequest::findOrFail($id);

    // cập nhật trạng thái request
    $return->status = $request->status;
    $return->save();

    // chỉ xử lý khi duyệt
    if ($request->status == 'approved') {

        $order = Order::find($return->order_id);

        $product = Product::find($return->product_id);

        // ===== TRẢ HÀNG =====
        if ($return->type == 'return') {

            // cộng lại kho
            if ($product && $product->inventory) {

                $product->inventory->quantity += 1;

                $product->inventory->sold_quantity -= 1;

                $product->inventory->updateStatus();

                $product->inventory->save();
            }

            // cập nhật trạng thái đơn
            $order->status = 'returned';
            $order->save();
        }

        // ===== ĐỔI HÀNG =====
        if ($return->type == 'exchange') {

            $order->status = 'exchange';
            $order->save();
        }
    }

    return back()->with('success', 'Đã xử lý yêu cầu');
}

    public function exportRevenue()
    {
        return Excel::download(new RevenueExport,'bao_cao_doanh_thu.xlsx');
    }

    public function exportBestSelling()
    {
        return Excel::download(new BestSellingExport,'san_pham_ban_chay.xlsx');
    }


}
