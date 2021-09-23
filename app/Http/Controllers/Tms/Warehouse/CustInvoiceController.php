<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\CustInvTrait;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use Illuminate\Http\Request;
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
                
            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }
}
