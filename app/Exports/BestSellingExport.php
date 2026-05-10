<?php

namespace App\Exports;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class BestSellingExport implements FromCollection
{
    public function collection()
    {
        return OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->groupBy('product_id')
            ->with('product')
            ->get()
            ->map(function($item){

                return [
                    'Sản phẩm' => $item->product->name ?? 'N/A',
                    'Đã bán' => $item->total_sold
                ];
            });
    }
}