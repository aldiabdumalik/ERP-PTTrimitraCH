<?php
namespace App\Http\Traits\TMS\Warehouse;

use App\Models\Dbtbs\CustInvoice;
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

trait CustInvTrait {

    protected function custInvDo($params)
    {
        $query = DoEntry::selectRaw('
                entry_do_tbl.do_no,
                entry_do_tbl.item_code,
                entry_do_tbl.quantity as qty_sj,
                entry_do_tbl.unit,
                entry_do_tbl.so_no,
                entry_do_tbl.sso_no,
                entry_do_tbl.ref_no,
                entry_do_tbl.po_no,
                entry_do_tbl.dn_no,
                entry_do_tbl.rr_no,
                entry_do_tbl.period as do_priod,
                entry_do_tbl.cust_id as cust_id,
                SUM(entry_do_tbl.quantity) as tot_qty,
                db_tbs.item.PART_NO as part_no,
                db_tbs.item.descript1 as model,
                db_tbs.item.descript as part_name
            ')
            ->leftJoin('db_tbs.entry_sso_tbl', function ($join) {
                    $join->on('db_tbs.entry_sso_tbl.sso_header','=','db_tbs.entry_do_tbl.sso_no');
                    $join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_do_tbl.item_code');
                }
            )
            ->leftJoin('db_tbs.entry_so_tbl', function($join){
                    $join->on('db_tbs.entry_sso_tbl.so_header','=','db_tbs.entry_so_tbl.so_header');
                    $join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_so_tbl.item_code');
                }
            )
            ->leftJoin('db_tbs.item','db_tbs.entry_sso_tbl.item_code','=','db_tbs.item.itemcode')
            ->where([
                'entry_do_tbl.branch' => $params['branch'],
                'entry_do_tbl.cust_id' => $params['cust_id'],
            ])
            ->whereNotNull('rr_date')
            ->get();
        return $query;
    }

    protected function custInvDoGB($params)
    {
        $query = DoEntry::selectRaw('
                entry_do_tbl.do_no,
                entry_do_tbl.item_code,
                entry_do_tbl.quantity as qty_sj,
                entry_do_tbl.unit,
                entry_do_tbl.so_no,
                entry_do_tbl.sso_no,
                entry_do_tbl.ref_no,
                entry_do_tbl.po_no,
                entry_do_tbl.dn_no,
                entry_do_tbl.rr_no,
                entry_do_tbl.period as do_priod,
                entry_do_tbl.cust_id as cust_id,
                entry_do_tbl.delivery_date as do_date,
                SUM(entry_do_tbl.quantity) as tot_qty,
                db_tbs.item.PART_NO as part_no,
                db_tbs.item.descript1 as model,
                db_tbs.item.descript as part_name
            ')
            ->leftJoin('db_tbs.entry_sso_tbl', function ($join) {
                    $join->on('db_tbs.entry_sso_tbl.sso_header','=','db_tbs.entry_do_tbl.sso_no');
                    $join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_do_tbl.item_code');
                }
            )
            ->leftJoin('db_tbs.entry_so_tbl', function($join){
                    $join->on('db_tbs.entry_sso_tbl.so_header','=','db_tbs.entry_so_tbl.so_header');
                    $join->on('db_tbs.entry_sso_tbl.item_code','=','db_tbs.entry_so_tbl.item_code');
                }
            )
            ->leftJoin('db_tbs.item','db_tbs.entry_sso_tbl.item_code','=','db_tbs.item.itemcode')
            ->where([
                'entry_do_tbl.branch' => $params['branch'],
                'entry_do_tbl.cust_id' => $params['cust_id'],
            ])
            ->whereNotNull('rr_date')
            ->groupBy('db_tbs.entry_do_tbl.do_no')
            ->get();
        return $query;
    }
    
    protected function custInvNo(Request $request)
    {
        $ref = DB::table('db_tbs.sys_number')
            ->selectRaw('concat(right(year(NOW()),2),DATE_FORMAT(NOW(),"%m")) as ref')
            ->first();
        $inv_no = DB::table('db_tbs.sys_number')
            ->where('label', 'INVOICE NUMBER')
            ->select('contents')
            ->first();
        $a = substr($inv_no->contents, 0, 4);
        $b = $ref->ref;
        if ($a == $b){
            $cekInvNo = $inv_no->contents + 1;
            $cekInvTbl = CustInvoice::select('inv_no')
                ->where('inv_no', $cekInvNo)
                ->get();
            
            if ($cekInvTbl->isEmpty()){
                $InvNo = $cekInvNo;
                return $InvNo;
            }else{
                do{
                    $cekInvNo++;
                    $cekInvTbl = CustInvoice::select('inv_no')
                        ->where('inv_no', $cekInvNo)
                        ->get();           
                }while (!$cekInvTbl->isEmpty());
                $InvNo = $cekInvNo;
                return $InvNo;
            }
        }else{
            $cekInvNo  = $b;
            $cekInvNo  .= '0001';
            $cekInvTbl = CustInvoice::select('inv_no')
                ->where('inv_no', $cekInvNo)
                ->get();
            if ($cekInvTbl->isEmpty()){
                $InvNo = $cekInvNo;
                return $InvNo;
            }else{
                do{
                    $cekInvNo++;
                    $cekInvTbl = CustInvoice::select('inv_no')
                        ->where('inv_no', $cekInvNo)
                        ->get();
                }while (!$cekInvTbl->isEmpty());
                $InvNo = $cekInvNo;
                return $InvNo;
            }
        }
    }
    
}