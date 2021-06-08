<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Dbtbs\ClaimEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClaimEntryController extends Controller
{
    public function index()
    {
        return view('tms.warehouse.claim-entry.index');
    }

    public function claimEntry(Request $request)
    {
        $query = ClaimEntry::all();
        dd($query);
    }

    public function claimEntryHeader(Request $request)
    {
        switch ($request->type) {
            case "branch":
                return DataTables::of($this->headerToolsBranch($request))->make(true);
                break;
            case "warehouse":
                return DataTables::of($this->headerToolsWarehouse($request))->make(true);
                break;
            default:
                return response()->json([
                    'status' => true,
                    'content' => null,
                ], 200);
        }
    }

    private function headerToolsBranch(Request $request)
    {
        $query = DB::connection('db_tbs')
            ->table('branch')
            ->selectRaw('Branch as code, descript as name')
            ->where('status', 'ACTIVE')
            ->get();
        return $query;
    }

    private function headerToolsWarehouse(Request $request)
    {
        if ($request->branch != null) {
            $where = [
                'branch' => $request->branch,
                'status' => 'ACTIVE'
            ];
        }else{
            $where = [
                'status' => 'ACTIVE'
            ];
        }
        $query = DB::connection('db_tbs')
            ->table('sys_warehouse')
            ->selectRaw('warehouse_id as code, descript as name')
            ->where($where)
            ->get();
        return $query;
    }
}
