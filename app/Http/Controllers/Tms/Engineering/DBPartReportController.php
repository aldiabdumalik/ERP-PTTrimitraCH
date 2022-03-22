<?php

namespace App\Http\Controllers\TMS\Engineering;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DB_parts\InputParts;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DBPartReportController extends Controller
{
    use ToolsTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $type = InputParts::select(['type'])->groupBy('type')->get();
        return view('tms.db_parts.report.index', compact('type'));
    }

    public function partsType($customer)
    {
        $type = InputParts::select(['type'])->where('cust_id', $customer)->groupBy('type')->get();

        if ($type->isEmpty()) {
            return _Success('Part type for this customer not exist!');
        }

        return _Success('OK', 200, $type);
    }

    public function report(Request $request)
    {
        if (isset($request->params) && $request->params != "") {
            $decode = base64_decode($request->params);
            $arr = explode('&', $decode);

            $customer = $arr[0];
            $type = $arr[1];

            
            $params = InputParts::leftJoin('ekanban.ekanban_customermaster as customer', 'customer.CustomerCode_eKanban', '=', 'db_tbs.dbparts_item_part_tbl.cust_id')
            // ->leftJoin('db_tbs.dbparts_item_part_tbl_log as part_log', 'part_log.id_part', '=', 'db_tbs.dbparts_item_part_tbl.id')
            ->where(function ($on) use ($customer, $type){
                $on->where('db_tbs.dbparts_item_part_tbl.type', $type);
                $on->where('db_tbs.dbparts_item_part_tbl.cust_id', $customer);
            })
            ->select([
                'db_tbs.dbparts_item_part_tbl.*',
                'customer.CustomerName as cust_name',
            ])
            ->get();

            $log = InputParts::leftJoin('db_tbs.dbparts_item_part_tbl_log', 'db_tbs.dbparts_item_part_tbl_log.id_part', '=', 'db_tbs.dbparts_item_part_tbl.id')
            ->where(function ($on) use ($customer, $type){
                $on->where('db_tbs.dbparts_item_part_tbl.type', $type);
                $on->where('db_tbs.dbparts_item_part_tbl.cust_id', $customer);
                $on->where('db_tbs.dbparts_item_part_tbl_log.status', 'EDIT');
            })
            ->select([
                'db_tbs.dbparts_item_part_tbl.*',
                'db_tbs.dbparts_item_part_tbl_log.id_part',
                'db_tbs.dbparts_item_part_tbl_log.note',
                'db_tbs.dbparts_item_part_tbl_log.log_by',
                DB::raw('DATE_FORMAT(log_date, "%d/%m/%Y") as date_log'),
                'db_tbs.dbparts_item_part_tbl_log.value'
            ])
            ->get();

            $log_mark = [];
            $m = null;
            if ($log->isNotEmpty()) {
                $no = 0;
                foreach ($log as $l) {
                    $arr = json_decode($l->value);
                    $no = ++$no;
                    foreach($arr as $key => $val){
                        $log_mark[$l->id_part][$key] = $no;
                    }
                }
            }
            $arr_params = $params->toArray();
            $res = [];
            for ($x=0; $x < count($arr_params); $x++) {
                foreach ($arr_params[$x] as $key => $value) {
                    if (!empty($log_mark[$arr_params[$x]['id']][$key])) {
                        $res[$x][$key] = $arr_params[$x][$key] . '|<div class="rev"><p>' . $log_mark[$arr_params[$x]['id']][$key] .'</p></div>';// implode('&', $log_mark[$arr_params[$x]['id']][$key]) .'</p></div>';
                    }else{
                        $res[$x][$key] = $arr_params[$x][$key];
                    }
                }
            }

            if ($params->isEmpty()) {
                $request->session()->flash('message', 'Data tidak ditemukan!');
                return Redirect::back();
            }

            $pdf = PDF::loadView('tms.db_parts.report.template.report', compact('params', 'log', 'res'))->setPaper('a3', 'landscape');
            return $pdf->stream();
        }
    }
}
