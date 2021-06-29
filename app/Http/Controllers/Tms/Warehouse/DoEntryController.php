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
        if (isset($request->tbl_index)) {
            $query = DoEntrySetting::where(function ($query){
                $query->where('user', Auth::user()->FullName);
                $query->where('status', 1);
            })->orderBy('idx', 'asc')->get();
            if ($query->isEmpty()) {
                $query = DoEntrySetting::where(function ($query){
                    $query->where('user', 'default');
                    $query->where('status', 1);
                })->orderBy('idx', 'asc')->get();
            }
        }else{
            $query = DoEntrySetting::where(function ($query){
                $query->where('user', Auth::user()->FullName);
            })->orderBy('idx', 'asc')->get();
            if ($query->isEmpty()) {
                $query = DoEntrySetting::where(function ($query){
                    $query->where('user', 'default');
                })->orderBy('idx', 'asc')->get();
            }
            return DataTables::of($query)
                ->editColumn('status', function($query) {
                    return view('tms.warehouse.do-entry.button.statusTableSetting', [
                        'query' => $query,
                    ]);
                })
                ->addColumn('status_ori', function($query){
                    return $query->status;
                })->rawColumns(['status_ori'])
                ->addIndexColumn()
                ->make(true);
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

    public function DoEntryHeader(Request $request)
    {
        switch ($request->type) {
            case "DONo":
                return $this->headerToolsDoEntryNo($request);
                break;
            case "branch":
                return DataTables::of($this->headerToolsBranch($request))->make(true);
                break;
            case "warehouse":
                return DataTables::of($this->headerToolsWarehouse($request))->make(true);
                break;
            case "customer":
                return DataTables::of($this->headerToolsCustomer($request))->make(true);
                break;
            case "customerclick":
                return $this->headerToolsCustomerClick($request);
                break;
            case "doaddr":
                return DataTables::of($this->headerToolsCustomerAddr($request))->make(true);
                break;
            case "item":
                return DataTables::of($this->headerToolsItem($request))->make(true);
                break;
            case "log":
                return DataTables::of($this->headerToolsLog($request))->make(true);
                break;
            case "setting":
                return $this->_Success('Setting saved!', 201, $this->headerToolsTableSetting($request));
                break;
            case "sso_header":
                $sso = $this->headerToolsSSOHeader($request);
                if (!isset($sso)) {
                    return $this->_Error('SSO data not found!', 404);
                }else{
                    return $this->_Success('OK!', 200, $sso);
                }
                break;
            case "so_header":
                $so = $this->headerToolsSOHeader($request);
                if (!isset($so)) {
                    return $this->_Error('SO data not found!', 404);
                }else{
                    return $this->_Success('OK!', 200, $so);
                }
                break;
            case "sso_detail":
                $result = $this->headerToolsSSODetail($request);
                if ($result->isEmpty()) {
                    return $this->_Error('SO data not found!', 404);
                }else{
                    return $this->_Success('OK!', 200, $result);
                }
                break;
            default:
                return $this->_Error('Methode Not Found');
        }
    }
}
