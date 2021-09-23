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