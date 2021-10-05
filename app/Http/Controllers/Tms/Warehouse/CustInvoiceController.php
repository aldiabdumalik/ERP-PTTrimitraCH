<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\CustInvTrait;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustInvoiceController extends Controller
{
    use ToolsTrait, CustInvTrait;

    public function __construct() {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.warehouse.cust-invoice.index');
    }

    public function header(Request $request)
    {
        switch ($request->type) {
            case 'invno':
                return $this->custInvNo($request);
                break;
            
            case 'customer':
                return DataTables::of($this->customer($request))->make(true);
                break;

            case 'sys_account':
                if (isset($request->number)) {
                    return _Success(null, 200, $this->sys_account($request));
                }
                return DataTables::of($this->sys_account($request))->make(true);
                break;

            case 'currency':
                if (isset($request->currency)) {
                    $res = DB::table('db_tbs.valas')
                        ->where('valas', $request->currency)
                        ->first();
                }else{
                    $res = DB::table('db_tbs.valas')->get();
                }
                return _Success(null, 200, $res);
                break;
                
            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }
}
