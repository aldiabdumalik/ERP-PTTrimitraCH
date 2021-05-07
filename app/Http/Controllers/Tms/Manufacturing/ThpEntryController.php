<?php

namespace App\Http\Controllers\Tms\Manufacturing;

use App\Http\Controllers\Controller;
use App\Models\Oee\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ThpEntryController extends Controller
{
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
                ->select('id_thp', 'production_code', 'id_cust', 'part_name', 'part_type', 'route', 'process', 'actual_1', 'actual_2', 'plan_1', 'plan_2',)
                ->get();
            return DataTables::of($query)
            ->make(true);
    }

    public function getProductionTable(Request $request)
    {
        if($request->ajax()){
            if (empty($request->process) && empty($request->cust)){
                $where = [
                    'production_process' => 'PRESSING',
                    'code_status' => 1
                ];
            }elseif(empty($request->process) && !empty($request->cust)){
                $where = [
                    'production_process' => 'PRESSING',
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
                // ->limit(100)
                ->get();
            return DataTables::of($query)
            ->editColumn('process', function($query){
                return $query->process_sequence_1.'/'.$query->process_sequence_2;
            })
            ->make(true);
        }
    }
    
    public function createTHP(Request $request)
    {
        if($request->ajax()){
            if ($request->id_thp == 0) {
                $query = $this->_createTHP($request);
                $message = 'ditambahkan';
            }else{
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

    private function _createTHP(Request $request)
    {
        $act_hour = ((int)$request->actual_1 + (int)$request->actual_1)*(int)$request->ct/3600;
        $query = DB::connection('oee')
            ->table('entry_thp_tbl')
            ->insert([
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
                'act_hour' => $act_hour,
                'note' => $request->note,
                'apnormality' => $request->apnormal,
                'action_plan' => $request->action_plan,
                'status' => NULL,
                'user' => Auth::user()->FullName,
                'date' => date('Y-m-d')
            ]);
        // $return = [$query, true];
        return $query;
    }

    private function _updateTHP(Request $request)
    {
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
                // 'actual_lhp' => $request->actual_1.'.'.$request->actual_2,
                // 'act_hour' => $request->act_hour,
                'note' => $request->note,
                'apnormality' => $request->apnormal,
                'action_plan' => $request->action_plan,
                'status' => NULL,
                'user' => Auth::user()->FullName,
                'date' => date('Y-m-d')
            ]);
        return $query;
    }

}
