<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Dbtbs\DoEntry;

class RrEntryController extends Controller
{
    use ToolsTrait;

    public function index()
    {
        return view('tms.warehouse.rr-entry.index');
    }

    public function RrEntrySave(Request $request)
    {
        # code...
    }

    public function RrEntryHeader(Request $request)
    {
        switch ($request->type) {
            case "dodata":
                $query = DoEntry::selectRaw('do_no, ref_no, delivery_date, po_no, dn_no, cust_id')
                    ->orderBy('do_no', 'desc')
                    ->groupBy('do_no')
                    ->get();
                return DataTables::of($query)
                    ->editColumn('delivery_date', function($query) {
                        return date('d/m/Y', strtotime($query->delivery_date));
                    })
                    ->make(true);
                break;
            case "dodataclick":
                $req = $this->validationDo($request->dono);
                if ($req === null) {
                    $query = DoEntry::where('entry_do_tbl.do_no', $request->dono)
                        ->leftJoin('item', 'item.ITEMCODE', '=', 'entry_do_tbl.item_code')
                        ->get();
                    return $this->_Success(null, 200, $query);
                }
                return $this->_Error($req);
                break;
            default:
                return $this->_Error('Methode Not Found');
        }
    }

    private function validationDo($id)
    {
        $q = DoEntry::where('do_no', $id)
            ->first();
        if (!isset($q)) {
            return 'Data not found';
        }
        if ($q->voided_date != null) {
            return "DO has been voided";
        }elseif ($q->finished_date != null) {
            return "DO has been finished";
        }elseif ($q->posted_date != null) {
            return "DO has been posted";
        }
        return null;
    }
}
