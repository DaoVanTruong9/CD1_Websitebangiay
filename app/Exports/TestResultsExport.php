<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TestResultsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DB::connection('mysql_testing')
            ->table('test_results')
            ->select(
                'test_case',
                'result',
                'status',
                'created_at'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'Test Case',
            'Kết Quả',
            'Trạng Thái',
            'Thời Gian'
        ];
    }
}