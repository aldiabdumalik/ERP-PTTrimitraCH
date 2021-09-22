<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustInvoiceController extends Controller
{
    public function index()
    {
        return view('tms.warehouse.cust-invoice.index');
    }

    public function header(Request $request)
    {
        switch ($request->type) {
            case 'invno':
                return 123;
                break;
            
            case 'value':
                # code...
                break;
                
            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }
}
