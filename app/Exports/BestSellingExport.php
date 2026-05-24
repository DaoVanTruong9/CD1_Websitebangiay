<?php

namespace App\Exports;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BestSellingExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
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
        ->map(function ($item) {

            return [
                'Sản phẩm' => $item->product->name ?? 'N/A',
                'Đã bán' => $item->total_sold
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tên sản phẩm',
            'Số lượng bán'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [

            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],

                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'rgb' => '198754'
                    ]
                ],

                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ]
            ],
        ];
    }
}