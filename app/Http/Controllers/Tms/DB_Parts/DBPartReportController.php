<?php

namespace App\Http\Controllers\TMS\DB_Parts;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DB_parts\InputParts;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DBPartReportController extends Controller
{
    use ToolsTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $type = InputParts::select(['type'])->groupBy('type')->get();
        return view('tms.db_parts.report.index', compact('type'));
    }

    public function partsType($customer)
    {
        $type = InputParts::select(['type'])->where('cust_id', $customer)->groupBy('type')->get();

        if ($type->isEmpty()) {
            return _Error('Part type for this customer not exist!');
        }

        return _Success('OK', 200, $type);
    }

    public function report(Request $request)
    {
        if (isset($request->params) && $request->params != "") {
            $decode = base64_decode($request->params);
            $arr = explode('&', $decode);

            $customer = $arr[0];
            $type = $arr[1];

            
            $params = InputParts::leftJoin('ekanban.ekanban_customermaster as customer', 'customer.CustomerCode_eKanban', '=', 'db_tbs.dbparts_item_part_tbl.cust_id')
            ->where(function ($on) use ($customer, $type){
                $on->where('db_tbs.dbparts_item_part_tbl.type', $type);
                $on->where('db_tbs.dbparts_item_part_tbl.cust_id', $customer);
            })
            ->select([
                'db_tbs.dbparts_item_part_tbl.*',
                'customer.CustomerName as cust_name',
            ])
            ->get();

            if ($params->isEmpty()) {
                $request->session()->flash('message', 'Data tidak ditemukan!');
                return Redirect::back();
            }
            
            $pdf = PDF::loadView('tms.db_parts.report.template.report', compact('params'))->setPaper('a3', 'landscape');
            return $pdf->stream();
        }
    }
}
