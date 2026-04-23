<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Import;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{

    public function index()
    {
        $imports = Import::with('product')->latest()->get();
        $products = \App\Models\Product::all();

        return view('admin.imports.index', compact('imports', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|integer|min:0',
        ]);

        $total = $request->quantity * $request->cost_price;

        // 1. lưu phiếu nhập
        Import::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'total_cost' => $total,
        ]);

        // 2. cập nhật inventory
        $inventory = Inventory::firstOrCreate(
            ['product_id' => $request->product_id],
            ['quantity' => 0, 'sold_quantity' => 0]
        );

        $inventory->quantity += $request->quantity;
        $inventory->updateStatus();
        $inventory->save();

        return back()->with('success', 'Nhập hàng thành công');
    }

    public function destroy($id)
    {
        Import::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa phiếu nhập');
    }

}
