<?php
namespace App\Http\Traits\TMS\Warehouse;

use Carbon\Carbon;
use App\Models\Dbtbs\ClaimEntry;
use App\Models\Dbtbs\ClaimEntryRG;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Redirect;

trait ClaimEntryTrait {
    
    protected function createLOG($id, $status, $note=null)
    {
        $log = DB::connection('db_tbs')
            ->table('entry_cl_log')
            ->insert([
                'cl_no' => $id,
                'date_written' => date('Y-m-d'),
                'time_written' => date('H:i:s'),
                'status_change' => $status,
                'user' => Auth::user()->FullName,
                'note' => ($note != null) ? $note : ""
            ]);
        return $log;
    }

    protected function claimEntryCheckRG(Request $request)
    {
        $claim = DB::connection('db_tbs')
            ->table('entry_cl_tbl')
            ->selectRaw('SUM(qty) as tot_qty')
            ->where('cl_no', $request->cl_no)
            ->first();
        $rg = DB::connection('db_tbs')
            ->table('entry_cl_rg')
            ->selectRaw('SUM(qty_rg) as tot_qty')
            ->where('cl_no', $request->cl_no)
            ->first();
        if ($claim->tot_qty == $rg->tot_qty) {
            $query = DB::connection('db_tbs')
                ->table('entry_cl_rg')
                ->where('cl_no', $request->cl_no)
                ->get();
            return $query;
        }else{
            return null;
        }
    }

    protected function claimEntryCheck(Request $request)
    {
        $closed = ClaimEntry::where('cl_no', $request->cl_no)
            ->whereNotNull('closed')
            ->get();
        $date_do = ClaimEntry::where('cl_no', $request->cl_no)
            ->whereNotNull('date_do')
            ->get();
        $date_rg = ClaimEntry::where('cl_no', $request->cl_no)
            ->whereNotNull('date_rg')
            ->get();
        $voided = ClaimEntry::where('cl_no', $request->cl_no)
            ->whereNotNull('voided')
            ->get();
        if ($request->cek == 'all') {
            if (!$closed->isEmpty()) {
                return 'Claim has been closed, please contact your manager!';
            }elseif (!$voided->isEmpty()) {
                return 'Claim has been voided';
            }elseif (!$date_do->isEmpty()) {
                return 'Claim has been delivered';
            }elseif (!$date_rg->isEmpty()) {
                return 'Claim has been received';
            }
        }elseif ($request->cek == 'deliver') {
            if (!$closed->isEmpty()) {
                return 'Claim has been closed, please contact your manager!';
            }elseif (!$voided->isEmpty()) {
                return 'Claim has been voided';
            }
        }elseif ($request->cek == 'receive') {
            if (!$voided->isEmpty()) {
                return 'Claim has been voided';
            }
        }elseif ($request->cek == 'unclose') {
            if (!$voided->isEmpty()) {
                return 'Claim has been voided';
            }
        }
    }

    protected function headerToolsBranch(Request $request)
    {
        $query = DB::connection('db_tbs')
            ->table('branch')
            ->selectRaw('Branch as code, descript as name')
            ->where('status', 'ACTIVE')
            ->get();
        return $query;
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
            DB::connection('oee')
            ->table('db_customername_tbl')
            ->selectRaw('customer_id as code, customer_name as name')
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
            ->where('cl_no', $request->cl_no)
            ->get();
        return $query;
    }

    protected function headerToolsEntryNo(Request $request)
    {
        $reference = 
            DB::connection('db_tbs')
            ->table('sys_number')
            ->selectRaw('concat(right(year(NOW()),2),DATE_FORMAT(NOW(),"%m")) as ref')
            ->first();
        $cl_no = 
            DB::connection('db_tbs')
            ->table('sys_number')
            ->where('label', 'CLAIM NUMBER')
            ->select('contents')
            ->first();
        $a = substr($cl_no->contents, 0, 4);
        $b = $reference->ref;
        if ($a == $b){
            $cekCLno = $cl_no->contents + 1;
            $cekCLTbl = 
                ClaimEntry::select('cl_no')
                ->where('cl_no', $cekCLno)
                ->get();  
            if ($cekCLTbl->isEmpty()){
                $clNo = $cekCLno;
                return $clNo;
            }else{
                do{
                    $cekCLno++;
                    $cekCLTbl = 
                        ClaimEntry::select('cl_no')
                        ->where('cl_no', $cekCLno)
                        ->get();           
                }while (!$cekCLTbl->isEmpty());
                $clNo = $cekCLno;
                return $clNo;
            }
        } else {
            $cekCLno  = $b;
            $cekCLno  .= '0001';
            $cekCLTbl = 
                ClaimEntry::select('cl_no')
                ->where('cl_no', $cekCLno)
                ->get();
            if ($cekCLTbl->isEmpty()){
                $clNo = $cekCLno;
                return $clNo;
            }else{
                do{
                    $cekCLno++;
                    $cekCLTbl = 
                        ClaimEntry::select('cl_no')
                        ->where('cl_no',$cekCLno)
                        ->get();
                }while (!$cekCLTbl->isEmpty());
                $clNo = $cekCLno;
                return $clNo;
            }
        }  
    }
    
}