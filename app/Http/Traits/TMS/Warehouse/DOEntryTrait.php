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

    protected function headerToolsSSOHeader(Request $request)
    {
        $query = DB::table('db_tbs.entry_sso_tbl')
            ->where('db_tbs.entry_sso_tbl.sso_header', $request->sso_header)
            ->where('db_tbs.entry_sso_tbl.active_cls','1')
            ->leftJoin('db_tbs.entry_so_tbl', 'db_tbs.entry_so_tbl.so_header', '=', 'db_tbs.entry_sso_tbl.so_header')
            ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'db_tbs.entry_so_tbl.cust_id')
            ->leftJoin('db_tbs.sys_do_address', function ($join) {
                $join->on('db_tbs.entry_so_tbl.cust_id','=','db_tbs.sys_do_address.cust_code');
                $join->on('db_tbs.entry_so_tbl.do_address','=','db_tbs.sys_do_address.id_do');
            })
            ->select(
                'db_tbs.entry_so_tbl.so_header as so_header',
                'db_tbs.entry_so_tbl.cust_id as cust_id',
                'ekanban.ekanban_customermaster.CustomerName as customer',
                'db_tbs.entry_so_tbl.po_no as po_no',
                'db_tbs.entry_sso_tbl.dn_no as dn_no',
                'db_tbs.sys_do_address.do_addr1 as Address1',
                'db_tbs.sys_do_address.do_addr2 as Address2',
                'db_tbs.sys_do_address.do_addr3 as Address3',
                'db_tbs.sys_do_address.do_addr4 as Address4',
                'db_tbs.sys_do_address.id_do',
                'db_tbs.entry_so_tbl.branch as branch',
                'db_tbs.entry_sso_tbl.closed_date as closed_date',
                'db_tbs.entry_so_tbl.warehouse as wh',
                'db_tbs.entry_sso_tbl.sso_header as sso_header',
                'ekanban.ekanban_customermaster.Cus_Group as gr_customer',
                'db_tbs.entry_so_tbl.do_address as id_do_addr'
            )
            ->groupBy('db_tbs.entry_so_tbl.so_header')
            ->first();
        return $query;
    }
    protected function headerToolsSOHeader(Request $request)
    {
        $query = DB::table('db_tbs.entry_sso_tbl')
            ->where('db_tbs.entry_sso_tbl.so_header', $request->so_header)
            ->where('db_tbs.entry_sso_tbl.active_cls', '1')
            ->leftJoin('db_tbs.entry_so_tbl', 'db_tbs.entry_so_tbl.so_header', '=', 'db_tbs.entry_sso_tbl.so_header')
            ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'db_tbs.entry_so_tbl.cust_id')
            ->leftJoin('db_tbs.sys_do_address', function ($join) {
                $join->on('db_tbs.entry_so_tbl.cust_id','=','db_tbs.sys_do_address.cust_code');
                $join->on('db_tbs.entry_so_tbl.do_address','=','db_tbs.sys_do_address.id_do');
            })
            ->select(
                'db_tbs.entry_so_tbl.so_header as so_header',
                'db_tbs.entry_so_tbl.cust_id as cust_id',
                'ekanban.ekanban_customermaster.CustomerName as customer',
                'db_tbs.entry_so_tbl.po_no as po_no',
                'db_tbs.entry_sso_tbl.dn_no as dn_no',
                'db_tbs.sys_do_address.do_addr1 as Address1',
                'db_tbs.sys_do_address.do_addr2 as Address2',
                'db_tbs.sys_do_address.do_addr3 as Address3',
                'db_tbs.sys_do_address.do_addr4 as Address4',
                'db_tbs.sys_do_address.id_do',
                'db_tbs.entry_so_tbl.branch as branch',
                'db_tbs.entry_sso_tbl.closed_date as closed_date',
                'db_tbs.entry_so_tbl.warehouse as wh',
                'db_tbs.entry_sso_tbl.sso_header as sso_header',
                'ekanban.ekanban_customermaster.Cus_Group as gr_customer',
                'db_tbs.entry_so_tbl.do_address as id_do_addr'
            )
            ->groupBy('db_tbs.entry_so_tbl.so_header')
            ->first();
        return $query;
    }
    protected function headerToolsSSODetail(Request $request)
    {
        $where = [];
        if (isset($request->sso_header)) {
           $where = [
                'db_tbs.entry_sso_tbl.sso_header' => $request->sso_header
            ];
        }else{
            $where = [
                'db_tbs.entry_sso_tbl.so_header' => $request->so_header
            ];
        }
        $query = DB::table('db_tbs.entry_sso_tbl')
            ->where($where)
            ->where('db_tbs.entry_sso_tbl.active_cls', '1')
            ->leftJoin('db_tbs.item','db_tbs.entry_sso_tbl.item_code','=','db_tbs.item.itemcode')
            ->leftJoin('db_tbs.entry_so_tbl', function($join){
                $join->on('db_tbs.entry_sso_tbl.so_header','=','db_tbs.entry_so_tbl.so_header');
                $join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_so_tbl.item_code');
            })
            ->leftJoin('db_tbs.entry_do_tbl', function($join){
                $join->on('db_tbs.entry_sso_tbl.sso_header','=','db_tbs.entry_do_tbl.sso_no');
                $join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_do_tbl.item_code');
            })
            ->select([
                'db_tbs.entry_sso_tbl.dn_no as dn_no',
                'db_tbs.item.part_no as part_no',
                'db_tbs.entry_sso_tbl.item_code as itemcode',
                'db_tbs.item.unit as unit',
                'db_tbs.item.descript1 as model',
                'db_tbs.entry_so_tbl.qty_so as qty_so',
                'db_tbs.entry_sso_tbl.sso_header as sso_no',
                'db_tbs.entry_so_tbl.so_header as so_no',
                'db_tbs.item.descript as part_name',
                'db_tbs.entry_sso_tbl.qty_sso as qty_sso',
                DB::raw('IFNULL(sum(db_tbs.entry_do_tbl.quantity),0) as qty_sj, date(db_tbs.entry_sso_tbl.closed_date) as closed_date')
            ])
            ->groupBy('db_tbs.entry_sso_tbl.item_code')
            ->get();
        return $query;
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
            ->selectRaw('CustomerCode_eKanban as code, CustomerName as name, Cus_Group as cg')
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