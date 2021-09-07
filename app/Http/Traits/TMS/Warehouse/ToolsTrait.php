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
            ->selectRaw('CustomerCode_eKanban as code, CustomerName as name')
            ->where('status_data', 'ACTIVE')
            ->get();
        return $query;
    }
}