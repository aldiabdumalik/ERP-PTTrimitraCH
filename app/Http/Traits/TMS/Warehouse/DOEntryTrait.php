<?php
namespace App\Http\Traits\TMS\Warehouse;

use App\Models\Dbtbs\DoEntry;
use App\Models\Dbtbs\DoEntrySetting;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Redirect;

trait DoEntryTrait {

    protected function headerToolsBranch(Request $request)
    {
        $query = DB::connection('db_tbs')
            ->table('branch')
            ->selectRaw('Branch as code, descript as name')
            ->where('status', 'ACTIVE')
            ->get();
        return $query;
    }

    protected function headerToolsTableSetting(Request $request)
    {
        $data = [];
        $setting = $request->setting;
        $query = DoEntrySetting::where(function ($query){
            $query->where('user', Auth::user()->FullName);
        })->orderBy('idx', 'asc')->get();
        if (!$query->isEmpty()) {
            $delete_first = DoEntrySetting::where('user', Auth::user()->FullName)->delete();
        }
        for ($i=0; $i < count($setting); $i++) { 
            $data[] = [
                'title' => $setting[$i]['title'],
                'data' => $setting[$i]['data'],
                'user' => Auth::user()->FullName,
                'status' => $setting[$i]['status_ori'],
                'idx' => $setting[$i]['idx']
            ];
        }
        $insert = DoEntrySetting::insert($data);
        return $data;
    }

    protected function headerToolsWarehouse(Request $request)
    {
        if ($request->branch != null) {
            $where = [
                'branch' => $request->branch,
                'status' => 'ACTIVE'
            ];
        }else{
            $where = [
                'status' => 'ACTIVE'
            ];
        }
        $query = DB::connection('db_tbs')
            ->table('sys_warehouse')
            ->selectRaw('warehouse_id as code, descript as name')
            ->where($where)
            ->get();
        return $query;
    }

    protected function headerToolsCustomer(Request $request)
    {
        $query = 
            DB::connection('ekanban')
            ->table('ekanban_customermaster')
            ->selectRaw('CustomerCode_eKanban as code, CustomerName as name')
            ->get();
        return $query;
    }

    protected function headerToolsCustomerClick(Request $request)
    {
        $query = 
            DB::connection('db_tbs')
            ->table('sys_do_address')
            ->selectRaw('id_do as code, cust_name as name, do_addr1, do_addr2, do_addr3, do_addr4')
            ->where('cust_code', $request->cust_code)
            ->where('status', 'ACTIVE')
            ->first();
        if (isset($query)) {
            return response()->json([
                'status' => true,
                'content' => $query
            ], 200);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data tidak ditemukan!'
        ], 404);
    }

    protected function headerToolsCustomerAddr(Request $request)
    {
        $query = 
            DB::connection('db_tbs')
            ->table('sys_do_address')
            ->selectRaw('id_do as code, cust_name as name, do_addr1, do_addr2, do_addr3, do_addr4')
            ->where('cust_code', $request->cust_code)
            ->where('status', 'ACTIVE')
            ->get();
        return $query;
    }

    protected function headerToolsItem(Request $request)
    {
        $query = 
            DB::connection('db_tbs')
            ->table('item')
            ->selectRaw('ITEMCODE, PART_NO, DESCRIPT, UNIT')
            ->where('CUSTCODE', $request->cust_code)
            ->get();
        return $query;
    }

    protected function headerToolsLog(Request $request)
    {
        $query = 
            DB::connection('db_tbs')
            ->table('entry_cl_log')
            ->where('do_no', $request->cl_no)
            ->get();
        return $query;
    }

    protected function headerToolsDoEntryNo(Request $request)
    {
        $reference = 
            DB::connection('db_tbs')
            ->table('sys_number')
            ->selectRaw('concat(right(year(NOW()),2),DATE_FORMAT(NOW(),"%m")) as ref')
            ->first();
        $do_no = 
            DB::connection('db_tbs')
            ->table('sys_number')
            ->where('label', 'DO NUMBER')
            ->select('contents')
            ->first();
        $a = substr($do_no->contents, 0, 4);
        $b = $reference->ref;
        if ($a == $b){
            $cekDoNo = $do_no->contents + 1;
            $cekDoTbl = 
                DoEntry::select('do_no')
                ->where('do_no', $cekDoNo)
                ->get();  
            if ($cekDoTbl->isEmpty()){
                $DoNo = $cekDoNo;
                return $DoNo;
            }else{
                do{
                    $cekDoNo++;
                    $cekDoTbl = 
                        DoEntry::select('do_no')
                        ->where('do_no', $cekDoNo)
                        ->get();           
                }while (!$cekDoTbl->isEmpty());
                $DoNo = $cekDoNo;
                return $DoNo;
            }
        } else {
            $cekDoNo  = $b;
            $cekDoNo  .= '0001';
            $cekDoTbl = 
                DoEntry::select('do_no')
                ->where('do_no', $cekDoNo)
                ->get();
            if ($cekDoTbl->isEmpty()){
                $DoNo = $cekDoNo;
                return $DoNo;
            }else{
                do{
                    $cekDoNo++;
                    $cekDoTbl = 
                        DoEntry::select('do_no')
                        ->where('do_no',$cekDoNo)
                        ->get();
                }while (!$cekDoTbl->isEmpty());
                $DoNo = $cekDoNo;
                return $DoNo;
            }
        }  
    }

    protected function _Error($message=null, $code=401, $content=null)
    {
        return response()->json([
            'status' => false,
            'content' => $content,
            'message' => $message
        ], $code);
    }

    protected function _Success($message=null, $code=200, $content=null)
    {
        return response()->json([
            'status' => true,
            'content' => $content,
            'message' => $message
        ], $code);
    }
}