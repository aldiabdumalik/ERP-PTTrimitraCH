<?php
namespace App\Http\Traits\TMS\Warehouse;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ToolsTrait {
    
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

    protected function dateConvertFrom($date, $from='Y-m-d', $to='d/m/Y')
    {
        return Carbon::createFromFormat($from, $date)->format($to);
    }

    protected function createGlobalLog($tbl, $data)
    {
        $log = DB::table($tbl)
            ->insert($data);
        return $log;
    }

    protected function customer()
    {
        $query = 
            DB::connection('ekanban')
            ->table('ekanban_customermaster')
            ->selectRaw('CustomerCode_eKanban as code, CustomerName as name, Contact as cont, Address1 as ad1, Address2 as ad2, Address3 as ad3, Address4 as ad4, GLAR as glcode')
            ->where('status_data', 'ACTIVE')
            ->get();
        return $query;
    }

    protected function sys_account(Request $request)
    {
        if (isset($request->number)) {
            $query = 
                DB::table('db_tbs.sys_account')
                    ->where('status', 'ACTIVE')
                    ->where('number', $request->number)
                    ->first();
        }else{
            $query = 
                DB::table('db_tbs.sys_account')
                    ->where('status', 'ACTIVE')
                    ->get();
        }
        return $query;
    }

    protected function items($id)
    {
        $query = 
            DB::connection('db_tbs')
                ->table('item')
                ->selectRaw('ITEMCODE as itemcode, PART_NO as part_no, DESCRIPT as descript, UNIT as unit')
                ->where('CUSTCODE', $id)
                ->get();
        return $query;
    }

    protected function item($itemcode)
    {
        $query = 
            DB::connection('db_tbs')
                ->table('item')
                ->selectRaw('ITEMCODE as itemcode, PART_NO as part_no, DESCRIPT as descript, UNIT as unit')
                ->where('ITEMCODE', $itemcode)
                ->first();
        return $query;
    }

}