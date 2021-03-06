<?php
namespace App\Http\Traits\TMS\Warehouse;

use App\Models\Dbtbs\DoPendingEntry;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait DoPendingEntryTrait {
    protected function headerToolsSSOHeader($request)
    {
        $query = DB::table('db_tbs.entry_sso_tbl')
            ->where('db_tbs.entry_sso_tbl.sso_header', $request->sso_header)
            ->where('db_tbs.entry_sso_tbl.active_cls','1')
            ->leftJoin('db_tbs.entry_so_tbl', 'db_tbs.entry_so_tbl.so_header', '=', 'db_tbs.entry_sso_tbl.so_header')
            ->leftJoin('db_tbs.sys_warehouse', function ($join){
                    $join->on('db_tbs.sys_warehouse.branch', '=', 'db_tbs.entry_so_tbl.branch');
                    $join->on('db_tbs.sys_warehouse.warehouse_id', '=', 'db_tbs.entry_so_tbl.warehouse');
                }
            )
            ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'db_tbs.entry_so_tbl.cust_id')
            ->leftJoin('db_tbs.sys_do_address', function ($join) {
                    $join->on('db_tbs.entry_so_tbl.cust_id','=','db_tbs.sys_do_address.cust_code');
                    $join->on('db_tbs.entry_so_tbl.do_address','=','db_tbs.sys_do_address.id_do');
                }
            )
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
    protected function headerToolsSOHeader($request)
    {
        $query = DB::table('db_tbs.entry_sso_tbl')
            ->where('db_tbs.entry_sso_tbl.so_header', $request->so_header)
            ->where('db_tbs.entry_sso_tbl.active_cls', '1')
            ->leftJoin('db_tbs.entry_so_tbl', 'db_tbs.entry_so_tbl.so_header', '=', 'db_tbs.entry_sso_tbl.so_header')
            ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'db_tbs.entry_so_tbl.cust_id')
            ->leftJoin('db_tbs.sys_warehouse', function ($join){
                    $join->on('db_tbs.sys_warehouse.branch', '=', 'db_tbs.entry_so_tbl.branch');
                    $join->on('db_tbs.sys_warehouse.warehouse_id', '=', 'db_tbs.entry_so_tbl.warehouse');
                }
            )
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
    protected function headerToolsSSODetail($request)
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
            ->leftJoin('db_tbs.entry_do_pending_tbl', function($join){
                $join->on('db_tbs.entry_sso_tbl.sso_header','=','db_tbs.entry_do_pending_tbl.sso_no');
                $join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_do_pending_tbl.item_code');
            })
            ->select([
                'db_tbs.entry_sso_tbl.item_code as itemcode',
                'db_tbs.entry_sso_tbl.dn_no as dn_no',
                'db_tbs.item.part_no as part_no',
                'db_tbs.item.unit as unit',
                'db_tbs.item.descript1 as model',
                'db_tbs.entry_so_tbl.qty_so as qty_so',
                'db_tbs.entry_sso_tbl.sso_header as sso_no',
                'db_tbs.entry_so_tbl.so_header as so_no',
                'db_tbs.item.descript as part_name',
                'db_tbs.entry_sso_tbl.qty_sso as qty_sso',
                DB::raw('
                    IFNULL(db_tbs.entry_do_pending_tbl.do_no, 0) as do_no, 
                    IFNULL(sum(db_tbs.entry_do_pending_tbl.quantity), 0) as qty_sj, 
                    date(db_tbs.entry_sso_tbl.closed_date) as closed_date
                ')
            ])
            ->groupBy('db_tbs.entry_sso_tbl.item_code')
            ->get();
        return $query;
    }

    protected function headerToolsViewDo($data)
    {
        $query = DoPendingEntry::where('db_tbs.entry_do_pending_tbl.do_no', $data->do_no)
            ->leftJoin('db_tbs.item','item_code','=','db_tbs.item.itemcode')
            ->leftJoin('db_tbs.sys_do_address', function($join) {
                $join->on('db_tbs.entry_do_pending_tbl.cust_id','=','db_tbs.sys_do_address.cust_code');
                $join->on('db_tbs.entry_do_pending_tbl.do_address','=','db_tbs.sys_do_address.id_do');
            })
            ->leftJoin('db_tbs.entry_sso_tbl', function ($join) {
                $join->on('db_tbs.entry_do_pending_tbl.sso_no','=','db_tbs.entry_sso_tbl.sso_header');
                $join->on('db_tbs.entry_do_pending_tbl.item_code','=','db_tbs.entry_sso_tbl.item_code');
            })
            ->leftJoin('db_tbs.entry_so_tbl',function($join){
                $join->on('db_tbs.entry_do_pending_tbl.so_no','=','db_tbs.entry_so_tbl.so_header');
                $join->on('db_tbs.entry_do_pending_tbl.item_code','=','db_tbs.entry_so_tbl.item_code');
            })
            ->select($this->columnOfDoView())
            ->get();
        $data = [
            'header' => $query->first(),
            'items' => $query
        ];
        return $data;
    }

    protected function dataForPrint($data)
    {
        return DB::table('db_tbs.entry_do_pending_tbl as do_temp')
            ->leftJoin('ekanban.ekanban_customermaster as tb_cust', 'tb_cust.CustomerCode_eKanban', '=', 'do_temp.cust_id')
            ->leftJoin('db_tbs.sys_do_address as tb_doaddr', 'tb_doaddr.id_do', '=', 'do_temp.do_address')
            ->leftJoin('db_tbs.item as tb_item', function ($join){
                $join->on('do_temp.cust_id', '=', 'tb_item.CUSTCODE');
                $join->on('do_temp.item_code', '=', 'tb_item.ITEMCODE');
            })
            ->where(function ($on) use($data){
                $on->where('do_temp.do_no', '>=', $data->dari);
                $on->where('do_temp.do_no', '<=', $data->sampai);
                $on->whereNull('do_temp.voided_date');
            })
            ->select([
                'do_temp.so_no',
                'do_temp.sso_no',
                'do_temp.cust_id',
                'do_temp.delivery_date',
                'do_temp.period',
                'do_temp.do_no',
                'tb_doaddr.cust_name',
                'tb_doaddr.id_do',
                'tb_doaddr.do_addr1 as address1',
                'tb_doaddr.do_addr2 as address2',
                'tb_doaddr.do_addr3 as address3',
                'tb_doaddr.do_addr4 as address4',
                'do_temp.item_code',
                'tb_item.part_no as part_no',
                'tb_item.descript as part_name',
                'tb_item.descript1 as model',
                'tb_item.unit as unit',
                'tb_item.fac_unit as fac_unit',
                'do_temp.quantity',
                'do_temp.branch',
                'do_temp.warehouse',
                'do_temp.dn_no',
                'do_temp.po_no',
                'do_temp.ref_no',
                'do_temp.remark',
                'do_temp.invoice',
                'do_temp.created_by as user',
                'do_temp.sj_type',
                'do_temp.rr_no',
                DB::raw('
                    date(do_temp.printed_date) as printed,
                    date(do_temp.posted_date) as posted,
                    date(do_temp.finished_date) as finished,
                    date(do_temp.voided_date) as voided
                ')
            ])
            ->get();
    }

    protected function headerToolsDataDoForPrint($request)
    {
        $where = [];
        if (isset($request->dari)) {
            $where = [
                ['do_no', '>=', $request->dari],
                ['voided_date', '=', null],
            ];
        }else{
            $where = [
                ['voided_date', '=', null],
            ];
        }

        $query = DoPendingEntry::where($where)
            ->selectRaw('do_no, delivery_date, po_no, cust_id')
            ->orderBy('do_no', 'desc')
            ->groupBy('do_no')
            ->get();
        return $query;
    }

    protected function headerToolsCheckDO($request)
    {
        $message = null;
        $query = DoPendingEntry::where('do_no', $request->do_no)
            ->selectRaw('do_no, voided_date, voided_by, finished_date, finished_by, posted_date, posted_by')
            ->first();
        if (isset($query)) {
            if ($query->voided_date !== null && isset($request->check_print)) {
                $voided = $this->carbonCreateFormFormat($query->voided_date, 'Y-m-d H:i:s');
                return $message = "DO has been voided at $voided, by $query->voided_by";
            }elseif ($query->voided_date == null && isset($request->check_print)) {
                return $message = null;
            }
            if ($query->voided_date != null) {
                $voided = $this->carbonCreateFormFormat($query->voided_date, 'Y-m-d H:i:s');
                $message = "DO has been voided at $voided, by $query->voided_by";
            }elseif ($query->finished_date != null) {
                $finished = $this->carbonCreateFormFormat($query->finished_date, 'Y-m-d H:i:s');
                $message = "DO has been finished at $finished, by $query->finished_by";
            }elseif ($query->posted_date != null) {
                $posted = $this->carbonCreateFormFormat($query->posted_date, 'Y-m-d H:i:s');
                $message = "DO has been posted at $posted, by $query->posted_by";
            }
        }else{
            $message = 'DO Not Found!';
        }
        return $message;
    }

    protected function headerToolsBranch($request)
    {
        $query = DB::connection('db_tbs')
            ->table('branch')
            ->selectRaw('Branch as code, descript as name')
            ->where('status', 'ACTIVE')
            ->get();
        return $query;
    }

    protected function headerToolsTableSetting($request)
    {
        
    }

    protected function headerToolsWarehouse($request)
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
        $branch = $request->branch;
        $query = DB::connection('db_tbs')
            ->table('sys_warehouse')
            ->selectRaw('warehouse_id as code, descript as name')
            ->where(function ($whr) use($branch){
                $whr->where('branch', $branch);
            })
            ->get();
        return $query;
    }

    protected function headerToolsCustomer($request)
    {
        $query = 
            DB::connection('ekanban')
            ->table('ekanban_customermaster')
            ->selectRaw('CustomerCode_eKanban as code, CustomerName as name, Cus_Group as cg')
            ->get();
        return $query;
    }

    protected function headerToolsCustomerClick($request)
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

    protected function headerToolsCustomerAddr($request)
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

    protected function headerToolsItem($request)
    {
        $query = 
            DB::connection('db_tbs')
            ->table('item')
            ->selectRaw('ITEMCODE as itemcode, PART_NO as part_no, DESCRIPT as descript, UNIT as unit, DESCRIPT1 as model')
            ->where('CUSTCODE', $request->cust_code)
            ->whereRaw('ITEMCODE like ?', ['1%'])
            ->get();
        return $query;
    }

    protected function createLogDoTemp($request, $status, $note=null)
    {
        // return DB::table('db_tbs.entry_do_pending_tbl_log')->insert([
        // ]);
    }

    protected function headerToolsLog($request)
    {
        $query = 
            DB::connection('db_tbs')
            ->table('entry_do_pending_tbl_log')
            ->where('do_no', $request->do_no)
            ->orderBy('created_at', 'DESC')
            ->get();
        return $query;
    }

    private function getDataBySSO($where)
    {
        $query = DoPendingEntry::where('db_tbs.entry_do_pending_tbl.do_no', $where)
            ->leftJoin('db_tbs.item','item_code','=','db_tbs.item.itemcode')
            ->leftJoin('db_tbs.sys_do_address', function($join) {
                $join->on('db_tbs.entry_do_pending_tbl.cust_id','=','db_tbs.sys_do_address.cust_code');
                $join->on('db_tbs.entry_do_pending_tbl.do_address','=','db_tbs.sys_do_address.id_do');
            })
            ->leftJoin('db_tbs.entry_sso_tbl', function ($join) {
                $join->on('db_tbs.entry_do_pending_tbl.sso_no','=','db_tbs.entry_sso_tbl.sso_header');
                $join->on('db_tbs.entry_do_pending_tbl.item_code','=','db_tbs.entry_sso_tbl.item_code');
            })
            ->get();
        return $query;
    }

    private function getDataBySO($where)
    {
        $query = DoPendingEntry::where('db_tbs.entry_do_pending_tbl.do_no', $where)
            ->leftJoin('db_tbs.item','item_code','=','db_tbs.item.itemcode')
            ->leftJoin('db_tbs.sys_do_address', function($join) {
                $join->on('db_tbs.entry_do_pending_tbl.cust_id','=','db_tbs.sys_do_address.cust_code');
                $join->on('db_tbs.entry_do_pending_tbl.do_address','=','db_tbs.sys_do_address.id_do');
            })
            ->leftJoin('db_tbs.entry_sso_tbl', function ($join) {
                $join->on('db_tbs.entry_do_pending_tbl.sso_no','=','db_tbs.entry_sso_tbl.sso_header');
                $join->on('db_tbs.entry_do_pending_tbl.item_code','=','db_tbs.entry_sso_tbl.item_code');
            })
            ->get();
        return $query;
    }

    protected function createLOG($id, $status, $note=null)
    {
        $log = DB::connection('db_tbs')
            ->table('entry_do_pending_tbl_log')
            ->insert([
                'do_no' => $id,
                'date_log' => date('Y-m-d'),
                'time_log' => date('H:i:s'),
                'status_log' => $status,
                'user' => Auth::user()->FullName,
                'note' => ($note != null) ? $note : ""
            ]);
        return $log;
    }

    protected function createLOGBatch($data)
    {
        $log = DB::connection('db_tbs')
            ->table('entry_do_pending_tbl_log')
            ->insert($data);
        return $log;
    }

    protected function headerToolsDoPendingEntryNoTbs()
    {
        $tbsDoNo = DB::table('tch_tbs.numbers')->where('label','DO NUMBER')
            ->select('contents')
            ->first();
        $checkDoNo = $tbsDoNo->contents + 1;
        $cekDoTbl = DoPendingEntry::where('do_no', $checkDoNo)
            ->select(['do_no'])
            ->get();
        if ($cekDoTbl->isEmpty()) {
            $do_no = $checkDoNo;
        }else{
            do {
                $checkDoNo++;
                $cekDoTbl = DoPendingEntry::where('do_no', $checkDoNo)
                    ->select(['do_no'])
                    ->get();
            } while (!$cekDoTbl->isEmpty());
        }
        $do_no = $checkDoNo;
        return $do_no;
    }

    protected function headerToolsDoPendingEntryNo($request)
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
                DoPendingEntry::select('do_no')
                ->where('do_no', $cekDoNo)
                ->get();  
            if ($cekDoTbl->isEmpty()){
                $DoNo = $cekDoNo;
                return $DoNo;
            }else{
                do{
                    $cekDoNo++;
                    $cekDoTbl = 
                        DoPendingEntry::select('do_no')
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
                DoPendingEntry::select('do_no')
                ->where('do_no', $cekDoNo)
                ->get();
            if ($cekDoTbl->isEmpty()){
                $DoNo = $cekDoNo;
                return $DoNo;
            }else{
                do{
                    $cekDoNo++;
                    $cekDoTbl = 
                        DoPendingEntry::select('do_no')
                        ->where('do_no',$cekDoNo)
                        ->get();
                }while (!$cekDoTbl->isEmpty());
                $DoNo = $cekDoNo;
                return $DoNo;
            }
        }  
    }

    // protected function _Error($message=null, $code=401, $content=null)
    // {
    //     return response()->json([
    //         'status' => false,
    //         'content' => $content,
    //         'message' => $message
    //     ], $code);
    // }

    // protected function _Success($message=null, $code=200, $content=null)
    // {
    //     return response()->json([
    //         'status' => true,
    //         'content' => $content,
    //         'message' => $message
    //     ], $code);
    // }

    private function columnOfDoView()
    {
        return [
            'db_tbs.entry_do_pending_tbl.so_no',
            'db_tbs.entry_do_pending_tbl.sso_no',
            'db_tbs.entry_do_pending_tbl.cust_id',
            'db_tbs.entry_do_pending_tbl.delivery_date',
            'db_tbs.entry_do_pending_tbl.period',
            'db_tbs.entry_do_pending_tbl.do_no',
            'db_tbs.sys_do_address.cust_name',
            'db_tbs.sys_do_address.id_do',
            'db_tbs.sys_do_address.do_addr1 as address1',
            'db_tbs.sys_do_address.do_addr2 as address2',
            'db_tbs.sys_do_address.do_addr3 as address3',
            'db_tbs.sys_do_address.do_addr4 as address4',
            'db_tbs.entry_do_pending_tbl.row_no',
            'db_tbs.entry_do_pending_tbl.item_code',
            'db_tbs.item.part_no as part_no',
            'db_tbs.item.descript as part_name',
            'db_tbs.item.descript1 as model',
            'db_tbs.item.unit as unit',
            'db_tbs.item.fac_unit as fac_unit',
            'db_tbs.entry_so_tbl.qty_so as qty_so',
            'db_tbs.entry_sso_tbl.qty_sso as qty_sso',
            'db_tbs.entry_do_pending_tbl.quantity',
            'db_tbs.entry_do_pending_tbl.branch',
            'db_tbs.entry_do_pending_tbl.warehouse',
            'db_tbs.entry_do_pending_tbl.dn_no',
            'db_tbs.entry_do_pending_tbl.po_no',
            'db_tbs.entry_do_pending_tbl.ref_no',
            'db_tbs.entry_do_pending_tbl.remark',
            'db_tbs.entry_do_pending_tbl.invoice',
            'db_tbs.entry_do_pending_tbl.created_by as user',
            'db_tbs.entry_do_pending_tbl.sj_type',
            'db_tbs.entry_do_pending_tbl.rr_no',
            DB::raw('
                date(db_tbs.entry_do_pending_tbl.printed_date) as printed,
                date(db_tbs.entry_do_pending_tbl.posted_date) as posted,
                date(db_tbs.entry_do_pending_tbl.finished_date) as finished,
                date(db_tbs.entry_do_pending_tbl.voided_date) as voided
            ')
        ];
    }

    protected function carbonCreateFormFormat($date, $from='Y-m-d', $to='d/m/Y')
    {
        return Carbon::createFromFormat($from, $date)->format($to);
    }
}