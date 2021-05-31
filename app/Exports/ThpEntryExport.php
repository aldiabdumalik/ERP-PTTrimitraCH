<?php

namespace App\Exports;

use App\Models\Oee\ThpEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;

class ThpEntryExport implements FromArray, WithHeadings
{
    use Exportable;
    protected $result;

    function __construct($result) {
        $this->result = $result;
    }
    

    public function array(): array
    {
        return [
            [1, 2, 3],
            [4, 5, 6]
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'User',
            'Date',
        ];
    }
}