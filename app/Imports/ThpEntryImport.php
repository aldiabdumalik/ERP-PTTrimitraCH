<?php

namespace App\Imports;

use App\Models\Oee\ThpEntry;
use Carbon\Carbon;
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
    protected $min_persen;

    function __construct($date, $min_persen) {
        $this->date = $date;
        $this->min_persen = $min_persen;
    }

    public function collection(Collection $rows)
    {
        $productioncode = [];
        $log = [];
        $notif = [];
        foreach ($rows as $row) {
            if ($row->filter()->isNotEmpty()) {
                $productioncode = DB::connection('oee')
                    ->table('db_productioncode_tbl')
                    ->where('production_code', $row[2])
                    ->where('code_status', 1)
                    ->first();
                if (isset($productioncode)) {
                    $check = ThpEntry::where('production_code', $productioncode->production_code)
                        ->where('thp_date', $this->date)
                        ->first();
                    if (!isset($check)) {

                        $row_qty = (int)(($row[15] != null) ? $row[15] : 0) + (int)(($row[16] != null) ? $row[16] : 0);
                        $shift = (int)(($row[15] != null) ? $row[15] : 0) + (int)(($row[16] != null) ? $row[16] : 0);
                        $machine = (isset($row[11])) ? '' : $row[11];

                        $remark = $shift . "_" . $machine;

                        $thp = ThpEntry::where('production_code', $row[2])
                                ->whereNull('closed')
                                ->orderBy('thp_date', 'desc')
                                ->first();
                        $thp_oldid = NULL;
                        $insert_notif = 0;
                        if (isset($thp)) {
                            $thp_oldid = $thp->id_thp;
                            if ($thp->lhp_qty > 0) {
                                $persentase = round(($thp->lhp_qty / $thp->plan)*100);
                                $min_persen = $this->min_persen;
                                $outstanding_qty = ($thp->outstanding_qty != null) ? $thp->outstanding_qty : ($thp->lhp_qty - $thp->plan);
                                if ($persentase <= $min_persen) {
                                    $thp_qty = (int) $row[7] + abs($outstanding_qty);
                                    $out_pls = abs($outstanding_qty);
                                    $notif = [
                                        'id_thp_old' => $thp->id_thp,
                                        'notif_outstanding' => $out_pls,
                                        'notif_date' => Carbon::now(),
                                        'notif_note' => "THP dengan PROD. CODE $row[2] masih ada pendingan sebesar $out_pls, dan akan langsung otomatis ditambahkan"
                                    ];
                                    $insert_notif = DB::table('oee.entry_thp_tbl_notif')->insertGetId($notif);
                                }else{
                                    $thp_qty = (int) $row[7];
                                }
                                $update = ThpEntry::where('production_code', $thp->production_code)
                                    ->where('thp_date', $thp->thp_date)
                                    ->update([
                                        'closed' => date('Y-m-d'),
                                        'status' => 'CLOSED'
                                    ]);
                                $log = DB::table('oee.entry_thp_tbl_log')
                                    ->insert([
                                        'id_thp' => $thp->id_thp,
                                        'thp_date' => $thp->thp_date,
                                        'date_written' => date('Y-m-d'),
                                        'time_written' => date('H:i:s'),
                                        'status_change' => 'CLOSED',
                                        'user' => Auth::user()->FullName,
                                        'note' => 'CLOSED BY SYSTEM, BECAUSE LHP QTY HAS BEEN ADDED TO NEXT THP'
                                    ]);
                            }else{
                                $thp_qty = (int) $row[7];
                                $notif = [
                                    'id_thp_old' => $thp->id_thp,
                                    'notif_date' => Carbon::now(),
                                    'notif_note' => "THP dengan PROD. CODE $row[2] pada tanggal $thp->thp_date masih tersedia dengan LHP Qty 0, akan otomatis di close."
                                ];
                                $insert_notif = DB::table('oee.entry_thp_tbl_notif')->insertGetId($notif);
                                $update = ThpEntry::where('production_code', $thp->production_code)
                                    ->where('thp_date', $thp->thp_date)
                                    ->update([
                                        'closed' => date('Y-m-d'),
                                        'status' => 'CLOSED'
                                    ]);
                                $log = DB::table('oee.entry_thp_tbl_log')
                                    ->insert([
                                        'id_thp' => $thp->id_thp,
                                        'thp_date' => $thp->thp_date,
                                        'date_written' => date('Y-m-d'),
                                        'time_written' => date('H:i:s'),
                                        'status_change' => 'CLOSED',
                                        'user' => Auth::user()->FullName,
                                        'note' => 'CLOSED BY SYSTEM, BECAUSE LHP QTY NOT FOUND'
                                    ]);
                            }
                        }else{
                            $thp_qty = (int) $row[7];
                        }

                        $data_insert = [
                            'customer_code' => $productioncode->customer_id,
                            'production_code' => $productioncode->production_code,
                            'item_code' => ($productioncode->item_code !== null) ? $productioncode->item_code : $row[3],
                            'part_number' => $productioncode->part_number,
                            'part_name' => $productioncode->part_name,
                            'part_type' => $productioncode->part_type,
                            'production_process' => $productioncode->production_process,
                            'route' => $productioncode->process_detailname,
                            'process_sequence_1' => $productioncode->process_sequence_1,
                            'process_sequence_2' => $productioncode->process_sequence_2,
                            'ct' => $productioncode->ct_sph,
                            // 'plan' => $row[7],
                            'plan' => $thp_qty,
                            'ton' => $machine,
                            'time' => (isset($row[13])) ? 0.00 : round($row[13], 2),
                            'plan_hour' => (isset($row[14])) ? 0.00 : round($row[14], 2),
                            'thp_qty' => $thp_qty,
                            'thp_remark' => $remark,
                            'note' => (isset($row[21])) ? $row[21] : null,
                            // 'apnormality' => $row[20],
                            // 'action_plan' => $row[25],
                            'thp_date' => $this->date,
                            'user' => Auth::user()->FullName,
                            'thp_written' => date('Y-m-d H:i:s')
                        ];
                        $insert = ThpEntry::insertGetId($data_insert);
                        if ($insert_notif != 0) {
                           $update_notif = DB::table('oee.entry_thp_tbl_notif')->where('id', $insert_notif)->update(['id_thp' => $insert]);
                        }
                        $log[] = [
                            'id_thp' => $insert,
                            'production_code' => $productioncode->production_code,
                            'item_code' => ($productioncode->item_code !== null) ? $productioncode->item_code : $row[3],
                            'remark' => $remark,
                            'thp_date' => $this->date,
                            'date_written' => date('Y-m-d'),
                            'time_written' => date('H:i:s'),
                            'status_change' => 'ADD',
                            'user' => Auth::user()->FullName,
                            'note' => null
                        ];
                    }
                }
            }
        }
        $log = DB::table('oee.entry_thp_tbl_log')
            ->insert($log);
    }

    public function startRow(): int
    {
        return 9;
    }

}
