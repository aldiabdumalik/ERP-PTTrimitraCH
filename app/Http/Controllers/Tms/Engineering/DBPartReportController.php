<?php

namespace App\Http\Controllers\TMS\Engineering;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DB_parts\InputParts;
use App\Models\Dbtbs\DB_parts\Parts;
use App\Models\Dbtbs\DB_parts\Projects;
use App\Models\Dbtbs\DB_parts\Revision;
use App\Models\Dbtbs\DB_parts\RevisionLogs;
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

    public function report($type, Request $request)
    {
        // $type = 6;
        $log_note = Revision::where('id_type', $type)
        ->orderBy('revision_number', 'ASC')
        ->get();

        $project = Projects::details($type)->first();

        $parts = Parts::with('children')->with('production')->where('db_tbs.dbparts_item_part_tbl.project_id', $type)->whereNull('parent_id')->get();
        // print_r($parts->toArray());die;
        // $parts = Parts::with('production')->where('db_tbs.dbparts_item_part_tbl.project_id', $type)->get();
        $arr_params = $parts->toArray();

        $revLogs = RevisionLogs::where('id_type', $type)
        ->orderBy('created_at', 'ASC')
        ->get();

        if ($revLogs->isEmpty()) {
            $request->session()->flash('message', 'Data revisi tidak ditemukan!');
            return Redirect::back();
        }

        $byLogType = $revLogs->groupBy('type_revision')->toArray();
        $log_mark = [];
        foreach ($byLogType as $key => $val) {
            foreach($val as $v){
                if ($key=='PART') {
                    foreach (json_decode($v['old_data']) as $oldKey => $old) {
                        foreach (json_decode($v['new_data']) as $newKey => $new) {
                            if ($oldKey == $newKey) {
                                $log_mark[$v['id_part']][$oldKey] = $v['revision_number'];
                            }
                        }
                    }
                }
            }
        }

        $res = [];
        $no = 1;
        $child = [];
        $ii = 1;
        $iii = 0.1;
        for ($x=0; $x < count($arr_params); $x++) {
            $res[$x]['no'] = $no;
            // $no++;
            foreach ($arr_params[$x] as $key => $value) {
                if (!empty($log_mark[$arr_params[$x]['id']][$key])) {
                    $res[$x][$key] = $arr_params[$x][$key] . '|<div class="rev"><p>' . $log_mark[$arr_params[$x]['id']][$key] .'</p></div>';// implode('&', $log_mark[$arr_params[$x]['id']][$key]) .'</p></div>';
                }else{
                    if ($key !== 'children') {
                        $res[$x][$key] = $arr_params[$x][$key];
                    }
                }
                if ($key == 'children' && !empty($arr_params[$x]['children'])) {
                    $iii = 0.1;
                    for ($xc=0; $xc < count($arr_params[$x]['children']); $xc++) { 
                        foreach ($arr_params[$x]['children'][$xc] as $keyChild => $valChild) {
                            $child[] = $arr_params[$x]['children'][$xc][$keyChild];
                            if (!empty($log_mark[$arr_params[$x]['children'][$xc]['id']][$keyChild])) {
                                $res[$x+$ii][$keyChild] = $arr_params[$x]['children'][$xc][$keyChild] . '|<div class="rev"><p>' . $log_mark[$arr_params[$x]['children'][$xc]['id']][$keyChild] .'</p></div>';// implode('&', $log_mark[$arr_params[$x]['id']][$keyChild]) .'</p></div>';
                            }else{
                                $res[$x+$ii][$keyChild] = $arr_params[$x]['children'][$xc][$keyChild];
                            }
                        }
                        $res[$x+$ii]['no'] = $no + $iii;
                        $ii++;
                        $iii += $iii;
                    }

                }
            }
            $no++;
        }
        // print_r($res);die;
        // print_r($log_mark[$arr_params[0]['children'][0]['id']]);die;
        $pdf = PDF::loadView('tms.db_parts.report.template.report', compact('res', 'project', 'log_note'))->setPaper('a3', 'landscape');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream();
    }

    public function _report(Request $request)
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
