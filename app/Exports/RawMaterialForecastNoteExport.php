<?php

namespace App\Exports;

use Carbon\Carbon;
use DB;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


use App\Models\StoredProcedure\proc_RawMaterial As RawMaterial;


class RawMaterialForecastNoteExport implements FromView, WithEvents, ShouldAutoSize
{

    private $model_type;

    public function setVendorCode(string $vend_code)
    {
        $this->vend_code = $vend_code;
    }

    public function setPeriod(string $period)
    {
        $this->period = $period;
    }

    public function view(): View
    {
        $model_type = RawMaterial::ReportForecastNoteDetailModel($this->vend_code, $this->period);

        foreach($model_type AS $type){
            ${'data'.$type->model} = RawMaterial::ReportForecastNoteDetail($this->vend_code, $this->period, $type->model);
            $txtData['data'.$type->model] = ${'data'.$type->model};
        }

        $output = [
            'header'        => RawMaterial::ReportForecastNoteHeader($this->vend_code, $this->period),
            'parameter'     => RawMaterial::ReportForecastNoteParameter($this->vend_code, $this->period),
            'ver_period'    => RawMaterial::ReportForecastNoteVerPeriod($this->vend_code, $this->period),
            'detail'        => $txtData,
            'detail_model'  => RawMaterial::ReportForecastNoteDetailModel($this->vend_code, $this->period),
        ];

        return view('tms.manufacturing.raw-material.forecast-note-export')->with($output);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

                $lastColumnIndex = 'N';
                $lastRowIndex = 1000;
                $event->sheet->getDelegate()->getStyle('A1:'.$lastColumnIndex.$lastRowIndex)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A3:J4')->getFont()->setBold(true);

                $rows = $event->sheet->getDelegate()->toArray();

                foreach($rows as $k => $v){
                    if($v[10] === 'Approved by'){
                        $event->sheet->getDelegate()->getStyle('K'.($k+1).':N'.($k+4))->applyFromArray($styleArray);
                    }
                    if($v[0] === 'Up.'){
                        $event->sheet->getDelegate()->getStyle('A'.($k+1).':H'.($k+3))->applyFromArray($styleArray);
                    }
                    if($v[0] === 'NO'){
                        $event->sheet->getDelegate()->getStyle('A'.($k+1).':N'.($k+3))->applyFromArray($styleArray);
                    }
                }
            },
        ];
    }



}
