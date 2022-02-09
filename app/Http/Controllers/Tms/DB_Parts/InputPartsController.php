<?php

namespace App\Http\Controllers\TMS\DB_Parts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DB_parts\InputParts;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InputPartsController extends Controller
{
    use ToolsTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.db_parts.input_parts.index');
    }

    public function tableIndex(Request $request)
    {
        if ($request->ajax()) {
            $result = InputParts::where('is_active', 1)->get();
            return DataTables::of($result)
            ->make(true);
        }
    }
}