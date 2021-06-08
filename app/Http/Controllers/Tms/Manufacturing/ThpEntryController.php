<?php

namespace App\Http\Controllers\Tms\Manufacturing;

use App\Exports\ThpEntryExport;
use App\Http\Controllers\Controller;
use App\Imports\ThpEntryImport;
use App\Models\Oee\Customer;
use App\Models\Oee\ThpEntry;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
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
        if ($request->date_thp == null) {
            $thpdate = date('Y-m-d');
        }else{
            $cd = explode('/', $request->date_thp);
            $thpdate = $cd[2].'-'.$cd[1].'-'.$cd[0];
        }
        $query =  DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where('thp_date', $thpdate)
            ->whereNull('closed')
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
            ->editColumn('date_order', function($query){
                return date('Ymd', strtotime($query->thp_date));
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
        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where(['id_thp' => $id])
            ->first();
        if ($query->item_code != null) {
            $lhp_where = [
                'production_code' => $query->production_code,
                'item_code' => $query->item_code,
                'date2' => $query->thp_date
            ];
        }else{
            $lhp_where = [
                'production_code' => $query->production_code,
                'date2' => $query->thp_date
            ];
        }
        $lhp = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as lhp_qty'))
            ->where($lhp_where)
            ->first();
        $lhp_qty = ($lhp->lhp_qty != null) ? $lhp->lhp_qty : 0;
        if (isset($lhp)) {
            $outstanding_qty =  $lhp_qty - $query->thp_qty;
            $update = ThpEntry::where('id_thp', $query->id_thp)
                ->update([
                    'lhp_qty' => $lhp_qty,
                    'outstanding_qty' => $outstanding_qty
                ]);
        }
        if (!empty($query)) {
            return response()->json([
                'status' => true,
                'data' => $query,
                'lhp' => $lhp
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

        $outstanding_qty = 0;
        if ($data->item_code != null) {
            $lhp_where = [
                'production_code' => $data->production_code,
                'item_code' => $data->item_code,
                'date2' => $data->thp_date
            ];
        }else{
            $lhp_where = [
                'production_code' => $data->production_code,
                'date2' => $data->thp_date
            ];
        }
        $lhp = DB::connection('oee')
            ->table('entry_lhp_tbl')
            ->select(DB::raw('SUM(lhp_qty) as lhp_qty'))
            ->where($lhp_where)
            ->first();
        $lhp_qty = ($lhp->lhp_qty != null) ? $lhp->lhp_qty : 0;
        $outstanding_qty =  $lhp_qty - $data->thp_qty;
        $update = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where('id_thp', $data->id_thp)
            ->update([
                'lhp_qty' => $lhp_qty,
                'outstanding_qty' => $outstanding_qty
            ]);

        $thp = ThpEntry::where('id_thp', $request->id)
            ->first();
        if (isset($thp)) {
            $persentase = round(($thp->lhp_qty / $thp->thp_qty)*100);
            $getsetting = DB::connection('oee')
                ->table('entry_thp_tbl_setting')
                ->select('value_setting')
                ->where('id', 1)
                ->first();
            $min_persen = $getsetting->value_setting;
            $outstanding_qty = ($thp->outstanding_qty != null) ? $thp->outstanding_qty : ($thp->lhp_qty - $thp->thp_qty);
            if ($persentase >= $min_persen) {
                $update = ThpEntry::where('production_code', $thp->production_code)
                    ->where('thp_date', $thp->thp_date)
                    ->update([
                        'closed' => date('Y-m-d'),
                        'status' => 'CLOSED'
                    ]);
                $query2 = DB::connection('oee')
                    ->table('entry_thp_tbl_log')
                    ->insert([
                        'id_thp' => $data->id_thp,
                        'production_code' => $data->production_code,
                        'item_code' => $data->item_code,
                        'remark' => $data->thp_remark,
                        'thp_date' => $data->thp_date,
                        'date_written' => date('Y-m-d'),
                        'time_written' => date('H:i:s'),
                        'status_change' => 'CLOSE',
                        'user' => Auth::user()->FullName,
                        'note' => date('YmdHis').'-DEV'
                    ]);
                return response()->json([
                    'status' => true,
                    'message' => 'THP Entry CLOSED!',
                ], 201);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Maaf THP Entry tidak dapat diclose karena kurang dari '.$min_persen.'%, silahkan coba kembali!',
                ], 401);
            }
        }
        return response()->json([
            'status' => false,
            'message' => 'Maaf THP Entry tidak dapat diclose, silahkan coba kembali!',
        ], 401);
    }

    public function printThpentry(Request $request)
    {
        $decode = base64_decode($request->print);
        $arr_params = explode('&', $decode);
        
        if ($arr_params[2] == 'reportDate') {
            $params = $this->_reportByDate($request);
            if (count($params['data']) <= 0) {
                $request->session()->flash('msg', 'Data tidak ditemukan!');
                return Redirect::back();
            }
            $pdf = PDF::loadView('tms.manufacturing.thp_entry._report.reportThpall', $params)->setPaper('a3', 'landscape');
            return $pdf->stream();
        }else{
            $params = $this->_reportSummary($request);
            if (count($params['data']) <= 0) {
                $request->session()->flash('msg', 'Data tidak ditemukan!');
                return Redirect::back();
            }
            $pdf = PDF::loadView('tms.manufacturing.thp_entry._report.reportThpsummary', $params)->setPaper('a3', 'landscape');
            return $pdf->stream();
        }
    }

    public function importToDB(Request $request)
    {
        $getsetting = DB::connection('oee')
                ->table('entry_thp_tbl_setting')
                ->select('value_setting')
                ->where('id', 1)
                ->first();
        $min_persen = $getsetting->value_setting;
        $validated = $request->validate([
            'thp_import_file' => 'mimes:xls,xlsx,csv|max:25000',
        ]);
        $cd = explode('/', $request->thp_import_tanggal);
        $date = $cd[2].'-'.$cd[1].'-'.$cd[0];

        if ($request->hasFile('thp_import_file')) {
            Excel::import(new ThpEntryImport($date, $min_persen), $request->file('thp_import_file'));
        }
        return response()->json([
            'status' => true,
            'message' => 'Excel berhasil di convert'
        ], 200);
    }

    public function settingThpEntry(Request $request)
    {
        if ($request->type == 'GET') {
            $res = $this->_getSetting($request);
            if (isset($res)) {
                return response()->json([
                    'status' => true,
                    'data' => $res
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Setting tidak ditemukan'
                ], 404);
            }
        }elseif ($request->type == 'POST') {
            $data = (object)[
                'where' => [
                    'id' => $request->id
                ],
                'data' => [
                    'name_setting' => $request->setting_name,
                    'value_setting' => $request->setting_value,
                    'user' => Auth::user()->FullName
                ]
            ];
            $res = $this->_updateSetting($data);
            if ($res) {
                return response()->json([
                    'status' => true,
                    'message' => 'Setting berhasil di perbaharui'
                ], 201);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Setting gagal di perbaharui, silahkan coba kembali'
                ], 401);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Perintah gagal'
            ], 401);
        }
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

        $check = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where('production_code', $request->production_code)
            ->where('thp_date', $date)
            ->first();
        if (isset($check)) {
            return false;
        }

        $thp = ThpEntry::where('production_code', $request->production_code)
            ->where('closed', NULL)
            ->orderBy('thp_date', 'desc')
            ->first();
        if (isset($thp)) {
            $persentase = round(($thp->lhp_qty / $thp->thp_qty)*100);
            $getsetting = DB::connection('oee')
                ->table('entry_thp_tbl_setting')
                ->select('value_setting')
                ->where('id', 1)
                ->first();
            $min_persen = $getsetting->value_setting;
            $outstanding_qty = ($thp->outstanding_qty != null) ? $thp->outstanding_qty : ($thp->lhp_qty - $thp->thp_qty);
            if ($persentase <= $min_persen) {
                $thp_qty = $request->thp_qty + abs($outstanding_qty);
            }else{
                $thp_qty = $request->thp_qty;
            }
            $update = ThpEntry::where('production_code', $thp->production_code)
                ->where('thp_date', $thp->thp_date)
                ->update([
                    'closed' => date('Y-m-d'),
                    'status' => 'CLOSED'
                ]);
        }else{
            $thp_qty = $request->thp_qty;
        }

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
                'thp_qty' => $thp_qty,
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
        $cd = explode('/', $request->thp_date);
        $date = $cd[2].'-'.$cd[1].'-'.$cd[0];

        $check = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where('production_code', $request->production_code)
            ->where('thp_date', $date)
            ->where('id_thp', '!=', $request->id_thp)
            ->first();
        if (isset($check)) {
            return false;
        }

        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where('id_thp', $request->id_thp)
            ->update([
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
                'id_thp' => $request->id_thp,
                'production_code' => $request->production_code,
                'item_code' => $request->item_code,
                'remark' => $request->shift.$request->grup.'_'.$request->machine,
                'thp_date' => $date,
                'date_written' => date('Y-m-d'),
                'time_written' => date('H:i:s'),
                'status_change' => 'EDIT',
                'user' => Auth::user()->FullName,
                'note' => date('YmdHis').'-DEV'
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
                'code_status' => 1
            ];
        }elseif(empty($request->process) && !empty($request->cust)){
            $where = [
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

    private function _reportByDate(Request $request)
    {
        $decode = base64_decode($request->print);
        $arr_params = explode('&', $decode);

        $cd = explode('/', $arr_params[0]);
        $fixDate = $cd[2].'-'.$cd[1].'-'.$cd[0];
        $production_process = $arr_params[1];

        $check = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where('thp_date', $fixDate)
            ->where('production_process', $production_process)
            ->get();
        if (count($check) <= 0) {
            // $request->session()->flash('msg', 'Data tidak ditemukan!');
            // return Redirect::back();
        }
        else{
            $outstanding_qty = 0;
            foreach ($check as $v) {
                if ($v->item_code != null) {
                    $lhp_where = [
                        'production_code' => $v->production_code,
                        'item_code' => $v->item_code,
                        'date2' => $v->thp_date
                    ];
                }else{
                    $lhp_where = [
                        'production_code' => $v->production_code,
                        'date2' => $v->thp_date
                    ];
                }
                $lhp = DB::connection('oee')
                    ->table('entry_lhp_tbl')
                    ->select(DB::raw('SUM(lhp_qty) as lhp_qty'))
                    ->where($lhp_where)
                    ->first();
                $lhp_qty = ($lhp->lhp_qty != null) ? $lhp->lhp_qty : 0;
                $outstanding_qty =  $lhp_qty - $v->thp_qty;
                $update = DB::connection('oee')
                    ->table('entry_thp_tbl')
                    ->where('id_thp', $v->id_thp)
                    ->update([
                        'lhp_qty' => $lhp_qty,
                        'outstanding_qty' => $outstanding_qty
                    ]);
            }
        }
        $query =  DB::connection('oee')
            ->table('entry_thp_tbl AS t1')
            ->selectRaw($this->_QueryRawReport())
            ->where('thp_date', $fixDate)
            ->where('production_process', $production_process)
            ->groupByRaw('production_code, item_code, thp_date')
            ->get();
        $sum = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->selectRaw('SUM(plan) as total_plan, round(SUM(plan_hour), 2) as total_plan_hour')
            ->where('thp_date', $fixDate)
            ->where('production_process', $production_process)
            ->first();
        foreach ($check as $v) {
            $log_print = DB::connection('oee')
                ->table('entry_thp_tbl_log')
                ->insert([
                    'id_thp' => $v->id_thp,
                    'production_code' => $v->production_code,
                    'item_code' => $v->item_code,
                    'remark' => $v->thp_remark,
                    'thp_date' => $v->thp_date,
                    'date_written' => date('Y-m-d'),
                    'time_written' => date('H:i:s'),
                    'status_change' => 'PRINT',
                    'user' => Auth::user()->FullName,
                    'note' => date('YmdHis').'-DEV'
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
            'total_mp' => $total_mp,
            'date1' => $fixDate,
            'dept' => $production_process
        ];
        return $params;
    }

    private function _reportSummary(Request $request)
    {
        $decode = base64_decode($request->print);
        $arr_params = explode('&', $decode);

        $cd_1 = explode('/', $arr_params[0]);
        $fixDate_1 = $cd_1[2].'-'.$cd_1[1].'-'.$cd_1[0];

        $cd_2 = explode('/', $arr_params[1]);
        $fixDate_2 = $cd_2[2].'-'.$cd_2[1].'-'.$cd_2[0];

        $production_process = $arr_params[2];

        $check = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->where('thp_date', '>=',$fixDate_1)
            ->where('thp_date', '<=',$fixDate_2)
            ->where('production_process', $production_process)
            ->get();
        if (count($check) <= 0) {
            // $request->session()->flash('msg', 'Data tidak ditemukan!');
            // return Redirect::back();
        }
        else{
            $outstanding_qty = 0;
            foreach ($check as $v) {
                if ($v->item_code != null) {
                    $lhp_where = [
                        'production_code' => $v->production_code,
                        'item_code' => $v->item_code,
                        'date2' => $v->thp_date
                    ];
                }else{
                    $lhp_where = [
                        'production_code' => $v->production_code,
                        'date2' => $v->thp_date
                    ];
                }
                $lhp = DB::connection('oee')
                    ->table('entry_lhp_tbl')
                    ->select(DB::raw('SUM(lhp_qty) as lhp_qty'))
                    ->where($lhp_where)
                    ->first();
                $lhp_qty = ($lhp->lhp_qty != null) ? $lhp->lhp_qty : 0;
                $outstanding_qty =  $lhp_qty - $v->thp_qty;
                $update = DB::connection('oee')
                    ->table('entry_thp_tbl')
                    ->where('id_thp', $v->id_thp)
                    ->update([
                        'lhp_qty' => $lhp_qty,
                        'outstanding_qty' => $outstanding_qty
                    ]);
            }
        }
        $query =  DB::connection('oee')
            ->table('entry_thp_tbl AS t1')
            ->selectRaw($this->_QueryRawReport())
            ->where('thp_date', '>=',$fixDate_1)
            ->where('thp_date', '<=',$fixDate_2)
            ->where('production_process', $production_process)
            ->groupByRaw('production_code, item_code, thp_date')
            ->get();
        $sum = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->selectRaw('SUM(plan) as total_plan, round(SUM(plan_hour), 2) as total_plan_hour')
            ->where('thp_date', '>=',$fixDate_1)
            ->where('thp_date', '<=',$fixDate_2)
            ->where('production_process', $production_process)
            ->first();
        foreach ($check as $v) {
            $log_print = DB::connection('oee')
                ->table('entry_thp_tbl_log')
                ->insert([
                    'id_thp' => $v->id_thp,
                    'production_code' => $v->production_code,
                    'item_code' => $v->item_code,
                    'remark' => $v->thp_remark,
                    'thp_date' => $v->thp_date,
                    'date_written' => date('Y-m-d'),
                    'time_written' => date('H:i:s'),
                    'status_change' => 'PRINT',
                    'user' => Auth::user()->FullName,
                    'note' => date('YmdHis').'-DEV'
                ]);
        }

        $params = [
            'data' => $query,
            'sum' => $sum,
            'date1' => $fixDate_1,
            'dept' => $production_process
        ];
        return $params;
    }

    private function _getSetting(Request $request)
    {
        $query = DB::connection('oee')
            ->table('entry_thp_tbl_setting')
            ->where('id', $request->id)
            ->first();
        return $query;
    }

    private function _updateSetting($data)
    {
        $query = DB::connection('oee')
            ->table('entry_thp_tbl_setting')
            ->where($data->where)
            ->update($data->data);
        return $query;
    }

    private function _QueryRawReport()
    {
        return '
            t1.*,
            IFNULL((
                SELECT t2.lhp_qty FROM entry_lhp_tbl t2 
                WHERE LEFT(t2.remark, 1) = 1 
                AND t1.production_code = t2.production_code
                AND t1.thp_date = t2.date2
                LIMIT 1
            ), 0) AS LHP_1,
            IFNULL((
                SELECT t2.lhp_qty FROM entry_lhp_tbl t2 
                WHERE LEFT(t2.remark, 1) = 2 
                AND t1.production_code = t2.production_code
                AND t1.thp_date = t2.date2
                LIMIT 1
            ), 0) AS LHP_2,
            ROUND((t1.lhp_qty/t1.thp_qty)*100) AS persentase,
            ROUND((t1.lhp_qty * t1.ct/3600), 2) AS act_hour_new,
            IFNULL((t1.lhp_qty - t1.thp_qty), 0) AS outstanding_beta
        ';
    }

}
