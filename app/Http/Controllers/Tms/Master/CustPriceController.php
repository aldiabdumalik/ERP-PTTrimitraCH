<?php

namespace App\Http\Controllers\TMS\Master;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\AR\CustPriceTrait;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Jobs\CustPricePosted;
use App\Models\Dbtbs\CustPrice;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CustPriceController extends Controller
{
    use ToolsTrait, CustPriceTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.master.cust-price.index');
    }

    public function custPriceTable(Request $request)
    {
        $query = null;
        if (!isset($request->customer)) {
            $query = CustPrice::query()->select([
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
                // ->where('entry_custprice_tbl.status', 'ACTIVE')
                // ->groupBy(['cust_id', 'active_date'])
                ->orderBy('entry_custprice_tbl.created_date', 'DESC')
                ->orderBy('part_no', 'ASC')
                ->get();
        }else{
            $query = CustPrice::query()->select([
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
                // ->where('entry_custprice_tbl.status', 'ACTIVE')
                ->where('entry_custprice_tbl.cust_id', $request->customer)
                // ->groupBy(['cust_id', 'active_date'])
                ->orderBy('created_date', 'DESC')
                ->orderBy('part_no', 'ASC')
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
            ->editColumn('item_code', function ($query){
                return view('tms.master.cust-price.button.btnItemCode', ['data' => $query]);
            })
            ->addColumn('group', function($query){
                    return "$query->cust_id|".date('d/m/Y', strtotime($query->active_date))."|Active date|$query->cust_name";
                }
            )
            ->rawColumns(['action'])
            ->addColumn('action', function($query){
                    return view('tms.master.cust-price.button.btnTableIndex', ['data' => $query]);
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
            // ->where('entry_custprice_tbl.status', 'ACTIVE')
            ->orderBy('entry_custprice_tbl.item_code', 'ASC')
            ->get();
        return _Success(null, 200, $query);
    }

    public function save(Request $request)
    {
        $data = [];
        $items = json_decode($request->items, true);
        $is_update = 0;
        $price_old = 0;
        $is_stock = ($request->post['stock'] == 'true') ? 1 : 0;
        $is_so = ($request->post['so'] == 'true') ? 1 : 0;
        $is_sso = ($request->post['sso'] == 'true') ? 1 : 0;
        $is_sj = ($request->post['sj'] == 'true') ? 1 : 0;
        if (!empty($items)) {
            for ($i=0; $i < count($items); $i++) { 
                $item_replace = str_replace(' ', '', $items[$i]['itemcode']);
                $old = CustPrice::where('status', 'ACTIVE')->where('item_code', $item_replace)->orderBy('active_date', 'DESC')->first();
                if ($old) {
                    $prices = str_replace(',', '', $items[$i]['new_price']);
                    $is_update = ($old->price_new != $prices) ? $is_update = 1 : $is_update = 0;
                    $price_old = $old->price_new;

                    // Add range date

                    CustPrice::where('item_code', $item_replace)
                        ->where('active_date', $old->active_date)
                        ->update(['range_date' => date('Y-m-d', strtotime($old->active_date. "-1 days"))]);
                }else{
                    $is_update = 0;
                    $price_old = 0;
                }
                $data[] = [
                    'cust_id' => $request->cust_id,
                    'item_code' => str_replace(' ', '', $items[$i]['itemcode']),
                    'currency' => $request->valas,
                    'price' =>  str_replace(',', '', $items[$i]['new_price']), // $items[$i]['new_price'],
                    'price_new' =>  str_replace(',', '', $items[$i]['new_price']), // $items[$i][4],
                    'price_old' =>  $price_old, // str_replace(',', '', $items[$i]['old_price']), // $items[$i][4],
                    'active_date' => $request->active_date,
                    'created_by' => Auth::user()->FullName,
                    'created_date' => Carbon::now(),
                    'is_update' => $is_update,
                    'price_by' => $request->price_by,
                    'is_stock' => $is_stock,
                    'is_so' => $is_so,
                    'is_sso' => $is_sso,
                    'is_sj' => $is_sj,
                    'posted_date' => Carbon::now(),
                    'posted_by' => Auth::user()->FullName
                ];

                CustPrice::where('cust_id', $request->cust_id)
                    ->where('item_code', $item_replace)
                    ->update(['status' => 'NOT ACTIVE']);
            }
            // DB::connection('db_tbs')->beginTransaction();
            try {
                if ($request->price_by == 'DATE') {
                    // Trigger By Date
                    $trg = $this->triggerDate($data);
                }else{
                    // $non_active = CustPrice::where('cust_id', $request->cust_id)->update(['status' => 'NOT ACTIVE']);
                    $trg = $this->triggerSO($data);
                }
                $query = CustPrice::insert($data);
                if ($query) {
                    $log = [
                        [
                            'cust_id' => $request->cust_id,
                            'active_date' => $request->active_date,
                            'written_date' => Carbon::now(),
                            'status' => 'ADD',
                            'user' => Auth::user()->FullName,
                            'note' => null
                        ],
                        [
                            'cust_id' => $request->cust_id,
                            'active_date' => $request->active_date,
                            'written_date' => Carbon::now(),
                            'status' => 'POSTED',
                            'user' => Auth::user()->FullName,
                            'note' => null
                        ]
                    ];
                    $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', $log);
                }
                // DB::connection('db_tbs')->commit();
                return $this->_Success('Saved successfully!', 201, $trg);
            } catch (Exception $e) {
                // DB::connection('db_tbs')->rollBack();
                return $this->_Error('failed to save, please check your form again', 401, $e->getMessage());
            }
        }
        return _Error('failed to save');
    }

    public function update(Request $request, $cust, $active)
    {
        $cek = CustPrice::where('cust_id', $cust)
            ->where('active_date', $active)
            // ->where('entry_custprice_tbl.status', 'ACTIVE')
            ->first();
        $create_by = $cek->created_by;
        $create_date = $cek->created_date;
        $data = [];
        $items = json_decode($request->items, true);
        $is_update = 0;
        $price_old = 0;
        $is_stock = ($request->post['stock'] == 'true') ? 1 : 0;
        $is_so = ($request->post['so'] == 'true') ? 1 : 0;
        $is_sso = ($request->post['sso'] == 'true') ? 1 : 0;
        $is_sj = ($request->post['sj'] == 'true') ? 1 : 0;
        if (!empty($items)) {
            for ($i=0; $i < count($items); $i++) {
                $itemcode_s = str_replace(' ', '', $items[$i]['itemcode']);
                $old = CustPrice::where('status', 'ACTIVE')->where('item_code', $itemcode_s)->orderBy('active_date', 'DESC')->first();
                if ($old) {
                    $prices = str_replace(',', '', $items[$i]['new_price']);
                    $is_update = ($old->price_new != $prices) ? $is_update = 1 : $is_update = 0;
                    $price_old = $old->price_new;
                }else{
                    $is_update = 0;
                    $price_old = 0;
                }

                $data[] = [
                    'cust_id' => $request->cust_id,
                    'item_code' => $itemcode_s,
                    'currency' => $request->valas,
                    'price' =>  str_replace(',', '', $items[$i]['new_price']), // $items[$i]['price_new'],
                    'price_new' =>  str_replace(',', '', $items[$i]['new_price']), // $items[$i]['price_new'],
                    'price_old' =>  $price_old, // str_replace(',', '', $items[$i]['old_price']), // $items[$i][4],
                    'active_date' => $request->active_date,
                    'updated_by' => Auth::user()->FullName,
                    'updated_date' => Carbon::now(),
                    'created_by' => $create_by,
                    'created_date' => Carbon::now(),
                    'is_update' => $is_update,
                    'price_by' => $request->price_by,
                    'is_stock' => $is_stock,
                    'is_so' => $is_so,
                    'is_sso' => $is_sso,
                    'is_sj' => $is_sj,
                    'posted_date' => Carbon::now(),
                    'posted_by' => Auth::user()->FullName
                ];

                CustPrice::where('cust_id', $request->cust_id)
                    ->where('item_code', $itemcode_s)
                    ->update(['status' => 'NOT ACTIVE']);
            }
            // DB::connection('db_tbs')->beginTransaction();
            try {
                // $isext = 0;
                // $test = null;
                if ($request->price_by == 'DATE') {
                    // $cekBln = CustPrice::where('cust_id', $request->cust_id)
                    //     ->whereMonth('active_date', '=', convertDate($request->active_date, 'Y-m-d', 'm'))
                    //     ->whereYear('active_date', '=', convertDate($request->active_date, 'Y-m-d', 'Y'))
                    //     ->first();
                    // if (!$cekBln) {
                    //     $isext = 0;
                    //     // $non_active = CustPrice::where('cust_id', $request->cust_id)->update(['status' => 'NOT ACTIVE']);
                    // }else{
                    //     $isext = 1;
                    // }

                    // Trigger By Date
                    $trg = $this->triggerDate($data);
                }else{
                    // Trigger By SO
                    $trg = $this->triggerSO($data);
                }
                
                CustPrice::where('cust_id', $cust)
                    ->where('active_date', $request->active_date)
                    // ->where('entry_custprice_tbl.status', 'ACTIVE')
                    ->delete();

                $query = CustPrice::insert($data);
                if ($query) {
                    $log = [
                        [
                            'cust_id' => $request->cust_id,
                            'active_date' => $request->active_date,
                            'written_date' => Carbon::now(),
                            'status' => 'EDIT',
                            'user' => Auth::user()->FullName,
                            'note' => null
                        ],
                        [
                            'cust_id' => $request->cust_id,
                            'active_date' => $request->active_date,
                            'written_date' => Carbon::now(),
                            'status' => 'POSTED',
                            'user' => Auth::user()->FullName,
                            'note' => null
                        ]
                    ];
                    $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', $log);
                }

                // DB::connection('db_tbs')->commit();
                return $this->_Success('Cust Price has been update & posted!', 201, $trg);
            } catch (Exception $e) {
                // DB::connection('db_tbs')->rollBack();
                return $this->_Error('failed to update, please check your form again', 401, $e->getMessage());
            }
        }
        return _Error('failed to update');
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
        $is_stock = ($request->stock == 'true') ? 1 : 0;
        $is_so = ($request->so == 'true') ? 1 : 0;
        $is_sso = ($request->sso == 'true') ? 1 : 0;
        $is_sj = ($request->sj == 'true') ? 1 : 0;

        $query = CustPrice::where('active_date', $request->date)->where('cust_id', $request->cust)->get();

        $data = [];
        $price_by = null;

        if ($query->isNotEmpty()) {
            foreach ($query as $v) {
                $data[] = [
                    'cust_id' => $v['cust_id'],
                    'item_code' => $v['item_code'],
                    'currency' => $v['currency'],
                    'price_new' =>  $v['price_new'],
                    'price_old' =>  $v['price_old'],
                    'active_date' => $v['active_date'],
                    'price_by' => $v['price_by'],
                    'is_stock' => $is_stock,
                    'is_so' => $is_so,
                    'is_sso' => $is_sso,
                    'is_sj' => $is_sj
                ];

                $price_by = $v['price_by'];
            }

            if ($price_by == 'SO') {
                $trg = $this->repostSO($data);
            }else{
                $trg = $this->repostDate($data);
            }

            return _Success('Cust Price has been Reposted', 200, $trg);
        }

        // $posted = CustPrice::where([
        //         'cust_id' => $request->cust_id,
        //         'active_date' => $request->date
        //     ])->update([
        //         'posted_date' => Carbon::now(),
        //         'posted_by' => Auth::user()->FullName,
        //         'is_stock' => $is_stock,
        //         'is_so' => $is_so,
        //         'is_sso' => $is_sso,
        //         'is_sj' => $is_sj
        //     ]);
        // if ($posted) {
        //     $log = $this->createGlobalLog('db_tbs.entry_custprice_tbl_log', [
        //         'cust_id' => $request->cust_id,
        //         'active_date' => $request->date,
        //         'written_date' => Carbon::now(),
        //         'status' => 'REPOSTED',
        //         'user' => Auth::user()->FullName,
        //         'note' => null
        //     ]);
        // }
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
                'posted_by' => null,
                'is_stock' => 0,
                'is_so' => 0,
                'is_sso' => 0,
                'is_sj' => 0
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

    public function print(Request $request, $code)
    {
        $decode = base64_decode($code);
        $arr = explode('&', $decode);
        $cust = $arr[0];
        $date = $arr[1];
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
            ->where(function ($x) use ($cust, $date) {
                $x->where('entry_custprice_tbl.cust_id', $cust);
                $x->where('entry_custprice_tbl.active_date', $date);
            })
            ->orderBy('entry_custprice_tbl.item_code', 'ASC')
            ->get();
        if ($query->isEmpty()) {
            $request->session()->flash('message', 'Data Not Found!');
            return Redirect::back();
        }
        CustPrice::where(function ($x) use ($cust, $date) {
            $x->where('cust_id', $cust);
            $x->where('active_date', $date);
        })->update([
            'printed_date' => date('Y-m-d')
        ]);
        $pdf = PDF::loadView('tms.master.cust-price.report.report', compact('query'))->setPaper('a4', 'potrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream();
    }
    
    public function headerTools(Request $request)
    {
        switch ($request->type) {
            case 'customer':
                return _Success(null, 200, $this->customer());
                break;
            
            case "items":
                if (isset($request->cust_id)) {
                    $item = $this->_item_with_oldprice($request->cust_id);
                    return DataTables::of($item)
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
            case "item_log":
                $res = CustPrice::where('item_code', $request->id)->orderBy('created_date', 'DESC')->get();
                return DataTables::of($res)
                    ->editColumn('price_new', function ($res){
                        return rupiah(addZero($res->price_new));
                    })
                    ->editColumn('price_old', function ($res){
                        return rupiah(addZero($res->price_old));
                    })
                    ->editColumn('active_date', function ($res){
                        return (is_null($res->active_date) ? '//' : date('d/m/Y', strtotime($res->active_date)));
                    })
                    ->editColumn('range_date', function ($res){
                        return (is_null($res->range_date) ? '//' : date('d/m/Y', strtotime($res->range_date)));
                    })
                    ->make(true);
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
            
            case 'customerclick':
                if (isset($request->cust_id)) {
                    $last = CustPrice::where('entry_custprice_tbl.cust_id', $request->cust_id)
                        ->where('entry_custprice_tbl.status', 'ACTIVE')
                        ->orderBy('created_date', 'DESC')
                        ->first();
                    if ($last) {
                        $query = CustPrice::select([
                            'entry_custprice_tbl.*', 
                            'ekanban_customermaster.CustomerCode_eKanban as cuscode', 
                            'ekanban_customermaster.CustomerName as custname',
                            'item.PART_NO as part_no',
                            'item.DESCRIPT as desc',
                            DB::raw('TRIM(item.ITEMCODE) as itemcode_trims')
                        ])
                        ->leftJoin('db_tbs.item', 'entry_custprice_tbl.item_code', '=', 'db_tbs.item.itemcode')
                        ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'entry_custprice_tbl.cust_id')
                        ->where('entry_custprice_tbl.cust_id', $request->cust_id)
                        ->where('entry_custprice_tbl.active_date', $last->active_date)
                        // ->where('entry_custprice_tbl.status', 'ACTIVE')
                        ->get();
                    }else{
                        $query = CustPrice::select([
                                'entry_custprice_tbl.*', 
                                'ekanban_customermaster.CustomerCode_eKanban as cuscode', 
                                'ekanban_customermaster.CustomerName as custname',
                                'item.PART_NO as part_no',
                                'item.DESCRIPT as desc',
                                DB::raw('TRIM(item.ITEMCODE) as itemcode_trims')
                            ])
                            ->leftJoin('db_tbs.item', 'entry_custprice_tbl.item_code', '=', 'db_tbs.item.itemcode')
                            ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'entry_custprice_tbl.cust_id')
                            ->where('entry_custprice_tbl.cust_id', $request->cust_id)
                            // ->where('entry_custprice_tbl.status', 'ACTIVE')
                            ->get();
                    }
                    if ($query->isEmpty()) {
                        return _Success(null, 200);
                    }else{
                        return _Success(null, 200, $query);
                    }
                }
                return _Error('Params not exist!', 404);
                break;

            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }

    private function _trgSO($data, $period, $cust)
    {
        DB::connection('db_tbs')->beginTransaction();
        DB::connection('tch_tbs')->beginTransaction();
        try {
            foreach ($data as $d) {
                $item_i = $d['item_code'];
                $act_date = $d['active_date'];

                if ($d['is_stock'] == 1) {
                    DB::table('db_tbs.item')
                        ->where('ITEMCODE', $d['item_code'])
                        ->update([
                            'PRICE' => $d['price_new']
                        ]);
                    DB::table('tch_tbs.item')
                        ->where('itemcode', $d['item_code'])
                        ->update([
                            'price' => $d['price_new']
                        ]);
                }

                $so = DB::table('db_tbs.entry_so_tbl as so')
                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                        $join->on('sso.so_header', '=', 'so.so_header');
                        $join->on('sso.item_code', '=', 'so.item_code');
                    })
                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                        $join->on('sj.so_no', '=', 'so.so_header');
                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                        $join->on('sj.item_code', '=', 'so.item_code');
                    })
                    ->where('so.cust_id', $cust)
                    ->where('so.written_date', '>=', $act_date)
                    ->where('so.item_code', $item_i)
                    ->whereNull('sj.invoice_date')
                    ->select([
                        'so.so_header',
                        'so.so_period',
                        'so.tax_rate as so_tax_rate',
                        'so.item_code as so_item_code',
                        'so.price as so_price',
                        'so.qty_so as so_qty_so',
                        'so.sub_amount as so_sub_amount',
                        'so.tot_vat as so_tot_vat',
                        'so.total_amount as so_total_amount',
                        'sso.sso_header as sso_header',
                        'sj.do_no as sj_number',
                    ])
                ->get();

                
                if ($so->isNotEmpty()) {
                    foreach ($so as $s) {
                        $so_header = $s->so_header;
                        $sub_amt = $d['price_new'] * $s->so_qty_so;
                        $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                        $total_amount = $sub_amt + $tot_vat;

                        DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.so_header', $so_header)
                            ->where('so.item_code', $d['item_code'])
                            ->whereNull('sj.invoice_date')
                            ->update([
                                'so.price' => $d['price_new'],
                                'so.sub_amount' => $sub_amt,
                                'so.tot_vat' => $tot_vat,
                                'so.total_amount' => $total_amount
                            ]);

                        if ($d['is_sso'] == 1) {
                            $cvrt = $act_date;
                            $custprice_id = $cust.'.'.$cvrt;
                            DB::table('db_tbs.entry_sso_tbl as sso')
                                ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                    $join->on('sj.sso_no', '=', 'sso.sso_header');
                                    $join->on('sj.item_code', '=', 'sso.item_code');
                                })
                                ->where('sso.so_header', $so_header)
                                ->where('sso.item_code', $d['item_code'])
                                ->whereNull('sj.invoice_date')
                                ->whereRaw('DATE(sso.created_date) >= ?', [$act_date])
                                ->update([
                                    'sso.custprice' => $custprice_id
                                ]);
                        }
                        
                        if ($d['is_sj'] == 1) {
                            $cvrt = $act_date;
                            $custprice_id = $cust.'.'.$cvrt;
                            DB::table('db_tbs.entry_do_tbl as sj')
                                ->where('sj.so_no', $so_header)
                                ->where('sj.item_code', $d['item_code'])
                                ->whereNull('sj.invoice_date')
                                ->whereRaw('DATE(sj.created_date) >= ?', [$act_date])
                                ->update([
                                    'sj.custprice' => $custprice_id
                                ]);
                        }
                    }
                }

                $so_tch = DB::table('tch_tbs.soline as so_dtl')
                    ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                    ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.so_no', '=', 'so_dtl.so_no')
                    ->leftJoin('tch_tbs.sso_dtl as sso_dtl', function ($join){
                        $join->on('sso_dtl.so_no', '=', 'so_dtl.so_no');
                        $join->on('sso_dtl.itemcode', '=', 'so_dtl.itemcode');
                    })
                    ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                        $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                        $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                        $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                    })
                    ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                    ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                        $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                    })
                    ->where(function ($wh) use ($cust, $act_date, $item_i){
                        $wh->where('so_hdr.custcode', $cust);
                        $wh->where('so_hdr.written', '>=', $act_date);
                        $wh->where('so_dtl.itemcode', $item_i);
                        $wh->whereNull('inv_dtl.do_no');
                    })
                    ->select([
                        'so_dtl.so_no',
                        'so_hdr.period as so_period',
                        'so_hdr.taxrate as so_tax_rate',
                        'so_dtl.itemcode as so_item_code',
                        'so_dtl.price as so_price',
                        'so_dtl.quantity as so_qty_so',
                        'so_hdr.sub_amt as so_sub_amount',
                        'so_hdr.tot_disc as so_tot_vat',
                        'so_hdr.tot_amt as so_total_amount',
                        'inv_dtl.do_no as inv_do'
                    ])
                    ->get();
                if ($so_tch->isNotEmpty()) {

                    foreach ($so_tch as $so) {
                        $so_header = $so->so_no;
                        if ($d['is_so'] == 1) {
                            $sub_amt = $d['price_new'] * $s->so_qty_so;
                            $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                            $total_amount = $sub_amt + $tot_vat;

                            DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                    $wh->where('so_dtl.so_no', $so_header);
                                    $wh->where('so_dtl.itemcode', $item_i);
                                    $wh->where('so_dtl.written', '>=', $act_date);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'so_dtl.price' => $d['price_new'],
                                    'so_hdr.sub_amt' => $sub_amt,
                                    'so_hdr.tot_disc' => $tot_vat,
                                    'so_hdr.tot_amt' => $total_amount
                                ]);
                        }
                        if ($d['is_sso'] == 1) {
                            $sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                    $wh->where('sso_dtl.so_no', $so_header);
                                    $wh->where('sso_dtl.itemcode', $item_i);
                                    $wh->where('sso_dtl.written', '>=', $act_date);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'sso_dtl.so_no',
                                    'sso_hdr.period as sso_period',
                                    'sso_hdr.taxrate as sso_tax_rate',
                                    'sso_dtl.itemcode as sso_item_code',
                                    'sso_dtl.price as sso_price',
                                    'sso_dtl.quantity as sso_qty_sso',
                                    'sso_hdr.sub_amt as sso_sub_amount',
                                    'sso_hdr.tot_disc as sso_tot_vat',
                                    'sso_hdr.tot_amt as sso_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            if ($sso_tch->isNotEmpty()) {
                                foreach ($sso_tch as $s) {
                                    $sub_amt = $d['price_new'] * $s->sso_qty_sso;
                                    $tot_vat = $sub_amt * $s->sso_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.sso_dtl as sso_dtl')
                                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                        })
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                            $wh->where('sso_dtl.so_no', $so_header);
                                            $wh->where('sso_dtl.itemcode', $item_i);
                                            $wh->where('sso_dtl.written', '>=', $act_date);
                                            $wh->whereNull('inv_dtl.do_no');
                                        })
                                        ->update([
                                            'sso_dtl.price' => $d['price_new'],
                                            'sso_hdr.sub_amt' => $sub_amt,
                                            'sso_hdr.tot_disc' => $tot_vat,
                                            'sso_hdr.tot_amt' => $total_amount
                                        ]);
                                }
                            }
                        }

                        if ($d['is_sj'] == 1) {
                            $do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                    $wh->where('sj_dtl.so_no', $so_header);
                                    $wh->where('sj_dtl.itemcode', $item_i);
                                    $wh->where('sj_dtl.written', '>=', $act_date);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->select([
                                    'sj_dtl.do_no',
                                    'sj_hdr.period as sj_period',
                                    'sj_hdr.taxrate as sj_tax_rate',
                                    'sj_dtl.itemcode as sj_item_code',
                                    'sj_dtl.price as sj_price',
                                    'sj_dtl.quantity as sj_qty',
                                    'sj_hdr.sub_amt as sj_sub_amount',
                                    'sj_hdr.tot_disc as sj_tot_vat',
                                    'sj_hdr.tot_amt as sj_total_amount',
                                    'inv_dtl.do_no as inv_do'
                                ])
                                ->get();
                            if ($do_tch->isNotEmpty()) {
                                foreach ($do_tch as $s) {
                                    $sub_amt = $d['price_new'] * $s->sj_qty;
                                    $tot_vat = $sub_amt * $s->sj_tax_rate / 100;
                                    $total_amount = $sub_amt + $tot_vat;
                                    DB::table('tch_tbs.do_dtl as sj_dtl')
                                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                        })
                                        ->where(function ($wh) use ($so_header, $item_i, $act_date){
                                            $wh->where('sj_dtl.so_no', $so_header);
                                            $wh->where('sj_dtl.itemcode', $item_i);
                                            $wh->where('sj_dtl.written', '>=', $act_date);
                                            $wh->whereNull('inv_dtl.do_no');
                                        })
                                    ->update([
                                        'sj_dtl.price' => $d['price_new'],
                                        'sj_hdr.sub_amt' => $sub_amt,
                                        'sj_hdr.tot_disc' => $tot_vat,
                                        'sj_hdr.tot_amt' => $total_amount
                                    ]);
                                }
                            }
                        }
                    }
                    // END
                }
            }
            DB::connection('db_tbs')->commit();
            DB::connection('tch_tbs')->commit();
            return true;
        } catch (Exception $e) {
            Log::channel('queue')->info($e->getMessage());
            DB::connection('db_tbs')->rollBack();
            DB::connection('tch_tbs')->rollBack();
        }
    }

    private function _trgDate($data, $period, $cust, $is_exist)
    {
        DB::connection('db_tbs')->beginTransaction();
        DB::connection('tch_tbs')->beginTransaction();
        try {

            foreach ($data as $key => $d) {
                $item_i = $d['item_code'];
                $act_date = $d['active_date'];
                $so = DB::table('db_tbs.entry_so_tbl as so')
                    ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                        $join->on('sso.so_header', '=', 'so.so_header');
                        $join->on('sso.item_code', '=', 'so.item_code');
                    })
                    ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                        $join->on('sj.so_no', '=', 'so.so_header');
                        $join->on('sj.sso_no', '=', 'sso.sso_header');
                        $join->on('sj.item_code', '=', 'so.item_code');
                    })
                    ->where('so.cust_id', $cust)
                    ->where('so.item_code', $d['item_code'])
                    ->where(function ($where) use ($act_date){
                        $where->whereRaw('DATE(so.written_date) >= ?', [$act_date]);
                    })
                    ->whereNull('sj.invoice_date')
                    ->select([
                        'so.so_header',
                        'so.so_period',
                        'so.tax_rate as so_tax_rate',
                        'so.item_code as so_item_code',
                        'so.price as so_price',
                        'so.qty_so as so_qty_so',
                        'so.sub_amount as so_sub_amount',
                        'so.tot_vat as so_tot_vat',
                        'so.total_amount as so_total_amount',
                        'sso.sso_header as sso_header',
                        'sj.do_no as sj_number',
                    ])
                ->get();

                if ($so->isNotEmpty()) {
                    foreach ($so as $s) {
                        $sub_amt = $d['price_new'] * $s->qty_so;
                        $tot_vat = $sub_amt * $s->tax_rate / 100;
                        $total_amount = $sub_amt + $tot_vat;
                        DB::table('db_tbs.entry_so_tbl as so')
                            ->leftJoin('db_tbs.entry_sso_tbl as sso', function ($join){
                                $join->on('sso.so_header', '=', 'so.so_header');
                                $join->on('sso.item_code', '=', 'so.item_code');
                            })
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.so_no', '=', 'so.so_header');
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'so.item_code');
                            })
                            ->where('so.cust_id', $cust)
                            ->where('so.item_code', $d['item_code'])
                            ->where(function ($where) use ($act_date){
                                $where->whereRaw('DATE(so.written_date) >= ?', [$act_date]);
                            })
                            ->whereNull('sj.invoice_date')
                            ->update([
                                'price' => $d['price_new'],
                                'sub_amount' => $sub_amt,
                                'tot_vat' => $tot_vat,
                                'total_amount' => $total_amount
                            ]);
                        Log::channel('queue')->info("Itemcode ".$d['item_code']." updated price");
                    }
                }

                if ($d['is_sso'] == 1) {
                    $sso_tms = DB::table('db_tbs.entry_sso_tbl as sso')
                        ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                            $join->on('sj.sso_no', '=', 'sso.sso_header');
                            $join->on('sj.item_code', '=', 'sso.item_code');
                        })
                        ->where('sso.item_code', $d['item_code'])
                        ->where(function ($where) use ($act_date){
                            $where->whereRaw('DATE(sso.created_date) >= ?', [$act_date]);
                        })
                        ->whereNull('sj.invoice_date')
                        ->get();
                    if ($sso_tms->isNotEmpty()) {
                        $cvrt = $act_date;
                        $custprice_id = $cust.'.'.$cvrt;
                        DB::table('db_tbs.entry_sso_tbl as sso')
                            ->leftJoin('db_tbs.entry_do_tbl as sj', function ($join){
                                $join->on('sj.sso_no', '=', 'sso.sso_header');
                                $join->on('sj.item_code', '=', 'sso.item_code');
                            })
                            ->where('sso.item_code', $d['item_code'])
                            ->where(function ($where) use ($act_date){
                                $where->whereRaw('DATE(sso.created_date) >= ?', [$act_date]);
                            })
                            ->whereNull('sj.invoice_date')
                            ->update([
                                'sso.custprice' => $custprice_id
                            ]);
                    }
                }

                if ($d['is_so'] == 1) {
                    $so_tch = DB::table('tch_tbs.soline as so_dtl')
                        ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                            $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                            $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                        })
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date, $is_exist){
                            $where->whereNotNull('so_hdr.written');
                            $where->where('so_hdr.written', '>=', $act_date);
                            // if ($is_exist == 1) {
                            //     $where->where('so_hdr.posted', '>=', $act_date);
                            // }
                            // $where->whereMonth('so_hdr.posted', date('m', strtotime($act_date)));
                            // $where->whereYear('so_hdr.posted', date('Y', strtotime($act_date)));
                        })
                        ->where(function ($wh) use ($cust, $item_i){
                            $wh->where('so_hdr.custcode', $cust);
                            // $wh->where('so_hdr.period', $period);
                            $wh->where('so_dtl.itemcode', $item_i);
                            $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'so_dtl.so_no',
                            'so_hdr.period as so_period',
                            'so_hdr.taxrate as so_tax_rate',
                            'so_dtl.itemcode as so_item_code',
                            'so_dtl.price as so_price',
                            'so_dtl.quantity as so_qty_so',
                            'so_hdr.sub_amt as so_sub_amount',
                            'so_hdr.tot_disc as so_tot_vat',
                            'so_hdr.tot_amt as so_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($so_tch->isNotEmpty()) {
                        foreach ($so_tch as $s) {
                            $sub_amt = $d['price_new'] * $s->so_qty_so;
                            $tot_vat = $sub_amt * $s->so_tax_rate / 100;
                            $total_amount = $sub_amt + $tot_vat;
                            DB::table('tch_tbs.soline as so_dtl')
                                ->leftJoin('tch_tbs.sohdr as so_hdr', 'so_hdr.so_no', '=', 'so_dtl.so_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.so_no', '=', 'so_dtl.so_no');
                                    $join->on('sj_dtl.itemcode', '=', 'so_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date, $is_exist){
                                    $where->whereNotNull('so_hdr.written');
                                    $where->where('so_hdr.written', '>=', $act_date);
                                    // $where->whereNotNull('so_hdr.posted');
                                    // if ($is_exist == 1) {
                                    //     $where->where('so_hdr.posted', '>=', $act_date);
                                    // }
                                    // $where->whereMonth('so_hdr.posted', date('m', strtotime($act_date)));
                                    // $where->whereYear('so_hdr.posted', date('Y', strtotime($act_date)));
                                })
                                ->where(function ($wh) use ($cust, $item_i){
                                    $wh->where('so_hdr.custcode', $cust);
                                    // $wh->where('so_hdr.period', $period);
                                    $wh->where('so_dtl.itemcode', $item_i);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'so_dtl.price' => $d['price_new'],
                                    'so_hdr.sub_amt' => $sub_amt,
                                    'so_hdr.tot_disc' => $tot_vat,
                                    'so_hdr.tot_amt' => $total_amount
                                ]);
                        }
                    }
                }

                if ($d['is_sso'] == 1) {
                    $sso_tch = DB::table('tch_tbs.sso_dtl as sso_dtl')
                        ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                        ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                            $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                            $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                        })
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date, $is_exist){
                            $where->whereNotNull('sso_hdr.written');
                            $where->where('sso_hdr.written', '>=', $act_date);
                            // $where->whereNotNull('sso_hdr.posted');
                            // if ($is_exist == 1) {
                            //     $where->where('sso_hdr.posted', '>=', $act_date);
                            // }
                            // $where->whereMonth('sso_hdr.posted', date('m', strtotime($act_date)));
                            // $where->whereYear('sso_hdr.posted', date('Y', strtotime($act_date)));
                        })
                        ->where(function ($wh) use ($cust, $period, $item_i){
                            $wh->where('sso_hdr.custcode', $cust);
                            $wh->where('sso_dtl.itemcode', $item_i);
                            $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'sso_dtl.so_no',
                            'sso_hdr.period as sso_period',
                            'sso_hdr.taxrate as sso_tax_rate',
                            'sso_dtl.itemcode as sso_item_code',
                            'sso_dtl.price as sso_price',
                            'sso_dtl.quantity as sso_qty_sso',
                            'sso_hdr.sub_amt as sso_sub_amount',
                            'sso_hdr.tot_disc as sso_tot_vat',
                            'sso_hdr.tot_amt as sso_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($sso_tch->isNotEmpty()) {
                        foreach ($sso_tch as $s) {
                            $sub_amt = $d['price_new'] * $s->sso_qty_sso;
                            $tot_vat = $sub_amt * $s->sso_tax_rate / 100;
                            $total_amount = $sub_amt + $tot_vat;
                            DB::table('tch_tbs.sso_dtl as sso_dtl')
                                ->leftJoin('tch_tbs.sso_hdr as sso_hdr', 'sso_hdr.sso_no', '=', 'sso_dtl.sso_no')
                                ->leftJoin('tch_tbs.do_dtl as sj_dtl', function ($join){
                                    $join->on('sj_dtl.sso_no', '=', 'sso_dtl.sso_no');
                                    $join->on('sj_dtl.itemcode', '=', 'sso_dtl.itemcode');
                                })
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date, $is_exist){
                                    $where->whereNotNull('sso_hdr.written');
                                    $where->where('sso_hdr.written', '>=', $act_date);
                                    // $where->whereNotNull('sso_hdr.posted');
                                    // if ($is_exist == 1) {
                                    //     $where->where('sso_hdr.posted', '>=', $act_date);
                                    // }
                                    // $where->whereMonth('sso_hdr.posted', date('m', strtotime($act_date)));
                                    // $where->whereYear('sso_hdr.posted', date('Y', strtotime($act_date)));
                                })
                                ->where(function ($wh) use ($cust, $period, $item_i){
                                    $wh->where('sso_hdr.custcode', $cust);
                                    $wh->where('sso_dtl.itemcode', $item_i);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'sso_dtl.price' => $d['price_new'],
                                    'sso_hdr.sub_amt' => $sub_amt,
                                    'sso_hdr.tot_disc' => $tot_vat,
                                    'sso_hdr.tot_amt' => $total_amount
                                ]);
                        }
                    }
                }

                if ($d['is_sj'] == 1) {
                    $do_tch = DB::table('tch_tbs.do_dtl as sj_dtl')
                        ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                        ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                            $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                        })
                        ->where(function ($where) use ($act_date, $is_exist){
                            $where->whereNotNull('sj_hdr.written');
                            $where->where('sj_hdr.written', '>=', $act_date);
                            // $where->whereNotNull('sj_hdr.posted');
                            // if ($is_exist == 1) {
                            //     $where->where('sj_hdr.posted', '>=', $act_date);
                            // }
                            // $where->whereMonth('sj_hdr.posted', date('m', strtotime($act_date)));
                            // $where->whereYear('sj_hdr.posted', date('Y', strtotime($act_date)));
                        })
                        ->where(function ($wh) use ($cust, $period, $item_i){
                            $wh->where('sj_hdr.custcode', $cust);
                            $wh->where('sj_dtl.itemcode', $item_i);
                            $wh->whereNull('inv_dtl.do_no');
                        })
                        ->select([
                            'sj_dtl.do_no',
                            'sj_hdr.period as sj_period',
                            'sj_hdr.taxrate as sj_tax_rate',
                            'sj_dtl.itemcode as sj_item_code',
                            'sj_dtl.price as sj_price',
                            'sj_dtl.quantity as sj_qty',
                            'sj_hdr.sub_amt as sj_sub_amount',
                            'sj_hdr.tot_disc as sj_tot_vat',
                            'sj_hdr.tot_amt as sj_total_amount',
                            'inv_dtl.do_no as inv_do'
                        ])
                        ->get();
                    if ($do_tch->isNotEmpty()) {
                        foreach ($do_tch as $s) {
                            $sub_amt = $d['price_new'] * $s->sj_qty;
                            $tot_vat = $sub_amt * $s->sj_tax_rate / 100;
                            $total_amount = $sub_amt + $tot_vat;
                            DB::table('tch_tbs.do_dtl as sj_dtl')
                                ->leftJoin('tch_tbs.do_hdr as sj_hdr', 'sj_hdr.do_no', '=', 'sj_dtl.do_no')
                                ->leftJoin('tch_tbs.inv_sj as inv_dtl', function ($join){
                                    $join->on('inv_dtl.do_no', '=', 'sj_dtl.do_no');
                                })
                                ->where(function ($where) use ($act_date, $is_exist){
                                    $where->whereNotNull('sj_hdr.written');
                                    $where->where('sj_hdr.written', '>=', $act_date);
                                    // $where->whereNotNull('sj_hdr.posted');
                                    // if ($is_exist == 1) {
                                    //     $where->where('sj_hdr.posted', '>=', $act_date);
                                    // }
                                    // $where->whereMonth('sj_hdr.posted', date('m', strtotime($act_date)));
                                    // $where->whereYear('sj_hdr.posted', date('Y', strtotime($act_date)));
                                })
                                ->where(function ($wh) use ($cust, $period, $item_i){
                                    $wh->where('sj_hdr.custcode', $cust);
                                    $wh->where('sj_dtl.itemcode', $item_i);
                                    $wh->whereNull('inv_dtl.do_no');
                                })
                                ->update([
                                    'sj_dtl.price' => $d['price_new'],
                                    'sj_hdr.sub_amt' => $sub_amt,
                                    'sj_hdr.tot_disc' => $tot_vat,
                                    'sj_hdr.tot_amt' => $total_amount
                                ]);
                        }
                    }

                    // $tbs_do = DB::table('db_tbs.entry_do_tbl as sj')
                    //     ->where('sj.cust_id', $cust)
                    //     ->where('sj.item_code', $d['item_code'])
                    //     ->where(function ($where) use ($act_date, $is_exist){
                    //         $where->whereNotNull('sj.created_date');
                    //         $where->where('sj.created_date', '>=', $act_date);
                    //     })
                    //     ->whereNull('sj.invoice_date')
                    //     ->select([
                    //         '*'
                    //     ])
                    // ->get();

                    // if ($tbs_do->isNotEmpty()) {
                    //     $cvrt = $act_date;
                    //     $custprice_id = $cust.'.'.$cvrt;
                    //     DB::table('db_tbs.entry_do_tbl as sj')
                    //         ->where('sj.cust_id', $cust)
                    //         ->where('sj.item_code', $d['item_code'])
                    //         ->where(function ($where) use ($act_date, $is_exist){
                    //             $where->whereNotNull('sj.created_date');
                    //             $where->where('sj.created_date', '>=', $act_date);
                    //         })
                    //         ->whereNull('sj.invoice_date')
                    //         ->update([
                    //             'custprice' => $custprice_id
                    //         ]);
                    // }
                }

                if ($d['is_stock'] == 1) {
                    DB::table('db_tbs.item')
                        ->where('ITEMCODE', $d['item_code'])
                        ->update([
                            'PRICE' => $d['price_new']
                        ]);
                    DB::table('tch_tbs.item')
                        ->where('itemcode', $d['item_code'])
                        ->update([
                            'price' => $d['price_new']
                        ]);
                }
            }
            
            DB::connection('db_tbs')->commit();
            DB::connection('tch_tbs')->commit();
            return true;
        } catch (Exception $e) {
            DB::connection('db_tbs')->rollBack();
            DB::connection('tch_tbs')->rollBack();
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

    private function _item_with_oldprice($cust)
    {
        $query = DB::table('db_tbs.item')
            ->leftJoin(DB::raw('
                (
                    SELECT s1.* FROM db_tbs.entry_custprice_tbl as s1
                    LEFT JOIN db_tbs.entry_custprice_tbl as s2 ON s1.item_code = s2.item_code AND s1.active_date < s2.active_date
                    WHERE s2.item_code IS NULL
                ) as custprice
            '), function($join){
                $join->on("custprice.item_code", "=", "item.ITEMCODE");
            })
            ->select([
                DB::raw('TRIM(item.ITEMCODE) as itemcode'),
                'item.PART_NO as part_no', 
                'item.DESCRIPT as descript', 
                'item.UNIT as unit', 
                'item.DESCRIPT1 as model',
                'custprice.active_date as active_date',
                DB::raw('IFNULL(custprice.price_new, 0) as price')
            ])
            ->where('item.CUSTCODE', $cust)
            ->whereRaw('item.ITEMCODE like ?', ['1%'])
            // ->where('custprice.status', 'ACTIVE')
            ->get();
        return $query;
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
