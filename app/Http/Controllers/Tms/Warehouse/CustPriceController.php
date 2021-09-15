<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\CustPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function save(Request $request)
    {
        $data = [];
        $items = $request->items;
        if (!empty($items)) {
            for ($i=0; $i < count($items); $i++) { 
                $data[] = [
                    'cust_id' => $request->cust_id,
                    'item_code' => $items[$i][1],
                    'currency' => $request->valas,
                    'price' =>  str_replace(',', '', $items[$i][4]), // $items[$i][4],
                    'active_date' => $request->active_date,
                    'created_by' => Auth::user()->FullName,
                    'created_date' => Carbon::now(),
                ];
            }
            return _Success(null, 200, $data);
        }
        return _Error('failed to save');
    }

    public function update(Request $request, $cust, $active)
    {
        $cek = CustPrice::select([
                'entry_custprice_tbl.*', 
                'ekanban_customermaster.CustomerCode_eKanban', 
                'ekanban_customermaster.CustomerName',
                'item.PART_NO',
                'item.DESCRIPT'
            ])
            ->leftJoin('db_tbs.item', 'entry_custprice_tbl.item_code', '=', 'db_tbs.item.itemcode')
            ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'entry_custprice_tbl.cust_id')
            ->where('cust_id', $cust)
            ->where('active_date', $active)
            ->get();
        $items = $request->items;
    }

    public function voided(Request $request)
    {
        $cek = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])
            ->whereNotNull('posted_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Customer Price has been posted');
        }

        $void = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])->update([
                'voided_date' => Carbon::now(),
                'voided_by' => Auth::user()->FullName
            ]);
        return _Success('Customer Price has been Voided');
    }

    public function posted(Request $request)
    {
        $cek = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])
            ->whereNotNull('voided_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Customer Price has been voided');
        }

        $void = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])->update([
                'posted_date' => Carbon::now(),
                'posted_by' => Auth::user()->FullName
            ]);
        return _Success('Customer Price has been Posted');
    }
    
    public function headerTools(Request $request)
    {
        switch ($request->type) {
            case 'customer':
                return _Success(null, 200, $this->customer());
                break;
            
            case "items":
                if (isset($request->cust_id)) {
                    // return DataTables::of($this->items($request->cust_id))->make(true);
                    return DataTables::of($this->items_with_old_price($request->cust_id))->make(true);
                }
                return _Error('Params not exist!', 404);
                break;
            
            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }

    public function getitems()
    {
        $cust = 'N02';
        $items = $this->items($cust);
        $items_arr = [];
        foreach ($items as $i) {
            $price = CustPrice::where('item_code', $i->itemcode)
                ->orderBy('active_date', 'DESC')
                ->first();
            if (isset($price)) {
                $items_arr[] = [
                    'itemcode' => $i->itemcode,
                    'part_no' => $i->part_no,
                    'descript' => $i->descript,
                    'unit' => $i->unit,
                    'price' => $price->price_new
                ];
            }else{
                $items_arr[] = [
                    'itemcode' => $i->itemcode,
                    'part_no' => $i->part_no,
                    'descript' => $i->descript,
                    'unit' => $i->unit,
                    'price' => 0
                ];
            }
        }
        print_r($items_arr);
    }

    private function items_with_old_price($cust)
    {
        $items = $this->items($cust);
        $items_arr = [];
        foreach ($items as $i) {
            $price = CustPrice::where('item_code', $i->itemcode)
                ->orderBy('active_date', 'DESC')
                ->first();
            if (isset($price)) {
                $items_arr[] = [
                    'itemcode' => $i->itemcode,
                    'part_no' => $i->part_no,
                    'descript' => $i->descript,
                    'unit' => $i->unit,
                    'price' => $price->price_new
                ];
            }else{
                $items_arr[] = [
                    'itemcode' => $i->itemcode,
                    'part_no' => $i->part_no,
                    'descript' => $i->descript,
                    'unit' => $i->unit,
                    'price' => 0
                ];
            }
        }
        return $items_arr;
    }
}
