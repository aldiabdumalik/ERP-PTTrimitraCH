<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use Illuminate\Http\Request;

class CustPriceController extends Controller
{
    use ToolsTrait;

    public function index()
    {
        return view('tms.warehouse.cust-price.index');
    }
    
    public function headerTools(Request $request)
    {
        switch ($request->type) {
            case 'customer':
                return _Success(null, 200, $this->customer());
                break;
            
            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }
}
