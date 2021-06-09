<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Dbtbs\ClaimEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClaimEntryController extends Controller
{
    public function index()
    {
        return view('tms.warehouse.claim-entry.index');
    }

    public function claimEntry(Request $request)
    {
        $query = ClaimEntry::all();
        dd($query);
    }

    public function claimEntryCreate(Request $request)
    {
        return response()->json([
            'status' => true,
            'content' => $request->items,
        ], 200);
    }

    public function claimEntryHeader(Request $request)
    {
        switch ($request->type) {
            case "CLNo":
                return $this->headerToolsEntryNo($request);
                break;
            case "branch":
                return DataTables::of($this->headerToolsBranch($request))->make(true);
                break;
            case "warehouse":
                return DataTables::of($this->headerToolsWarehouse($request))->make(true);
                break;
            case "customer":
                    return DataTables::of($this->headerToolsCustomer($request))->make(true);
                    break;
            case "customerclick":
                return $this->headerToolsCustomerClick($request);
                break;
            case "doaddr":
                    return DataTables::of($this->headerToolsCustomerAddr($request))->make(true);
                    break;
            case "item":
                    return DataTables::of($this->headerToolsItem($request))->make(true);
                    break;
            default:
                return response()->json([
                    'status' => true,
                    'content' => null,
                ], 200);
        }
    }

    private function headerToolsBranch(Request $request)
    {
        $query = DB::connection('db_tbs')
            ->table('branch')
            ->selectRaw('Branch as code, descript as name')
            ->where('status', 'ACTIVE')
            ->get();
        return $query;
    }

    private function headerToolsWarehouse(Request $request)
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

    private function headerToolsCustomer(Request $request)
    {
        $query = 
            DB::connection('oee')
            ->table('db_customername_tbl')
            ->selectRaw('customer_id as code, customer_name as name')
            ->get();
        return $query;
    }

    private function headerToolsCustomerClick(Request $request)
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

    private function headerToolsCustomerAddr(Request $request)
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

    private function headerToolsItem(Request $request)
    {
        $query = 
            DB::connection('db_tbs')
            ->table('item')
            ->selectRaw('ITEMCODE, PART_NO, DESCRIPT, UNIT')
            ->where('CUSTCODE', $request->cust_code)
            ->get();
        return $query;
    }

    private function headerToolsEntryNo(Request $request)
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
