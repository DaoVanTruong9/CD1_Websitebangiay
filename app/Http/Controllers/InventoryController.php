<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('product')->get();
        $products = Product::all();
        return view('admin.inventory.index', compact('inventories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $inventory = Inventory::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'sold_quantity' => 0,
        ]);

        $inventory->updateStatus();

        return back()->with('success', 'Thêm tồn kho thành công');
    }

    public function destroy($id)
    {
        Inventory::findOrFail($id)->delete();
        return back()->with('success', 'Đã xoá tồn kho');
    }
}