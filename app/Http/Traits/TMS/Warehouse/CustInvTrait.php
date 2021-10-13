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

    protected function callDOJoin($arr_do)
    {
        $query = DB::table("db_tbs.entry_do_tbl")
            ->selectRaw('
                db_tbs.entry_do_tbl.do_no,
                db_tbs.entry_do_tbl.item_code,
                db_tbs.entry_do_tbl.quantity as qty_sj,
                db_tbs.entry_do_tbl.unit,
                db_tbs.entry_do_tbl.so_no,
                db_tbs.entry_do_tbl.sso_no,
                db_tbs.entry_do_tbl.ref_no,
                db_tbs.entry_do_tbl.po_no,
                db_tbs.entry_do_tbl.dn_no,
                db_tbs.entry_do_tbl.rr_no,
                db_tbs.entry_do_tbl.rr_date,
                db_tbs.entry_do_tbl.period as do_priod,
                db_tbs.entry_do_tbl.cust_id as cust_id,
                db_tbs.entry_do_tbl.delivery_date as do_date,
                db_tbs.item.part_no as part_no,
                db_tbs.item.DESCRIPT as descript,
                db_tbs.item.PRICE as item_price,
                custprice.price_new as item_price_new,
                (IFNULL(custprice.price_new, db_tbs.item.PRICE) * db_tbs.entry_do_tbl.quantity) as item_price_hasil
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
            ->leftJoin(DB::raw('(SELECT price_new, item_code FROM db_tbs.entry_custprice_tbl ORDER BY active_date DESC LIMIT 1) as custprice'), function($join){
                $join->on("custprice.item_code", "=", "entry_do_tbl.item_code");
            })
            ->whereIn('db_tbs.entry_do_tbl.do_no', $arr_do)
            ->get();
        return $query;
    }

    protected function callDoEntryGB($params)
    {
        $query = DB::table("db_tbs.entry_do_tbl")
            ->select(
                "db_tbs.entry_do_tbl.do_no",
                "db_tbs.entry_do_tbl.item_code",
                "db_tbs.entry_do_tbl.quantity as qty_sj",
                DB::raw("sum(db_tbs.entry_do_tbl.quantity) as tot_qty"),
                "db_tbs.entry_do_tbl.unit",
                "db_tbs.entry_do_tbl.so_no",
                "db_tbs.entry_do_tbl.sso_no",
                "db_tbs.entry_do_tbl.ref_no",
                "db_tbs.entry_do_tbl.po_no",
                "db_tbs.entry_do_tbl.dn_no",
                "db_tbs.entry_do_tbl.rr_no",
                "db_tbs.entry_do_tbl.rr_date",
                "db_tbs.entry_do_tbl.period as do_priod",
                "db_tbs.entry_do_tbl.cust_id as cust_id",
                "db_tbs.entry_do_tbl.delivery_date as do_date",
                DB::raw("SUM((IFNULL(custprice.price_new, db_tbs.item.PRICE) * db_tbs.entry_do_tbl.quantity)) AS sub_ammount"),
            )
            ->leftJoin('db_tbs.item','db_tbs.entry_do_tbl.item_code','=','db_tbs.item.itemcode')
            ->leftJoin(DB::raw('(SELECT price_new, item_code FROM db_tbs.entry_custprice_tbl ORDER BY active_date DESC LIMIT 1) as custprice'), function($join){
                $join->on("custprice.item_code", "=", "entry_do_tbl.item_code");
            })
            ->where("db_tbs.entry_do_tbl.branch", "=", $params['branch'])
            ->where("db_tbs.entry_do_tbl.cust_id", "=", $params['cust_id'])
            ->whereIn('db_tbs.entry_do_tbl.do_no', $params['arr_do'])
            ->whereNotNull("db_tbs.entry_do_tbl.rr_date")
            ->groupBy("db_tbs.entry_do_tbl.do_no")
            ->get();
        return $query;
    }

    protected function callDoEntry($params)
    {
        $query = DB::table("db_tbs.entry_do_tbl")
            ->select(
                "db_tbs.entry_do_tbl.do_no",
                "db_tbs.entry_do_tbl.item_code",
                "db_tbs.entry_do_tbl.quantity as qty_sj",
                DB::raw("sum(db_tbs.entry_do_tbl.quantity) as tot_qty"),
                "db_tbs.entry_do_tbl.unit",
                "db_tbs.entry_do_tbl.so_no",
                "db_tbs.entry_do_tbl.sso_no",
                "db_tbs.entry_do_tbl.ref_no",
                "db_tbs.entry_do_tbl.po_no",
                "db_tbs.entry_do_tbl.dn_no",
                "db_tbs.entry_do_tbl.rr_no",
                "db_tbs.entry_do_tbl.rr_date",
                "db_tbs.entry_do_tbl.period as do_priod",
                "db_tbs.entry_do_tbl.cust_id as cust_id",
                "db_tbs.entry_do_tbl.delivery_date as do_date",
                DB::raw("SUM((IFNULL(custprice.price_new, db_tbs.item.PRICE) * db_tbs.entry_do_tbl.quantity)) AS sub_ammount"),
            )
            ->leftJoin('db_tbs.item','db_tbs.entry_do_tbl.item_code','=','db_tbs.item.itemcode')
            ->leftJoin(DB::raw('(SELECT price_new, item_code FROM db_tbs.entry_custprice_tbl ORDER BY active_date DESC LIMIT 1) as custprice'), function($join){
                $join->on("custprice.item_code", "=", "entry_do_tbl.item_code");
            })
            ->where("db_tbs.entry_do_tbl.branch", "=", $params['branch'])
            ->where("db_tbs.entry_do_tbl.cust_id", "=", $params['cust_id'])
            ->whereNotNull("db_tbs.entry_do_tbl.rr_date")
            ->groupBy("db_tbs.entry_do_tbl.do_no")
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