<?php

namespace App\Http\Controllers\TMS\DB_Parts;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DB_parts\ProductionCode;
use Illuminate\Http\Request;

class ProductionCodeController extends Controller
{
    use ToolsTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.db_parts.production_code.index');
    }

    public function tableIndex(Request $request)
    {
        $result = ProductionCode::query()->groupBy('id_part')->get();
    }
}
