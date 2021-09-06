<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustPriceController extends Controller
{
    public function index()
    {
        return view('tms.warehouse.cust-price.index');
    }
}
