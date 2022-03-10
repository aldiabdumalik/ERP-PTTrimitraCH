<?php

namespace App\Http\Controllers\TMS\Engineering;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DB_parts\InputParts;
use App\Models\Dbtbs\DB_parts\ProductionCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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

    public function store(Request $request)
    {
        return null;
        // try {
        //     $data = [];
        //     $items = json_decode($request->items, true);

        //     // for ($i=0; $i < count($items); $i++) { 
        //     //     $data[] = [
        //     //         'id_part' => $request->id_part,
        //     //         'id_process' => $items[$i]['process']
        //     //     ];
        //     // }

        //     return _Success(null, 200, $items);
        // } catch (Exception $e) {
        //     return _Error($e->getMessage());
        // }
    }

    public function headerTools(Request $request)
    {
        switch ($request->type) {
            case 'get_process':
                $query = DB::table('db_tbs.dbparts_master_process_tbl')->where('is_active', 1)->get();
                return _Success('OK', 200, $query);
                break;
            case 'get_detail_process':
                $query = DB::table('db_tbs.dbparts_master_process_detail_tbl')
                ->leftJoin('db_tbs.dbparts_master_process_tbl', 'db_tbs.dbparts_master_process_detail_tbl.process_id', '=', 'db_tbs.dbparts_master_process_tbl.process_id')
                ->where('db_tbs.dbparts_master_process_detail_tbl.process_id', $request->process)
                ->where('db_tbs.dbparts_master_process_detail_tbl.is_active', 1)
                ->get();
                return DataTables::of($query)
                ->make(true);
                break;
            case 'get_part':
                $result = InputParts::query()->where('is_active', 1)->get();
                return DataTables::of($result)
                ->make(true);
                break;
                
            default:
                # code...
                break;
        }
    }
}
