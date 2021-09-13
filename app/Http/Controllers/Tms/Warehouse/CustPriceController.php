<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\CustPrice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustPriceController extends Controller
{
    use ToolsTrait;

    public function index()
    {
        return view('tms.warehouse.cust-price.index');
    }

    public function custPriceTable(Request $request)
    {
        $query = CustPrice::select([
                'entry_custprice_tbl.*', 
                'ekanban_customermaster.CustomerCode_eKanban', 
                'ekanban_customermaster.CustomerName'
            ])
            ->join('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'entry_custprice_tbl.cust_id')
            ->groupBy(['cust_id', 'active_date'])
            ->orderBy('created_date', 'DESC')
            ->get();
            
        return DataTables::of($query)
            ->editColumn('created_date', function($query) {
                    return date('d/m/Y', strtotime($query->created_date));
                }
            )
            ->editColumn('active_date', function($query) {
                    return date('d/m/Y', strtotime($query->active_date));
                }
            )
            ->editColumn('posted_date', function($query) {
                    return ($query->posted_date == NULL) ? '/ /' : date('d/m/Y', strtotime($query->posted_date));
                }
            )
            ->editColumn('voided_date', function($query) {
                    return ($query->voided_date == NULL) ? '/ /' : date('d/m/Y', strtotime($query->voided_date));
                }
            )
            ->addColumn('action', function($query){
                    return view('tms.warehouse.cust-price.button.btnTableIndex', ['data' => $query]);
                }
            )
            ->rawColumns(['action'])
            ->make(true);
    }

    public function custPriceDetail($cust, $date)
    {
        $query = CustPrice::select([
                'entry_custprice_tbl.*', 
                'ekanban_customermaster.CustomerCode_eKanban', 
                'ekanban_customermaster.CustomerName',
                'item.PART_NO',
                'item.DESCRIPT'
            ])
            ->leftJoin('db_tbs.item', 'entry_custprice_tbl.item_code', '=', 'db_tbs.item.itemcode')
            ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'entry_custprice_tbl.cust_id')
            ->where('cust_id', $cust)
            ->where('active_date', $date)
            ->get();
        return _Success(null, 200, $query);
    }
    
    public function headerTools(Request $request)
    {
        switch ($request->type) {
            case 'customer':
                return _Success(null, 200, $this->customer());
                break;
            
            case "items":
                if (isset($request->cust_id)) {
                    return DataTables::of($this->items($request->cust_id))->make(true);
                }
                return _Error('Params not exist!', 404);
                break;
            
            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }
}
