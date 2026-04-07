<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
public function index()
{
    $orders = Order::latest()->get();
    return view('staff.orders', compact('orders'));
}

public function updateStatus($id, $status)
{
    $order = Order::find($id);
    $order->status = $status;
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
    $cart = session('cart');

    $order = Order::create([
        'customer_name'=>$request->name,
        'phone'=>$request->phone,
        'address'=>$request->address,
        'total_price'=>collect($cart)->sum(fn($i)=>$i['price']*$i['quantity'])
    ]);

    foreach($cart as $id=>$item){
        OrderItem::create([
            'order_id'=>$order->id,
            'product_id'=>$id,
            'quantity'=>$item['quantity'],
            'price'=>$item['price']
        ]);
    }

    session()->forget('cart');

    return redirect('/');
}
public function inventory()
{
    $products = Product::all();
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

    $product->price = $product->price * 0.9; // giảm 10%
    $product->save();

    return back()->with('success','Đã giảm giá 10%');
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
}
