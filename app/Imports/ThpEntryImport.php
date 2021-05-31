<?php

namespace App\Imports;

use App\Models\Oee\ThpEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ThpEntryImport implements ToCollection, WithStartRow
{

    protected $date;

    function __construct($date) {
        $this->date = $date;
    }

    public function collection(Collection $rows)
    {
        $productioncode = [];
        foreach ($rows as $row) {
            if ($row->filter()->isNotEmpty()) {
                $productioncode = DB::connection('oee')
                    ->table('db_productioncode_tbl')
                    ->select(
                        'production_code', 
                        'part_number', 
                        'part_name', 
                        'part_type',
                        'item_code',
                        'process_sequence_1', 
                        'process_sequence_2', 
                        'process_detailname', 
                        'customer_id',
                        'ct_sph',
                        'production_process'
                    )
                    ->where('production_code', $row[2])
                    ->where('code_status', 1)
                    ->first();
                if (isset($productioncode)) {
                    $data_shift_1 = [
                        'customer_code' => $productioncode->customer_id,
                        'production_code' => $productioncode->production_code,
                        'item_code' => $productioncode->item_code,
                        'part_number' => $productioncode->part_number,
                        'part_name' => $productioncode->part_name,
                        'part_type' => $productioncode->part_type,
                        'production_process' => $productioncode->production_process,
                        'route' => $productioncode->process_detailname,
                        'process_sequence_1' => $productioncode->process_sequence_1,
                        'process_sequence_2' => $productioncode->process_sequence_2,
                        'ct' => $productioncode->ct_sph,
                        'plan' => $row[6],
                        'ton' => $row[10],
                        'time' => round($row[12], 2),
                        'plan_hour' => round($row[13], 2),
                        'thp_qty' => ($row[14] != null) ? $row[14]:0,
                        'thp_remark' => '1A_ ',
                        'note' => (isset($row[20])) ? $row[20]:null,
                        // 'apnormality' => $row[20],
                        // 'action_plan' => $row[25],
                        'thp_date' => $this->date,
                        'user' => Auth::user()->FullName,
                        'thp_written' => date('Y-m-d H:i:s')
                    ];
                    $data_shift_2 = [
                        'customer_code' => $productioncode->customer_id,
                        'production_code' => $productioncode->production_code,
                        'item_code' => $productioncode->item_code,
                        'part_number' => $productioncode->part_number,
                        'part_name' => $productioncode->part_name,
                        'part_type' => $productioncode->part_type,
                        'production_process' => $productioncode->production_process,
                        'route' => $productioncode->process_detailname,
                        'process_sequence_1' => $productioncode->process_sequence_1,
                        'process_sequence_2' => $productioncode->process_sequence_2,
                        'ct' => $productioncode->ct_sph,
                        'plan' => $row[6],
                        'ton' => $row[10],
                        'time' => round($row[12], 2),
                        'plan_hour' => round($row[13], 2),
                        'thp_qty' => ($row[15] != null) ? $row[15]:0,
                        'thp_remark' => '2A_ ',
                        'note' => (isset($row[20])) ? $row[20]:null,
                        // 'apnormality' => $row[20],
                        // 'action_plan' => $row[25],
                        'thp_date' => $this->date,
                        'user' => Auth::user()->FullName,
                        'thp_written' => date('Y-m-d H:i:s')
                    ];
                    ThpEntry::create($data_shift_1);
                    ThpEntry::create($data_shift_2);
                }
            }
        }
    }

    public function startRow(): int
    {
        return 9;
    }

}
