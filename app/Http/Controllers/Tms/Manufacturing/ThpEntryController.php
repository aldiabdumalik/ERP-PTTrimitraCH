<?php

namespace App\Http\Controllers\Tms\Manufacturing;

use App\Http\Controllers\Controller;
use App\Models\Oee\Customer;
use App\Models\Oee\ThpEntry;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class ThpEntryController extends Controller
{
    public function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $customer = Customer::orderBy('customer_id', 'asc')->get();
        $getDate = Carbon::now()->format('d/m/Y');
        $getDate1 =  Carbon::now()->format('Y/m');
        return view('tms.manufacturing.thp_entry/index', compact('getDate','getDate1','customer'));
    }

    public function getThpTable(Request $request)
    {
        $query = DB::connection('oee')
                ->table('entry_thp_tbl')
                ->select(
                    'id_thp', 
                    'production_code', 
                    'id_cust', 
                    'part_name', 
                    'part_type', 
                    'plan', 
                    'route', 
                    'process', 
                    'actual_1', 
                    'actual_2', 
                    'plan_1', 
                    'plan_2', 
                    'status',
                    'closed',
                    'printed',
                    'date',
                    DB::raw('(plan_1 + plan_2) as total_plan'),
                    DB::raw('(actual_1 + actual_2) as total_actual'),
                    DB::raw('round((actual_1 + actual_2)/plan, 2) as persentase'),
                )
                ->get();
        return DataTables::of($query)
            ->editColumn('closed', function($query){
                if ($query->closed != NULL) {
                    return date('d/m/Y', strtotime($query->closed));
                }else{
                    return '//';
                }
            })
            ->editColumn('date', function($query){
                return date('d/m/Y', strtotime($query->date));
            })
            ->addColumn('action', function($query){
                return view('tms.manufacturing.thp_entry.action._actionTableIndex', [
                    'data' => $query,
                ]);
            })->rawColumns(['action'])
            ->make(true);
    }

    public function getProductionTable(Request $request)
    {
        if($request->ajax()){
            if (isset($request->post_production_code)) {
                $data = $this->_getProductionTableById($request);
                if ($data[0]->shift_1 == null && $data[1]->shift_2 == null) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data LHP tidak ditemukan',
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'data' => $data,
                ], 200);
            }else{
                return $this->_getProductionTable($request);
            }
        }
    }

    public function getLogThp(Request $request)
    {
        $query = DB::connection('oee')
                ->table('entry_thp_tbl_log')
                ->where('id_thp', $request->id)
                ->orderBy('date_written', 'DESC')
                ->get();
        return DataTables::of($query)
            ->editColumn('date_written', function($query) {
                return date('d/m/Y', strtotime($query->date_written));
            })
            ->make(true);
    }
    
    public function createTHP(Request $request)
    {
        if($request->ajax()){
            if ($request->id_thp == 0) {
                $query = $this->_createTHP($request);
                $message = 'ditambahkan';
            }else{
                $check = ThpEntry::where('closed', '!=', NULL)->where('id_thp', $request->id_thp)->first();
                if (isset($check)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data yang sudah di close tidak bisa di edit kembali!',
                        'data' => $check
                    ], 401);
                    exit();
                }
                $query = $this->_updateTHP($request);
                $message = 'diperbaharui';
            }
            if ($query){
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil '.$message.'!'
                ], 201);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data gagal '.$message.'! periksa kembali form Anda'
                ], 401);
            }
        }
    }

    public function editThpTable(Request $request, $id)
    {
        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where(['id_thp' => $id])
            ->get();
        if (!empty($query)) {
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditemukan!',
                'data' => $query[0]
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data gagal ditemukan!'
            ], 404);
        }
    }

    public function closeThpEntry(Request $request)
    {
        $data = ThpEntry::where('id_thp', $request->id)->first();
        $plan_thp = (int)$data->plan_1 + (int)$data->plan_2;
        $actual_thp = (int)$data->actual_1 + (int)$data->actual_2;
        if ($plan_thp == $actual_thp || $plan_thp >= $actual_thp) {
            
            ThpEntry::where('id_thp', $data->id_thp)
                ->update([
                    'status' => 'CLOSE',
                    'closed' => date('Y-m-d'),
                    'note' => $request->note
                ]);

            $query2 = DB::connection('oee')
                ->table('entry_thp_tbl_log')
                ->insert([
                    'id_thp' => $data->id_thp,
                    'date_written' => date('Y-m-d'),
                    'time_written' => date('H:i:s'),
                    'status_change' => 'CLOSE',
                    'user' => Auth::user()->FullName,
                    'note' => $request->note
                ]);
            return response()->json([
                'status' => true,
                'message' => 'THP Entry CLOSED!',
            ], 201);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Maaf THP Entry harus balance atau THP lebih besar dari Actual LHP, silahkan coba kembali!',
            ], 401);
        }
    }

    public function printThpentry(Request $request)
    {
        $decode = base64_decode($request->print);
        $arr_params = explode('&', $decode);
        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->select(
                'id_thp', 
                'production_code', 
                'id_cust',
                'part_name',
                'part_type',
                'plan',
                'ct',
                'route',
                'ton',
                'process',
                'time',
                'plan_hour',
                'plan_1',
                'plan_2',
                'actual_1',
                'actual_2',
                'act_hour',
                'note',
                'apnormality',
                'action_plan',
                'status',
                'closed',
                'printed',
                'date',
                DB::raw('round((actual_1 + actual_2)/plan, 2) as persentase'),
            )
            ->where('date', '>=', $arr_params[0])
            ->where('date', '<=', $arr_params[1])
            ->where('production_process', '=', $arr_params[2])
            ->get();
        if (count($query) <= 0) {
            $request->session()->flash('msg', 'Data tidak ditemukan!');
            return Redirect::back();
        }
        $sum = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->select(
                DB::raw('
                    SUM(plan) as total_plan, 
                    round(SUM(plan_hour), 2) as total_plan_hour, 
                    SUM(plan_1) as total_plan_1, 
                    SUM(plan_2) as total_plan_2, 
                    SUM(actual_1) as total_actual_1, 
                    SUM(actual_2) as total_actual_2,
                    round((SUM(actual_1) + SUM(actual_2))/SUM(plan), 2) as total_persentase,
                    round(SUM(act_hour), 2) as total_act_hour
                '),
            )
            ->where('date', '>=', $arr_params[0])
            ->where('date', '<=', $arr_params[1])
            ->where('production_process', '=', $arr_params[2])
            ->get();
        // $printed = ThpEntry::where('date', '>=', $request->thp_print_dari)->where('date', '<=', $request->thp_print_sampai)->update(['printed' => date('Y-m-d')]);
        $printed = ThpEntry::where('date', '>=', $arr_params[0])
            ->where('date', '<=', $arr_params[1])
            ->where('production_process', '=', $arr_params[2])
            ->update(['printed' => date('Y-m-d')]);
        foreach ($query as $v) {
            $log_print = DB::connection('oee')
                ->table('entry_thp_tbl_log')
                ->insert([
                    'id_thp' => $v->id_thp,
                    'date_written' => date('Y-m-d'),
                    'time_written' => date('H:i:s'),
                    'status_change' => 'PRINT',
                    'user' => Auth::user()->FullName,
                    'note' => date('YmdHis').'-TRIAL'
                ]);
        }
        $waktu_tersedia = 480+420;
        $eff = 85/100;
        $max_loading1 = ($waktu_tersedia*$eff)/60;
        $max_loading2 = $max_loading1*41;
        $man_power = 41*2;
        $loading_time = $sum[0]->total_plan_hour;
        $total_mp = round(($loading_time/$max_loading1)*2);

        $params = [
            'data' => $query,
            'sum' => $sum,
            'waktu_tersedia' => $waktu_tersedia,
            'eff' => $eff,
            'max_loading1' => $max_loading1,
            'max_loading2' => $max_loading2,
            'man_power' => $man_power,
            'loading_time' => $loading_time,
            'total_mp' => $total_mp
        ];

        // return view('tms.manufacturing.thp_entry._report.reportThpall', $params);
        $pdf = PDF::loadView('tms.manufacturing.thp_entry._report.reportThpall', $params)->setPaper('a3', 'landscape');;
        return $pdf->stream();
    }

    private function _createTHP(Request $request)
    {
        $act_hour = ((int)$request->actual_1 + (int)$request->actual_1)*(int)$request->ct/3600;
        $plan_thp = (int)$request->plan_1 + (int)$request->plan_2;
        $actual_thp = (int)$request->actual_1 + (int)$request->actual_2;
        if ($plan_thp == $actual_thp) {
            $status = 'CLOSE';
            $closed = date('Y-m-d');
        }else{
            $status = NULL;
            $closed = NULL;
        }

        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->insertGetId([
                'production_code' => $request->production_code,
                'id_cust' => $request->customer_code,
                'part_number' => $request->part_number,
                'part_name' => $request->part_name,
                'part_type' => $request->part_type,
                'plan' => $request->plan,
                'ct' => $request->ct,
                'route' => $request->route,
                'ton' => $request->ton,
                'process' => $request->process_1.'/'.$request->process_2,
                'production_process' => 'PRESSING',
                'time' => $request->time,
                'plan_hour' => $request->plan_hour,
                'plan_1' => $request->plan_1,
                'plan_2' => $request->plan_2,
                'actual_1' => $request->actual_1,
                'actual_2' => $request->actual_2,
                'act_hour' => round($act_hour, 2),
                'note' => $request->note,
                'apnormality' => $request->apnormal,
                'action_plan' => $request->action_plan,
                'status' => $status,
                'closed' => $closed,
                'user' => Auth::user()->FullName,
                'date' => date('Y-m-d')
            ]);
        $query2 = DB::connection('oee')
            ->table('entry_thp_tbl_log')
            ->insert([
                'id_thp' => $query,
                'date_written' => date('Y-m-d'),
                'time_written' => date('H:i:s'),
                'status_change' => 'ADD',
                'user' => Auth::user()->FullName,
                'note' => date('YmdHis').'-TRIAL'
            ]);
        // $return = [$query, true];
        return $query2;
    }

    private function _updateTHP(Request $request)
    {
        $act_hour = ((int)$request->actual_1 + (int)$request->actual_1)*(int)$request->ct/3600;
        $plan_thp = (int)$request->plan_1 + (int)$request->plan_2;
        $actual_thp = (int)$request->actual_1 + (int)$request->actual_2;
        if ($plan_thp == $actual_thp) {
            $status = 'CLOSE';
            $closed = date('Y-m-d');
        }else{
            $status = NULL;
            $closed = NULL;
        }

        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where('id_thp', $request->id_thp)
            ->update([
                'production_code' => $request->production_code,
                'id_cust' => $request->customer_code,
                'part_number' => $request->part_number,
                'part_name' => $request->part_name,
                'part_type' => $request->part_type,
                'plan' => $request->plan,
                'ct' => $request->ct,
                'route' => $request->route,
                'ton' => $request->ton,
                'process' => $request->process_1.'/'.$request->process_2,
                'production_process' => 'PRESSING',
                'time' => $request->time,
                'plan_hour' => $request->plan_hour,
                'plan_1' => $request->plan_1,
                'plan_2' => $request->plan_2,
                'actual_1' => $request->actual_1,
                'actual_2' => $request->actual_2,
                'act_hour' => round($act_hour, 2),
                'note' => $request->note,
                'apnormality' => $request->apnormal,
                'action_plan' => $request->action_plan,
                'status' => $status,
                'closed' => $closed,
                'user' => Auth::user()->FullName,
                'date' => date('Y-m-d')
            ]);
        $query2 = DB::connection('oee')
            ->table('entry_thp_tbl_log')
            ->insert([
                'id_thp' => $request->id_thp,
                'date_written' => date('Y-m-d'),
                'time_written' => date('H:i:s'),
                'status_change' => 'EDIT',
                'user' => Auth::user()->FullName,
                'note' => date('YmdHis').'-TRIAL'
            ]);
        return $query;
    }

    private function _getProductionTable(Request $request)
    {
        $where = $this->_whereProductionTable($request);
        $query = DB::connection('oee')
            ->table('db_productioncode_tbl')
            ->select(
                'production_code', 
                'part_number', 
                'part_name', 
                'part_type', 
                'process_sequence_1', 
                'process_sequence_2', 
                'process_detailname', 
                'customer_id',
                'ct_sph'
            )
            ->where($where)
            ->get();
        return DataTables::of($query)
        ->editColumn('process', function($query){
            return $query->process_sequence_1.'/'.$query->process_sequence_2;
        })
        ->make(true);
    }

    private function _getProductionTableById(Request $request)
    {
        $query = DB::connection('oee')
            ->table('db_productioncode_tbl')
            ->select(
                'production_code',
                'item_code'
            )
            ->where('production_code', $request->post_production_code)
            ->where('code_status', 1)
            ->first();

        $kemarin = date('Y-m-d', strtotime('-1 days', strtotime( date('Y-m-d') )));
        $shift_1 = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as shift_1'))
            ->where('production_code', $request->post_production_code)
            // ->where('item_code', $query->item_code)
            ->where('date2', $kemarin)
            ->whereRaw('LEFT(remark, 1) = 1')
            ->first();
        $shift_2 = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as shift_2'))
            ->where('production_code', $request->post_production_code)
            // ->where('item_code', $query->item_code)
            ->where('date2', $kemarin)
            ->whereRaw('LEFT(remark, 1) = 2')
            ->first();
        return [
            $shift_1,
            $shift_2
        ];
    }

    private function _whereProductionTable(Request $request)
    {
        if (empty($request->process) && empty($request->cust)){
            $where = [
                // 'production_process' => 'PRESSING',
                'code_status' => 1
            ];
        }elseif(empty($request->process) && !empty($request->cust)){
            $where = [
                // 'production_process' => 'PRESSING',
                'customer_id' => $request->cust,
                'code_status' => 1
            ];
        }elseif(!empty($request->process) && empty($request->cust)){
            $where = [
                'production_process' => $request->process,
                'code_status' => 1
            ];
        }else{
            $where = [
                'production_process' => $request->process,
                'customer_id' => $request->cust,
                'code_status' => 1
            ];
        }
        return $where;
    }

}
