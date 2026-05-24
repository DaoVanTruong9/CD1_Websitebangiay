<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
{
    public function collection()
    {
        return Order::select(
            'id',
            'customer_name',
            'phone',
            'total_price',
            'status',
            'payment_method',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Mã đơn',
            'Khách hàng',
            'SĐT',
            'Tổng tiền',
            'Trạng thái',
            'Thanh toán',
            'Ngày tạo'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [

            // HEADER
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],

                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'rgb' => '0B6FC7'
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