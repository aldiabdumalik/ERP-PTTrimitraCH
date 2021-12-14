<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\CustPrice;
use Carbon\Carbon;
use Exception;
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
        $query = null;
        if (!isset($request->customer)) {
            $query = CustPrice::select([
                    'entry_custprice_tbl.*', 
                    'ekanban_customermaster.CustomerCode_eKanban as custcode', 
                    'ekanban_customermaster.CustomerName as cust_name',
                    'item.PART_NO as part_no',
                    'item.DESCRIPT as desc',
                    'item.DESCRIPT1 as model'
                ])
                ->join('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'entry_custprice_tbl.cust_id')
                ->join('db_tbs.item', function ($join){
                    $join->on('db_tbs.item.ITEMCODE', '=', 'entry_custprice_tbl.item_code');
                    $join->on('db_tbs.item.CUSTCODE', '=', 'entry_custprice_tbl.cust_id');
                })
                // ->groupBy(['cust_id', 'active_date'])
                ->orderBy('created_date', 'DESC')
                ->orderBy('item_code', 'ASC')
                ->get();
        }else{
            $query = CustPrice::select([
                    'entry_custprice_tbl.*', 
                    'ekanban_customermaster.CustomerCode_eKanban as custcode', 
                    'ekanban_customermaster.CustomerName as cust_name',
                    'item.PART_NO as part_no',
                    'item.DESCRIPT as desc',
                    'item.DESCRIPT1 as model'
                ])
                ->join('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'entry_custprice_tbl.cust_id')
                ->join('db_tbs.item', function ($join){
                    $join->on('db_tbs.item.ITEMCODE', '=', 'entry_custprice_tbl.item_code');
                    $join->on('db_tbs.item.CUSTCODE', '=', 'entry_custprice_tbl.cust_id');
                })
                ->where('entry_custprice_tbl.cust_id', $request->customer)
                // ->groupBy(['cust_id', 'active_date'])
                ->orderBy('created_date', 'DESC')
                ->orderBy('item_code', 'ASC')
                ->get();
        }
            
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
            ->editColumn('price_new', function ($query){
                return rupiah(addZero($query->price_new));
            })
            ->editColumn('price_old', function ($query){
                return rupiah(addZero($query->price_old));
            })
            ->addColumn('group', function($query){
                    return "$query->cust_id - ".date('d/m/Y', strtotime($query->active_date));
                }
            )
            ->rawColumns(['action'])
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
                    'item_code' => $items[$i]['itemcode'],
                    'currency' => $request->valas,
                    'price' =>  str_replace(',', '', $items[$i]['new_price']), // $items[$i]['new_price'],
                    'price_new' =>  str_replace(',', '', $items[$i]['new_price']), // $items[$i][4],
                    'price_old' =>  str_replace(',', '', $items[$i]['old_price']), // $items[$i][4],
                    'active_date' => $request->active_date,
                    'created_by' => Auth::user()->FullName,
                    'created_date' => Carbon::now(),
                ];
            }
            try {
                $query = CustPrice::insert($data);
                $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
                    'cust_id' => $request->cust_id,
                    'active_date' => $request->active_date,
                    'written_date' => Carbon::now(),
                    'status' => 'ADD',
                    'user' => Auth::user()->FullName,
                    'note' => null
                ]);

                // Post
                $posted = CustPrice::where([
                        'cust_id' => $request->cust_id,
                        'active_date' => $request->active_date
                    ])->update([
                        'posted_date' => Carbon::now(),
                        'posted_by' => Auth::user()->FullName
                    ]);
                if ($posted) {
                    $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
                        'cust_id' => $request->cust_id,
                        'active_date' => $request->active_date,
                        'written_date' => Carbon::now(),
                        'status' => 'POSTED',
                        'user' => Auth::user()->FullName,
                        'note' => null
                    ]);
                }
                return $this->_Success('Saved successfully!', 201);
            } catch (Exception $e) {
                return $this->_Error('failed to save, please check your form again', 401, $e->getMessage());
            }
        }
        return _Error('failed to save');
    }

    public function update(Request $request, $cust, $active)
    {
        $cek = CustPrice::where('cust_id', $cust)
            ->where('active_date', $active)
            ->first();
        $create_by = $cek->created_by;
        $create_date = $cek->created_date;
        $data = [];
        $items = $request->items;
        $old_data = CustPrice::where('cust_id', $cust)
            ->where('active_date', $active)
            ->delete();
        if (!empty($items)) {
            for ($i=0; $i < count($items); $i++) { 
                $data[] = [
                    'cust_id' => $request->cust_id,
                    'item_code' => $items[$i]['itemcode'],
                    'currency' => $request->valas,
                    'price' =>  str_replace(',', '', $items[$i]['new_price']), // $items[$i]['price_new'],
                    'price_new' =>  str_replace(',', '', $items[$i]['new_price']), // $items[$i]['price_new'],
                    'price_old' =>  str_replace(',', '', $items[$i]['old_price']), // $items[$i][4],
                    'active_date' => $request->active_date,
                    'updated_by' => Auth::user()->FullName,
                    'updated_date' => Carbon::now(),
                    'created_by' => $create_by,
                    'created_date' => $create_date,
                ];
            }
            try {
                $query = CustPrice::insert($data);
                $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
                    'cust_id' => $request->cust_id,
                    'active_date' => $request->active_date,
                    'written_date' => Carbon::now(),
                    'status' => 'EDIT',
                    'user' => Auth::user()->FullName,
                    'note' => null
                ]);

                // Post
                $posted = CustPrice::where([
                        'cust_id' => $request->cust_id,
                        'active_date' => $request->active_date
                    ])->update([
                        'posted_date' => Carbon::now(),
                        'posted_by' => Auth::user()->FullName
                    ]);
                if ($posted) {
                    $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
                        'cust_id' => $request->cust_id,
                        'active_date' => $request->active_date,
                        'written_date' => Carbon::now(),
                        'status' => 'POSTED',
                        'user' => Auth::user()->FullName,
                        'note' => null
                    ]);
                }
                return $this->_Success('Cust Price has been update & posted!', 201);
            } catch (Exception $e) {
                return $this->_Error('failed to save, please check your form again', 401, $e->getMessage());
            }
        }
        return _Error('failed to save');
    }

    public function voided(Request $request)
    {
        $cek = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])
            ->whereNull('posted_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Customer Price has been posted', 404, $cek);
        }

        $void = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])->update([
                'voided_date' => Carbon::now(),
                'voided_by' => Auth::user()->FullName
            ]);
        if ($void) {
            $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
                'cust_id' => $request->cust_id,
                'active_date' => $request->date,
                'written_date' => Carbon::now(),
                'status' => 'VOIDED',
                'user' => Auth::user()->FullName,
                'note' => null
            ]);
        }
        return _Success('Customer Price has been Voided');
    }

    public function unvoided(Request $request)
    {
        $cek = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])
            ->whereNull('posted_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Customer Price has been posted');
        }

        $void = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])->update([
                'voided_date' => null,
                'voided_by' => null
            ]);
        if ($void) {
            $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
                'cust_id' => $request->cust_id,
                'active_date' => $request->date,
                'written_date' => Carbon::now(),
                'status' => 'UNVOIDED',
                'user' => Auth::user()->FullName,
                'note' => $request->note
            ]);
        }
        return _Success('Customer Price has been Unvoided');
    }

    public function posted(Request $request)
    {
        $cek = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])
            ->whereNull('voided_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Customer Price has been voided');
        }

        $posted = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])->update([
                'posted_date' => Carbon::now(),
                'posted_by' => Auth::user()->FullName
            ]);
        if ($posted) {
            $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
                'cust_id' => $request->cust_id,
                'active_date' => $request->date,
                'written_date' => Carbon::now(),
                'status' => 'POSTED',
                'user' => Auth::user()->FullName,
                'note' => null
            ]);
        }
        return _Success('Customer Price has been Posted');
    }

    public function unposted(Request $request)
    {
        $cek = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])
            ->whereNull('voided_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Customer Price has been voided');
        }

        $unposted = CustPrice::where([
                'cust_id' => $request->cust_id,
                'active_date' => $request->date
            ])->update([
                'posted_date' => null,
                'posted_by' => null
            ]);
        if ($unposted) {
            $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
                'cust_id' => $request->cust_id,
                'active_date' => $request->date,
                'written_date' => Carbon::now(),
                'status' => 'UNPOSTED',
                'user' => Auth::user()->FullName,
                'note' => $request->note
            ]);
        }
        return _Success('Customer Price has been Unposted');
    }
    
    public function headerTools(Request $request)
    {
        switch ($request->type) {
            case 'customer':
                return _Success(null, 200, $this->customer());
                break;
            
            case "items":
                if (isset($request->cust_id)) {
                    return DataTables::of($this->items($request->cust_id))
                        ->addIndexColumn()
                        ->make(true);
                    // return DataTables::of($this->items_with_old_price($request->cust_id))->make(true);
                }
                return _Error('Params not exist!', 404);
                break;

            case 'items_selected':
                $result = [];
                $itemcode = $request->items;
                if (!empty($itemcode)) {
                    for ($i=0; $i < count($itemcode); $i++) { 
                        $item = $this->item($itemcode[$i]);
                        $old = $this->_oldPrice($itemcode[$i]);
                        $result[] = [
                            'items' => $item,
                            'old_price' => $old
                        ];
                    }
                    return _Success(null, 200, $result);
                }
                return _Error('Please select a item');
                break;

            case 'currency':
                if (isset($request->currency)) {
                    $res = DB::table('db_tbs.valas')
                        ->where('valas', $request->currency)
                        ->first();
                }else{
                    $res = DB::table('db_tbs.valas')->get();
                }
                return _Success(null, 200, $res);
                break;

            case "log":
                if (isset($request->cust_id) && isset($request->date)) {
                    $query = DB::table('db_tbs.entry_custprice_tbl_log')
                        ->where('cust_id', $request->cust_id)
                        ->where('active_date', $request->date)
                        ->get();
                    return DataTables::of($query)
                        ->addColumn('date', function($query){
                                return convertDate($query->written_date, 'Y-m-d H:i:s', 'd/m/Y');
                            }
                        )
                        ->addColumn('time', function($query){
                                return convertDate($query->written_date, 'Y-m-d H:i:s', 'H:i');
                            }
                        )
                        ->make(true);
                }
                return _Error('Params not exist!', 404);
                break;

            case "validation":
                if (isset($request->cust_id) && isset($request->active)) {
                    $query = CustPrice::select([
                            'entry_custprice_tbl.*', 
                            'ekanban_customermaster.CustomerCode_eKanban', 
                            'ekanban_customermaster.CustomerName',
                            'item.PART_NO',
                            'item.DESCRIPT'
                        ])
                        ->leftJoin('db_tbs.item', 'entry_custprice_tbl.item_code', '=', 'db_tbs.item.itemcode')
                        ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'entry_custprice_tbl.cust_id')
                        ->where('cust_id', $request->cust_id)
                        ->where('active_date', $request->active)
                        ->first();
                    if ($query->posted_date !== null) {
                        return _Error('Customer Price has been posted');
                    }elseif ($query->voided_date !== null) {
                        return _Error('Customer Price has been voided');
                    }else{
                        return _Success('Already to action');
                    }
                }
                return _Error('Params not exist!', 404);
                break;

            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }

    private function _oldPrice($itemcode)
    {
        $query = CustPrice::where('item_code', $itemcode)
            ->selectRaw('item_code, price_new')
            ->orderBy('active_date', 'DESC')
            ->first();
        return $query;
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
