<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ClaimEntryTrait;
use App\Models\Dbtbs\ClaimEntry;
use App\Models\Dbtbs\ClaimEntryRG;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Redirect;

class ClaimEntryController extends Controller
{
    use ClaimEntryTrait;

    public function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.warehouse.claim-entry.index');
    }

    public function claimEntry(Request $request)
    {
        if (isset($request->cl_no)) {
            if (isset($request->cek)) {
                return response()->json([
                    'status' => true,
                    'content' => null,
                    'message' => $this->claimEntryCheck($request)
                ], 200);
            }else{
                $cek = ClaimEntry::where('cl_no', $request->cl_no)->get();
                if (!$cek->isEmpty()) {
                    return response()->json([
                        'status' => true,
                        'content' => $cek,
                        'message' => 'Data tersedia!'
                    ], 201);
                }else{
                    return response()->json([
                        'status' => false,
                        'content' => null,
                        'message' => 'Data tidak ditemukan!'
                    ], 200);
                }
            }
        }else{
            $query = ClaimEntry::query()->groupBy('cl_no')->get();
            return DataTables::of($query)
                ->editColumn('written', function($query) {
                    return date('d/m/Y', strtotime($query->written));
                })
                ->editColumn('date_do', function($query) {
                    return ($query->date_do == NULL) ? '/ /' : date('d/m/Y', strtotime($query->date_do));
                })
                ->editColumn('date_rg', function($query) {
                    return ($query->date_rg == NULL) ? '/ /' : date('d/m/Y', strtotime($query->date_rg));
                })
                ->editColumn('rr_no', function($query) {
                    return ($query->rr_no == NULL) ? '/ /' : date('d/m/Y', strtotime($query->rr_no));
                })
                ->addColumn('action', function($query){
                    return view('tms.warehouse.claim-entry.button.btnTableIndex', [
                        'data' => $query,
                    ]);
                })->rawColumns(['action'])
                ->make(true);
        }
    }

    public function claimEntryCreate(Request $request)
    {
        $cd = explode('/', $request->date);
        $date = $cd[2].'-'.$cd[1].'-'.$cd[0];
        $data = [];
        $items = $request->items;
        $tot_qty = 0;
        $num = 0;

        $periodYear = Carbon::createFromFormat('Y-m',  $request->priod)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $request->priod)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isEmpty()) {
            return _Error('Sudah closing tidak bisa entry');
        }
        
        if (!empty($request->items)) {
            for ($i=0; $i < count($items); $i++) { $tot_qty += $items[$i][5]; }
            for ($i=0; $i < count($items); $i++) {
                $tblItem =
                    DB::connection('db_tbs')
                        ->table('item')
                        ->selectRaw('ITEMCODE, PART_NO, DESCRIPT, UNIT, PRICE, COST, FAC_UNIT, FACTOR, WAREHOUSE, GROUPS, TYPES')
                        ->where('ITEMCODE', $items[$i][1])
                        ->first();
                $num++;

                $data[] = [
                    'cl_no' => $request->cl_no,
                    'num' => addZeroFirst($num),
                    'ref_no' => $request->refno,
                    'po_no' => $request->pono,
                    'rr_no' => $request->rrno,
                    'period' => $request->priod,
                    'written' => $date,
                    'cust_code' => $request->customercode,
                    'do_addr' => $request->customerdoaddr,
                    'company' => $request->customername,
                    'addr1' => $request->customeraddr1,
                    'addr2' => $request->customeraddr2,
                    'addr3' => $request->customeraddr3,
                    'addr4' => $request->customeraddr4,
                    'remark' => $request->remark,
                    'branch' => $request->branch,
                    'warehouse' => $request->warehouse,
                    'operator' => $request->user,
                    'itemcode' => $tblItem->ITEMCODE,
                    'part_no' => $tblItem->PART_NO,
                    'descript' => $tblItem->DESCRIPT,
                    'fac_unit' => $tblItem->FAC_UNIT,
                    'factor' => $tblItem->FACTOR,
                    'unit_item' => $tblItem->UNIT,
                    'warehouse_item' => $tblItem->WAREHOUSE,
                    'groups' => $tblItem->GROUPS,
                    'types' => $tblItem->TYPES,
                    'price' => $tblItem->PRICE,
                    'cost' => $tblItem->COST,
                    'tmp_qty' => 0,
                    'qty' => $items[$i][5],
                    'tot_qty' => $tot_qty,
                    'qty_rg' => 0,
                    'notes' => $items[$i][7],
                    'creation_by' => $request->user,
                    'creation_date' => date('Y-m-d'),
                    'cl_date' => $date,
                    'cl_time' => date('H:i:s')
                ];
            }
        }
        return response()->json([
            'status' => true,
            'content' => $data,
            'message' => 'Claim berhasil di input!'
        ], 201);
        $query = ClaimEntry::insert($data);
        $log = $this->createLOG($request->cl_no, 'ADD');
        if ($query) {
            return response()->json([
                'status' => true,
                'content' => null,
                'message' => 'Claim berhasil di input!'
            ], 201);   
        }else{
            return response()->json([
                'status' => true,
                'content' => null,
                'message' => 'Claim gagal di input, periksa kembali form Anda!'
            ], 401);
        }
    }

    public function claimEntryUpdate(Request $request)
    {
        $cd = explode('/', $request->date);
        $date = $cd[2].'-'.$cd[1].'-'.$cd[0];
        $data = [];
        $items = $request->items;
        $tot_qty = 0;
        $num = 0;

        $periodYear = Carbon::createFromFormat('Y-m',  $request->priod)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $request->priod)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isEmpty()) {
            return _Error('Sudah closing tidak bisa update');
        }

        if (!empty($request->items)) {
            $old = ClaimEntry::where('cl_no', $request->cl_no)->first();
            $creation_by = $old->operator;
            $creation_date = $old->written;
            $cl_date = $old->cl_date;
            $cl_time = $old->cl_time;
            $delete_first = ClaimEntry::where('cl_no', $request->cl_no)->delete();
            for ($i=0; $i < count($items); $i++) { $tot_qty += $items[$i][5]; }
            for ($i=0; $i < count($items); $i++) {
                $tblItem =
                    DB::connection('db_tbs')
                        ->table('item')
                        ->selectRaw('ITEMCODE, PART_NO, DESCRIPT, UNIT, PRICE, COST, FAC_UNIT, FACTOR, WAREHOUSE, GROUPS, TYPES')
                        ->where('ITEMCODE', $items[$i][1])
                        ->first();
                $num++;

                $data[] = [
                    'cl_no' => $request->cl_no,
                    'num' => addZeroFirst($num),
                    'ref_no' => $request->refno,
                    'po_no' => $request->pono,
                    'rr_no' => $request->rrno,
                    'period' => $request->priod,
                    'written' => $date,
                    'cust_code' => $request->customercode,
                    'do_addr' => $request->customerdoaddr,
                    'company' => $request->customername,
                    'addr1' => $request->customeraddr1,
                    'addr2' => $request->customeraddr2,
                    'addr3' => $request->customeraddr3,
                    'addr4' => $request->customeraddr4,
                    'remark' => $request->remark,
                    'branch' => $request->branch,
                    'warehouse' => $request->warehouse,
                    'operator' => $request->user,
                    'itemcode' => $tblItem->ITEMCODE,
                    'part_no' => $tblItem->PART_NO,
                    'descript' => $tblItem->DESCRIPT,
                    'fac_unit' => $tblItem->FAC_UNIT,
                    'factor' => $tblItem->FACTOR,
                    'unit_item' => $tblItem->UNIT,
                    'warehouse_item' => $tblItem->WAREHOUSE,
                    'groups' => $tblItem->GROUPS,
                    'types' => $tblItem->TYPES,
                    'price' => $tblItem->PRICE,
                    'cost' => $tblItem->COST,
                    'tmp_qty' => 0,
                    'qty' => $items[$i][5],
                    'tot_qty' => $tot_qty,
                    'qty_rg' => 0,
                    'notes' => $items[$i][7],
                    'cl_date' => $cl_date,
                    'cl_time' =>$cl_time,

                    'creation_by' => $creation_by,
                    'creation_date' => $creation_date,
                    'update_by' => $request->user,
                    'update_date' => date('Y-m-d')
                ];
            }
        }
        $query = ClaimEntry::insert($data);
        $log = $this->createLOG($request->cl_no, 'EDIT');
        if ($query) {
            return response()->json([
                'status' => true,
                'content' => null,
                'message' => 'Claim berhasil di update!'
            ], 201);   
        }else{
            return response()->json([
                'status' => true,
                'content' => null,
                'message' => 'Claim gagal di update, periksa kembali form Anda!'
            ], 401);
        }
    }

    public function claimEntryRG(Request $request)
    {
        $cd = explode('/', $request->date);
        $date = $cd[2].'-'.$cd[1].'-'.$cd[0];
        $data = [];
        $items = $request->items;
        if (!empty($items)) {
            for ($i=0; $i < count($items); $i++) {
                if ($items[$i][6] != 0) {
                    $data[] = [
                        'cl_no' => $request->cl_no,
                        'doc_no' => $request->doc_no,
                        'creation_at' => $date,
                        'creation_by' => Auth::user()->FullName,
                        'itemcode' => $items[$i][1],
                        'descript' => $items[$i][2],
                        'unit' => $items[$i][3],
                        'qty_rg' => $items[$i][6]
                    ];
                    $cl_tbl = ClaimEntry::where([
                        'cl_no' => $request->cl_no,
                        'itemcode' => $items[$i][1]
                    ])->first();
                    $update_rg = ClaimEntry::where([
                        'cl_no' => $request->cl_no,
                        'itemcode' => $items[$i][1]
                    ])->update([
                        'qty_rg' => (int)$cl_tbl->qty_rg + (int)$items[$i][6]
                    ]);
                }
            }
            $update_rg_date = ClaimEntry::where([
                'cl_no' => $request->cl_no,
            ])->update([
                'date_rg' => $date
            ]);
            $sum = DB::connection('db_tbs')
                ->table('entry_cl_tbl')
                ->selectRaw('SUM(qty) as tot_qty, SUM(qty_rg) as tot_qty_rg')
                ->where('cl_no', $request->cl_no)
                ->first();
            if ($sum->tot_qty == $sum->tot_qty_rg) {
                $update_rg = ClaimEntry::where([
                    'cl_no' => $request->cl_no,
                ])->update([
                    'closed' => date('Y-m-d')
                ]);
            }
            $query = DB::connection('db_tbs')
                ->table('entry_cl_rg')
                ->insert($data);
            if ($query) {
                return response()->json([
                    'status' => true,
                    'content' => null,
                    'message' => 'Qty RG Updated!'
                ], 201);   
            }
        }
    }
    public function claimEntryUnRG(Request $request)
    {
        $cek = ClaimEntry::where([
            'cl_no' => $request->cl_no,
            'closed' => null
        ])->first();
        if (!isset($cek)) {
            return response()->json([
                'status' => false,
                'content' => null,
                'message' => 'Claim has been closed, please contact your manager!'
            ], 404);
        }

        $items = $request->items;
        if (!empty($items)) {
            for ($i=0; $i < count($items); $i++) { 
                $where = [
                    'cl_no' => $items[$i]['cl_no'],
                    'itemcode' => $items[$i]['itemcode']
                ];
                $claim = ClaimEntry::where($where)
                    ->first();
                $rg = ClaimEntryRG::where('id', $items[$i]['id'])
                    ->first();
                
                $claim->qty_rg = (int)$claim->qty_rg - (int)$rg->qty_rg;
                $claim->save();
                $rg->delete();
            }
            $sum = DB::connection('db_tbs')
                ->table('entry_cl_tbl')
                ->selectRaw('SUM(qty) as tot_qty, SUM(qty_rg) as tot_qty_rg')
                ->where('cl_no', $request->cl_no)
                ->first();
            if ($sum->tot_qty_rg == 0) {
                $update_rg = ClaimEntry::where('cl_no', $request->cl_no)->update([
                    'date_rg' => null
                ]);
            }
            return response()->json([
                'status' => true,
                'content' => null,
                'message' => 'Claim Entry updated!'
            ], 200);
        }
    }

    public function claimEntryRGRead(Request $request)
    {
        $query = DB::connection('db_tbs')
            ->table('entry_cl_rg')
            ->where('cl_no', $request->cl_no)
            ->get();
        return DataTables::of($query)
            ->editColumn('creation_at', function($query) {
                return ($query->creation_at == NULL) ? '/ /' : date('d/m/Y', strtotime($query->creation_at));
            })
            ->make(true);
    }

    public function claimEntryUnClose(Request $request)
    {
        $claim = ClaimEntry::where('cl_no', $request->cl_no)->first();
        $periodYear = Carbon::createFromFormat('Y-m',  $claim->period)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $claim->period)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isEmpty()) {
            return _Error('Sudah closing tidak bisa unclose');
        }
        if (isset($request->cl_no)) {
            $claim = ClaimEntry::where('cl_no', $request->cl_no)->update([
                'closed' => null
            ]);
            $log = $this->createLOG($request->cl_no, 'UNCLOSE', $request->note);
            return response()->json([
                'status' => true,
                'content' => null,
                'message' => 'Claim has been unclose!'
            ], 200);
        }
    }

    public function claimEntryReport(Request $request)
    {
        if (isset($request->print) && $request->print != "") {
            $cl_no = base64_decode($request->print);
            $data = ClaimEntry::where('cl_no', $cl_no)->get();
            if ($data->isEmpty()) {
                $request->session()->flash('message', 'Data tidak ditemukan!');
                return Redirect::back();
            }
            $log = $this->createLOG($cl_no, 'PRINT', $request->note);
            $pdf = PDF::loadView('tms.warehouse.claim-entry.report.report', compact('data'))->setPaper('a4', 'potrait');
            return $pdf->stream();
        }else{
            $request->session()->flash('message', 'Data tidak ditemukan!');
            return Redirect::back();
        }
    }

    public function claimEntryHeader(Request $request)
    {
        switch ($request->type) {
            case "CLNo":
                return $this->headerToolsEntryNo($request);
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
            case "cek_rg":
                return response()->json([
                    'status' => true,
                    'content' => $this->claimEntryCheckRG($request),
                    'message' => 'Claim has been received!'
                ], 200);
                break;
            default:
                return response()->json([
                    'status' => true,
                    'content' => null,
                ], 200);
        }
    }

    public function claimEntryVoid(Request $request)
    {
        $claim = ClaimEntry::where('cl_no', $request->cl_no)->first();
        $periodYear = Carbon::createFromFormat('Y-m',  $claim->period)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $claim->period)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isEmpty()) {
            return _Error('Sudah closing tidak bisa void');
        }

        if (isset($request->cl_no)) {
            $voided = ClaimEntry::where('cl_no', $request->cl_no)
                ->whereNull('voided')
                ->get();
            if (!$voided->isEmpty()) {
                // data bisa divoid
                $query = ClaimEntry::where('cl_no', $request->cl_no)
                    ->update([
                        'voided' => date('Y-m-d'),
                        'closed' => date('Y-m-d')
                    ]);
                $log = $this->createLOG($request->cl_no, 'VOID');
                return response()->json([
                    'status' => true,
                    'content' => null,
                    'message' => 'Claim has been voided!'
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'content' => null,
                    'message' => 'Perintah ditolak! Coba beberapa saat lagi.'
                ], 401);
            }
        }
    }

    public function claimEntryUnVoid(Request $request)
    {
        $claim = ClaimEntry::where('cl_no', $request->cl_no)->first();
        $periodYear = Carbon::createFromFormat('Y-m',  $claim->period)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $claim->period)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isEmpty()) {
            return _Error('Sudah closing tidak bisa unvoid');
        }
        if (isset($request->cl_no)) {
            $voided = ClaimEntry::where('cl_no', $request->cl_no)
                ->whereNull('voided')
                ->get();
            if ($voided->isEmpty()) {
                $query = ClaimEntry::where('cl_no', $request->cl_no)
                    ->update([
                        'voided' => null,
                        'closed' => null
                    ]);
                $log = $this->createLOG($request->cl_no, 'UNVOID', $request->note);
                return response()->json([
                    'status' => true,
                    'content' => null,
                    'message' => 'Claim has been unvoided!'
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'content' => null,
                    'message' => 'Perintah ditolak! Coba beberapa saat lagi.'
                ], 401);
            }
        }
    }

    public function claimEntryDO(Request $request)
    {
        if (isset($request->cl_no)) {
            $cd = explode('/', $request->date_sj);
            $date = $cd[2].'-'.$cd[1].'-'.$cd[0];
            $date_do = ClaimEntry::where('cl_no', $request->cl_no)
                ->whereNull('date_do')
                ->get();
            if (!$date_do->isEmpty()) {
                $query = ClaimEntry::where('cl_no', $request->cl_no)
                    ->update([
                        'date_do' => $date
                    ]);
                $log = $this->createLOG($request->cl_no, 'DELIVER');
                return response()->json([
                    'status' => true,
                    'content' => null,
                    'message' => 'Claim has been delivered!'
                ], 200);
            }
        }
    }

    public function claimEntryUnDO(Request $request)
    {
        if (isset($request->cl_no)) {
            $date_do = ClaimEntry::where('cl_no', $request->cl_no)
                ->whereNull('date_do')
                ->get();
            if ($date_do->isEmpty()) {
                $query = ClaimEntry::where('cl_no', $request->cl_no)
                    ->update([
                        'date_do' => null
                    ]);
                $log = $this->createLOG($request->cl_no, 'UNDELIVER', $request->note);
                return response()->json([
                    'status' => true,
                    'content' => null,
                    'message' => 'Claim has been undelivered!'
                ], 200);
            }
        }
    }
    
}
