<?php

namespace App\Exports;

use App\Models\Oee\ThpEntry;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ThpEntryExportSummary implements FromView,  WithEvents, ShouldAutoSize, WithHeadings
{
    use Exportable;
    protected $result;
    protected $row_count;

    function __construct($result, $row_count) {
        $this->result = $result;
        $first = 8;
        $total = 1;
        $this->row = $first + $total + $row_count;
    }
    
    public function view(): View
    {
        return view('tms.manufacturing.thp_entry._report.excelSummary', $this->result);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {
                $event->sheet->getStyle('B7:S'.$this->row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '111'],
                        ],
                    ],
                ]);
                
                $event->sheet->getColumnDimension("A")->setAutoSize(true);
                $event->sheet->getColumnDimension("B")->setAutoSize(true);
                $event->sheet->getColumnDimension("C")->setAutoSize(true);
                $event->sheet->getColumnDimension("D")->setAutoSize(true);
                $event->sheet->getColumnDimension("E")->setAutoSize(true);
                $event->sheet->getColumnDimension("F")->setAutoSize(true);
                $event->sheet->getColumnDimension("G")->setAutoSize(true);
                $event->sheet->getColumnDimension("H")->setAutoSize(true);
                $event->sheet->getColumnDimension("I")->setAutoSize(true);
                $event->sheet->getColumnDimension("J")->setAutoSize(true);
                $event->sheet->getColumnDimension("K")->setAutoSize(true);
                $event->sheet->getColumnDimension("L")->setAutoSize(true);
                $event->sheet->getColumnDimension("M")->setAutoSize(true);
                $event->sheet->getColumnDimension("N")->setAutoSize(true);
                $event->sheet->getColumnDimension("O")->setAutoSize(true);
                $event->sheet->getColumnDimension("P")->setAutoSize(true);
                $event->sheet->getColumnDimension("Q")->setAutoSize(true);
                $event->sheet->getColumnDimension("R")->setAutoSize(true);
                $event->sheet->getColumnDimension("S")->setAutoSize(true);
                $event->sheet->getColumnDimension("T")->setAutoSize(true);
                $event->sheet->getColumnDimension("U")->setAutoSize(true);
                $cellRange = 'B7:S8';
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal('center');
            
            },
        ];
    }
    public function headings(): array
    {
        return [
            'DATE',
            'CUST',
            'PART NO',
            'ITEMCODE',
            'PART NAME',
            'C/T',
            'ROUTE',
            'TON',
            'PROSES',
            'TIME',
            'PLAN HOUR',
            'PLAN THP',
            'ACTUAL LHP',
            'ACT HOUR',
            'OUTSTANDING'
        ];
    }
}