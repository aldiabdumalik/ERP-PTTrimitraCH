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
        // $query =  DB::connection('oee')
        //     ->table('entry_thp_tbl AS t1')
        //     ->selectRaw('
        //         t1.*,
        //         (
        //             SELECT SUM(t2.thp_qty) FROM entry_thp_tbl t2
        //             WHERE LEFT(t2.thp_remark, 1) = 1 
        //             AND t1.production_code = t2.production_code
        //             AND t1.thp_date = t2.thp_date
        //         ) AS SHIFT_1,
        //         (
        //             SELECT SUM(t2.thp_qty) FROM entry_thp_tbl t2 
        //             WHERE LEFT(t2.thp_remark, 1) = 2 
        //             AND t1.production_code = t2.production_code
        //             AND t1.thp_date = t2.thp_date
        //         ) AS SHIFT_2
        //     ')
        //     ->groupByRaw('production_code, item_code, thp_date')
        //     ->get();
        $query =  DB::connection('oee')
            ->table('entry_thp_tbl AS t1')
            ->get();
        return DataTables::of($query)
            ->editColumn('closed', function($query){
                if ($query->closed != NULL) {
                    return date('d/m/Y', strtotime($query->closed));
                }else{
                    return '//';
                }
            })
            ->editColumn('thp_date', function($query){
                return date('d/m/Y', strtotime($query->thp_date));
            })
            ->editColumn('process', function($query){
                return $query->process_sequence_1.'/'.$query->process_sequence_2;
            })
            ->editColumn('shift', function($query){
                return substr($query->thp_remark, 0, 1);
            })
            ->editColumn('group', function($query){
                return substr($query->thp_remark, 1, 1);
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
        // $skrg = date('Y-m-d', strtotime('-2 days', strtotime( date('Y-m-d') )));
        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where(['id_thp' => $id])
            ->first();
        $production = DB::connection('oee')
            ->table('db_productioncode_tbl')
            ->select(
                'production_code',
                'item_code'
            )
            ->where('production_code', $query->production_code)
            ->where('code_status', 1)
            ->first();
        $lhp_where = [
            'production_code' => $production->production_code,
            'item_code' => $production->item_code,
            'date2' => $query->date
        ];
        $shift_1 = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as shift_1'))
            ->where($lhp_where)
            ->whereRaw('LEFT(remark, 1) = 1')
            ->first();
        $shift_2 = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as shift_2'))
            ->where($lhp_where)
            ->whereRaw('LEFT(remark, 1) = 2')
            ->first();
        if (!empty($query)) {
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditemukan!',
                'data' => $query,
                'lhp' => [
                    $shift_1,
                    $shift_2
                ]
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
        $production = DB::connection('oee')
            ->table('db_productioncode_tbl')
            ->select(
                'production_code',
                'item_code'
            )
            ->where('production_code', $data->production_code)
            ->where('code_status', 1)
            ->first();
        $lhp_where = [
            'production_code' => $production->production_code,
            'item_code' => $production->item_code,
            'date2' => $data->date
        ];

        $shift_1 = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as shift_1'))
            ->where($lhp_where)
            ->whereRaw('LEFT(remark, 1) = 1')
            ->first();
        $shift_2 = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as shift_2'))
            ->where($lhp_where)
            ->whereRaw('LEFT(remark, 1) = 2')
            ->first();

        $plan_thp = (int)$data->plan_1 + (int)$data->plan_2;
        $actual_thp = (int)$data->actual_1 + (int)$data->actual_2;
        $actual_lhp = (int)$shift_1->shift_1 + (int)$shift_2->shift_2;

        if ($plan_thp >= $actual_lhp && $actual_lhp != 0) {
            
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
                'customer_code',
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
        else{
            foreach ($query as $v) {
                $production = DB::connection('oee')
                    ->table('db_productioncode_tbl')
                    ->select(
                        'production_code',
                        'item_code'
                    )
                    ->where('production_code', $v->production_code)
                    ->where('code_status', 1)
                    ->first();
                $shift_1 = DB::connection('oee')
                    ->table('entry_lhp_tbl')
                    ->select(DB::raw('SUM(lhp_qty) as shift_1'))
                    ->where([
                        'production_code' => $production->production_code,
                        'item_code' => $production->item_code,
                        'date2' => $v->date
                    ])
                    ->whereRaw('LEFT(remark, 1) = 1')
                    ->first();
                $shift_2 = DB::connection('oee')
                    ->table('entry_lhp_tbl')
                    ->select(DB::raw('SUM(lhp_qty) as shift_2'))
                    ->where([
                        'production_code' => $production->production_code,
                        'item_code' => $production->item_code,
                        'date2' => $v->date
                    ])
                    ->whereRaw('LEFT(remark, 1) = 2')
                    ->first();
                $act_1 = ($shift_1->shift_1 != null) ? $shift_1->shift_1 : 0;
                $act_2 = ($shift_2->shift_2 != null) ? $shift_2->shift_2 : 0;
                $act_hour_fix = ($act_1 + $act_2)*$v->ct/3600;
                $update = DB::connection('oee')
                    ->table('entry_thp_tbl')
                    ->where('id_thp', $v->id_thp)
                    ->update([
                        'actual_1' => $act_1,
                        'actual_2' => $act_2,
                        'act_hour' => $act_hour_fix
                    ]);
            }
            $query = DB::connection('oee')
                ->table('entry_thp_tbl')
                ->select(
                    'id_thp', 
                    'production_code', 
                    'customer_code',
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
            ->first();
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
                    'note' => date('YmdHis').'-DEVELOPMENT'
                ]);
        }
        $waktu_tersedia = 480+420;
        $eff = 85/100;
        $max_loading1 = ($waktu_tersedia*$eff)/60;
        $max_loading2 = $max_loading1*41;
        $man_power = 41*2;
        $loading_time = $sum->total_plan_hour;
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

    public function getShiftGroupMachine(Request $request)
    {
        $query = [];
        if ($request->type == 'SHIFT') {
            $query = DB::connection('oee')
                ->table('oee_worktime_tbl')
                ->groupBy('oee_workshift')
                ->get();
        }elseif($request->type == 'GRUP'){
            $query = DB::connection('oee')
                ->table('db_employee_group_tbl')
                ->get();
        }elseif($request->type == 'MACHINE'){
            $query = DB::connection('oee')
                ->table('db_machinenumber_tbl')
                ->where([
                    'production_process' => $request->process,
                    'status' => 'ACTIVE'
                ])
                ->get();
        }
        if (count($query) <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Silahkan tambahkan parameter!',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $query
        ], 200);
    }

    private function _createTHP(Request $request)
    {
        $cd = explode('/', $request->thp_date);
        $date = $cd[2].'-'.$cd[1].'-'.$cd[0];

        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->insertGetId([
                'customer_code' => $request->customer_code,
                'production_code' => $request->production_code,
                'item_code' => $request->item_code,
                'part_number' => $request->part_number,
                'part_name' => $request->part_name,
                'part_type' => $request->part_type,
                'production_process' => $request->production_process,
                'route' => $request->route,
                'process_sequence_1' => $request->process_1,
                'process_sequence_2' => $request->process_2,
                'ct' => $request->ct,
                'plan' => $request->plan,
                'ton' => $request->ton,
                'time' => $request->time,
                'plan_hour' => $request->plan_hour,
                'thp_qty' => $request->thp_qty,
                'thp_remark' => $request->shift.$request->grup.'_'.$request->machine,
                'note' => $request->note,
                'apnormality' => $request->apnormal,
                'action_plan' => $request->action_plan,
                'thp_date' => $date,
                'user' => Auth::user()->FullName,
                'thp_written' => date('Y-m-d H:i:s')
            ]);
        $query2 = DB::connection('oee')
            ->table('entry_thp_tbl_log')
            ->insert([
                'id_thp' => $query,
                'production_code' => $request->production_code,
                'item_code' => $request->item_code,
                'remark' => $request->shift.$request->grup.'_'.$request->machine,
                'thp_date' => $date,
                'date_written' => date('Y-m-d'),
                'time_written' => date('H:i:s'),
                'status_change' => 'ADD',
                'user' => Auth::user()->FullName,
                'note' => date('YmdHis').'-DEV'
            ]);
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
                'customer_code' => $request->customer_code,
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
                'item_code',
                'process_sequence_1', 
                'process_sequence_2', 
                'process_detailname', 
                'customer_id',
                'ct_sph',
                'production_process'
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
        $where = $this->_whereProductionTableById($request, $query);
        $shift_1 = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as shift_1'))
            ->where($where[0])
            ->whereRaw('LEFT(remark, 1) = 1')
            ->first();
        $shift_2 = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as shift_2'))
            ->where($where[1])
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

    private function _whereProductionTableById(Request $request, $res)
    {
        $kemarin = date('Y-m-d', strtotime('-2 days', strtotime( date('Y-m-d') )));
        $kemarin2 = date('Y-m-d', strtotime('-3 days', strtotime( date('Y-m-d') )));
        if ($res->item_code != null) {
            $where_1 = [
                'production_code' => $request->post_production_code,
                'item_code' => $res->item_code,
                'date2' => $kemarin
            ];
            $where_2 = [
                'production_code' => $request->post_production_code,
                'item_code' => $res->item_code,
                'date2' => $kemarin2
            ];
        }else{
            $where_1 = [
                'production_code' => $request->post_production_code,
                'date2' => $kemarin
            ];
            $where_2 = [
                'production_code' => $request->post_production_code,
                'date2' => $kemarin2
            ];
        }
        return [
            $where_1,
            $where_2
        ];
    }

}
