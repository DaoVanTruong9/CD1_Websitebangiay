<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Hiển thị giỏ hàng
    public function index()
{
    $cart = session('cart', []);

    return view('user.cart', compact('cart'));
}

    // 2. Thêm vào giỏ
    public function add(Request $request)
{
    $product = Product::find($request->product_id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại'
        ]);
    }

    $cart = session()->get('cart', []);

    $key = $request->product_id . '_' . $request->size;

    if (isset($cart[$key])) {
        $cart[$key]['quantity'] += $request->quantity;
    } else {
        $cart[$key] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'size' => $request->size,
            'quantity' => $request->quantity
        ];
    }

    session()->put('cart', $cart);

    // ✅ FIX CHUẨN
    $totalQty = 0;
    foreach ($cart as $item) {
        $totalQty += $item['quantity'];
    }

    return response()->json([
        'success' => true,
        'message' => 'Đã thêm vào giỏ hàng',
        'cart_count' => $totalQty
    ]);
}

    // 3. Xóa
    public function remove($id)
    {
        CartItem::destroy($id);
        return back();
    }

    // 4. Cập nhật số lượng
    public function update(Request $request, $id)
    {
        $item = CartItem::find($id);

        if ($item) {
            $item->update([
                'quantity' => $request->quantity
            ]);
        }

        return back();
    }
    public function miniCart()
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return '<p class="text-center">Giỏ hàng trống</p>';
    }

    $html = '';
    $total = 0;

    foreach ($cart as $key => $item) {
        $total += $item['price'] * $item['quantity'];

        $html .= '
        <div class="cart-item">
            <img src="'.asset('images/'.$item['image']).'">

            <div class="info">
                <small>'.$item['name'].'</small><br>
                <small>Size: '.$item['size'].'</small><br>

                <div>
                    <button class="qty-btn" onclick="updateQty(\''.$key.'\', -1)">-</button>
                    '.$item['quantity'].'
                    <button class="qty-btn" onclick="updateQty(\''.$key.'\', 1)">+</button>
                </div>

                <small class="text-danger">
                    '.number_format($item['price']).'đ
                </small>
            </div>

            <span class="remove-btn" onclick="removeItem(\''.$key.'\')">
                <i class="fa fa-trash"></i>
            </span>
        </div>';
    }

    $html .= '
        <hr>
        <div class="text-end fw-bold text-danger">
            Tổng: '.number_format($total).' đ
        </div>
        <a href="/cart" class="btn btn-danger w-100 mt-2">
            Xem giỏ hàng
        </a>
    ';

    return $html;
}
public function updateQty(Request $request)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$request->key])) {

        $cart[$request->key]['quantity'] += $request->change;

        if ($cart[$request->key]['quantity'] <= 0) {
            unset($cart[$request->key]);
        }
    }

    session()->put('cart', $cart);

    return response()->json([
        'cart_count' => array_sum(array_column($cart, 'quantity'))
    ]);
}
public function removeMini(Request $request)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$request->key])) {
        unset($cart[$request->key]);
    }

    session()->put('cart', $cart);

    return response()->json([
        'cart_count' => array_sum(array_column($cart, 'quantity'))
    ]);
}
public function updateAjax(Request $request)
{
    $item = CartItem::find($request->id);

    if (!$item) {
        return response()->json(['success' => false]);
    }

    $item->quantity += $request->change;

    if ($item->quantity < 1) {
        $item->delete();
        return response()->json(['success' => false]);
    }

    $item->save();

    $itemTotal = $item->quantity * $item->product->price;

    $cart = Cart::with('items.product')
        ->where('user_id', Auth::id())
        ->first();

    $cartTotal = $cart->items->sum(fn($i) => $i->quantity * $i->product->price);

    $cartCount = $cart->items->sum('quantity');

    return response()->json([
        'success' => true,
        'qty' => $item->quantity,
        'item_total' => number_format($itemTotal) . ' đ',
        'cart_total' => number_format($cartTotal) . ' đ',
        'cart_count' => $cartCount // ✅ TRẢ VỀ
    ]);
}

public function removeAjax(Request $request)
{
    CartItem::destroy($request->id);

    return response()->json(['success' => true]);
}
public function checkoutPage()
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect('/san-pham')->with('error', 'Giỏ hàng trống');
    }

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('user.checkout', compact('cart', 'total'));
}
public function checkout(Request $request)
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Giỏ hàng trống');
    }

    // validate
    $request->validate([
        'name' => 'required',
        'phone' => 'required',
        'address' => 'required'
    ]);

    $total = 0;

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // 🎁 xử lý mã giảm giá (demo đơn giản)
    $discount = 0;
    if ($request->coupon == 'GIAM10') {
        $discount = 0.1 * $total;
    }

    $finalTotal = $total - $discount;

    // 🧾 tạo order
    $order = \App\Models\Order::create([
        'customer_name' => $request->customer_name,
        'phone' => $request->phone,
        'address' => $request->address,
        'total_price' => $finalTotal,
        'status' => 'pending'
    ]);

    // 📦 lưu order_items
    foreach ($cart as $item) {
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }

    // 🧹 xoá giỏ hàng
    session()->forget('cart');

    return redirect('/')->with('success', 'Đặt hàng thành công!');
}

}