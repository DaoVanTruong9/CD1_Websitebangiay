<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class RevenueExport implements FromCollection
{
    public function collection()
    {
        return Order::select(
            'id',
            'customer_name',
            'total_price',
            'status',
            'created_at'
        )->get();
    }
}