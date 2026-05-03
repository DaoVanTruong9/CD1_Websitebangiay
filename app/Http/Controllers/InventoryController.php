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
        return view('staff.inventory', compact('inventories', 'products'));
    }

    public function destroy($id)
    {
        Inventory::findOrFail($id)->delete();
        return back()->with('success', 'Đã xoá tồn kho');
    }
}