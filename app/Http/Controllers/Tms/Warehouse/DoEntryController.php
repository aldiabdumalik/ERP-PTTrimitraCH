<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\DoEntryTrait;
use App\Models\Dbtbs\DoEntry;
use App\Models\Dbtbs\DoEntrySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DoEntryController extends Controller
{
    use DoEntryTrait;
    
    public function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        return view('tms.warehouse.do-entry.index');
    }

    public function DoEntry(Request $request)
    {
        $query = DoEntry::groupBy('do_no')->get();
        return DataTables::of($query)
            ->editColumn('delivery_date', function($query) {
                return date('d/m/Y', strtotime($query->delivery_date));
            })
            ->editColumn('posted_date', function($query) {
                return ($query->posted_date == NULL) ? '/ /' : date('d/m/Y', strtotime($query->posted_date));
            })
            ->editColumn('finished_date', function($query) {
                return ($query->finished_date == NULL) ? '/ /' : date('d/m/Y', strtotime($query->finished_date));
            })
            ->editColumn('voided_date', function($query) {
                return ($query->voided_date == NULL) ? '/ /' : date('d/m/Y', strtotime($query->voided_date));
            })
            ->addColumn('action', function($query){
                return view('tms.warehouse.do-entry.button.btnTableIndex', [
                    'data' => $query,
                ]);
            })->rawColumns(['action'])
            ->make(true);
    }

    public function DoEntryTableSetting(Request $request)
    {
        $query = DoEntrySetting::where('user', Auth::user()->fullName)->get();
        if ($query->isEmpty()) {
            $query = DoEntrySetting::where('user', 'default')->get();
        }
        return $this->_Success('Default', 200, $query);
    }

    public function read(Request $request)
    {
        return $this->_Success('test promise');
    }

    public function create(Request $request)
    {
        
    }

    public function update(Request $request)
    {
        
    }

    public function headerTools(Request $request)
    {
        
    }
}
