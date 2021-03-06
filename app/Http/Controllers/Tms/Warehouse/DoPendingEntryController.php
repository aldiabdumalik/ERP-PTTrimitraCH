<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\DoPendingEntryTrait;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DoEntry;
use App\Models\Dbtbs\DoPendingEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Support\Facades\DB;

class DoPendingEntryController extends Controller
{
    use ToolsTrait, DoPendingEntryTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.warehouse.do-pending-entry.index');
    }

    public function index_table(Request $request)
    {
        $query = DoPendingEntry::query()->groupBy('do_no')->get();
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
                return view('tms.warehouse.do-pending-entry.button.btnTableIndex', [
                    'data' => $query,
                ]);
            })->rawColumns(['action'])
            ->make(true);
    }

    public function detail($do_no, $is_check=0)
    {
        if ($is_check == 1) {
            $query = DoPendingEntry::where('do_no', $do_no)->first();
            if ($query) {
                $content = 'exist';
            }else{
                $content = 'not_exist';
            }
        }else{
            $content = DB::table('db_tbs.entry_do_pending_tbl as do_temp')
            ->leftJoin('ekanban.ekanban_customermaster as tb_cust', 'tb_cust.CustomerCode_eKanban', '=', 'do_temp.cust_id')
            ->leftJoin('db_tbs.sys_do_address as tb_doaddr', function ($join)
            {
                $join->on('tb_doaddr.id_do', '=', 'do_temp.do_address');
                $join->on('tb_doaddr.cust_code', '=', 'do_temp.cust_id');
            })
            ->leftJoin('db_tbs.item as tb_item', function ($join){
                $join->on('do_temp.cust_id', '=', 'tb_item.CUSTCODE');
                $join->on('do_temp.item_code', '=', 'tb_item.ITEMCODE');
            })
            ->where('do_temp.do_no', $do_no)
            ->select([
                'do_temp.*',
                'tb_cust.CustomerCode_eKanban as custcode', 
                'tb_cust.CustomerName as custname', 
                'tb_cust.Cus_Group as custgroup',
                'tb_item.ITEMCODE as itemcode', 
                'tb_item.PART_NO as part_no', 
                'tb_item.DESCRIPT as descript', 
                'tb_item.UNIT as unit', 
                'tb_item.DESCRIPT1 as model',
                'tb_doaddr.do_addr1', 
                'tb_doaddr.do_addr2', 
                'tb_doaddr.do_addr3', 
                'tb_doaddr.do_addr4'
            ])
            ->get();
        }
        return _Success(null, 200, $content);
    }

    public function edit($do_no)
    {
        $query = DoPendingEntry::where('do_no', $do_no)->first();
        if (!is_null($query->voided_date)) {
            return _Error('DO Temp Entry has been voided');
        }elseif(!is_null($query->posted_date)){
            return _Error('DO Temp Entry has been posted');
        }elseif($query->period < date('Y-m')){
            return _Error('DO Temp Entry has been closed');
        }
        return _Success(true, 200, $query);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $data = [];
            $periodYear = Carbon::createFromFormat('Y-m',  $request->priod)->format('Y');
            $periodMonth = Carbon::createFromFormat('Y-m',  $request->priod)->format('m');
            $check =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $periodYear)
                ->whereMonth('DATE','=', $periodMonth)
                ->get();
            if ($check->isNotEmpty()) {
                return _Error('Sudah closing tidak bisa entry');
            }
            $item = json_decode($request->items, true);
            for ($i=0; $i < count($item); $i++) {
                $data[] = [
                    'do_no' => $request->no,
                    'item_code' => $item[$i]['itemcode'],
                    'quantity' => str_replace(',', '', $item[$i]['qty']),
                    'unit' => $item[$i]['unit'],
                    'so_no' => $request->so,
                    'sso_no' => $request->sso,
                    'ref_no' => $request->refno,
                    'po_no' => $request->pono,
                    'dn_no' => $request->dnno,
                    'period' => $request->priod,
                    'cust_id' => $request->cust_id,
                    'do_address' => $request->doaddr,
                    'cust_name' => $request->cust_name,
                    'id_driver' => Auth::user()->FullName,
                    'remark' => $request->remark,
                    'branch' => $request->branch,
                    'warehouse' => $request->warehouse,
                    'delivery_date' => $request->date,
                    'created_by' => Auth::user()->FullName,
                    'created_date' => Carbon::now(),
                    'sj_type' => $request->direct
                ];
            }
            DB::connection('db_tbs')->beginTransaction();
            try {
                $query = DoPendingEntry::insert($data);
                if ($query) {
                    $this->createGlobalLog('db_tbs.entry_do_pending_tbl_log', [
                        'do_no' => $request->no,
                        'status' => 'ADD',
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::user()->FullName
                    ]);
                }
                DB::connection('db_tbs')->commit();
                return _Success('Saved successfully', 201);
            } catch (Exception $e) {
                DB::connection('db_tbs')->rollBack();
                return _Error($e->getMessage());
            }
        }
    }

    public function update($do_no, Request $request)
    {
        if ($request->ajax()) {
            $periodYear = Carbon::createFromFormat('Y-m',  $request->priod)->format('Y');
            $periodMonth = Carbon::createFromFormat('Y-m',  $request->priod)->format('m');
            $check =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $periodYear)
                ->whereMonth('DATE','=', $periodMonth)
                ->get();
            if ($check->isNotEmpty()) {
                return _Error('Sudah closing tidak bisa update');
            }
            DB::connection('db_tbs')->beginTransaction();
            $data = [];
            $item = json_decode($request->items, true);

            $old_data = DoPendingEntry::where('do_no', $do_no)->first();
            $create_by = $old_data->created_by;
            $create_date = $old_data->created_date;

            $old_data = DoPendingEntry::where('do_no', $do_no)->delete();

            for ($i=0; $i < count($item); $i++) {
                $data[] = [
                    'do_no' => $do_no,
                    'item_code' => $item[$i]['itemcode'],
                    'quantity' => str_replace(',', '', $item[$i]['qty']),
                    'unit' => $item[$i]['unit'],
                    'so_no' => $request->so,
                    'sso_no' => $request->sso,
                    'ref_no' => $request->refno,
                    'po_no' => $request->pono,
                    'dn_no' => $request->dnno,
                    'period' => $request->priod,
                    'cust_id' => $request->cust_id,
                    'do_address' => $request->doaddr,
                    'cust_name' => $request->cust_name,
                    'id_driver' => Auth::user()->FullName,
                    'remark' => $request->remark,
                    'branch' => $request->branch,
                    'warehouse' => $request->warehouse,
                    'delivery_date' => $request->date,
                    'sj_type' => $request->direct,
                    'created_by' => $create_by,
                    'created_date' => $create_date,
                    'update_by' => Auth::user()->FullName,
                    'update_date' => Carbon::now(),
                ];
            }
            try {
                $query = DoPendingEntry::insert($data);
                if ($query) {
                    $this->createGlobalLog('db_tbs.entry_do_pending_tbl_log', [
                        'do_no' => $do_no,
                        'status' => 'EDIT',
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::user()->FullName
                    ]);
                }
                DB::connection('db_tbs')->commit();
                return _Success('Saved successfully', 201);
            } catch (Exception $e) {
                DB::connection('db_tbs')->rollBack();
                return _Error($e->getMessage());
            }
        }
    }

    public function posted($do_no, Request $request)
    {
        if ($request->ajax()) {
            $cek = DoPendingEntry::where('do_no', $do_no)->first();
            $periodYear = Carbon::createFromFormat('Y-m',  $cek->period)->format('Y');
            $periodMonth = Carbon::createFromFormat('Y-m',  $cek->period)->format('m');
            $check =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $periodYear)
                ->whereMonth('DATE','=', $periodMonth)
                ->get();
            if ($check->isNotEmpty()) {
                return _Error('Sudah closing tidak bisa post');
            }

            if (!is_null($cek->voided_date)) {
                return _Error('DO Temp has been voided');
            }elseif($cek->period < date('Y-m')){
                return _Error('DO Temp has been closed');
            };

            DB::connection('db_tbs')->beginTransaction();
            try {
                $update = DoPendingEntry::where('do_no', $do_no)->update([
                    'posted_date' => Carbon::now(),
                    'posted_by' => Auth::user()->FullName,
                    'rr_no' => $request->rr_no,
                    'rr_date' => $request->rr_date,
                    'scurity_stamp' => $request->st
                ]);
                if ($update) {
                    $this->createGlobalLog('db_tbs.entry_do_pending_tbl_log', [
                        'do_no' => $do_no,
                        'status' => 'POSTED',
                        'note' => $request->note,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::user()->FullName
                    ]);
                }
                DB::connection('db_tbs')->commit();
                return _Success('DO Temp posted successfully!');
            } catch (Exception $e) {
                DB::connection('db_tbs')->rollBack();
                return _Error($e->getMessage());
            }
        }
    }

    public function unposted($do_no, Request $request)
    {
        if ($request->ajax()) {
            $cek = DoPendingEntry::where('do_no', $do_no)->first();
            $periodYear = Carbon::createFromFormat('Y-m',  $cek->period)->format('Y');
            $periodMonth = Carbon::createFromFormat('Y-m',  $cek->period)->format('m');
            $check =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $periodYear)
                ->whereMonth('DATE','=', $periodMonth)
                ->get();
            if ($check->isNotEmpty()) {
                return _Error('Sudah closing tidak bisa unpost');
            }
            if($cek->period < date('Y-m')){
                return _Error('DO Temp has been finished');
            };

            DB::connection('db_tbs')->beginTransaction();
            try {
                $update = DoPendingEntry::where('do_no', $do_no)->update([
                    'posted_date' => null,
                    'posted_by' => null,
                    'rr_no' => null,
                    'rr_date' => null,
                    'scurity_stamp' => null
                ]);
                if ($update) {
                    $this->createGlobalLog('db_tbs.entry_do_pending_tbl_log', [
                        'do_no' => $do_no,
                        'status' => 'UNPOSTED',
                        'note' => $request->note,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::user()->FullName
                    ]);
                }
                DB::connection('db_tbs')->commit();
                return _Success('DO Temp unposted successfully!');
            } catch (Exception $e) {
                DB::connection('db_tbs')->rollBack();
                return _Error($e->getMessage());
            }
        }
    }

    public function voided($do_no, Request $request)
    {
        if ($request->ajax()) {
            $cek = DoPendingEntry::where('do_no', $do_no)->first();
            $periodYear = Carbon::createFromFormat('Y-m',  $cek->period)->format('Y');
            $periodMonth = Carbon::createFromFormat('Y-m',  $cek->period)->format('m');
            $check =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $periodYear)
                ->whereMonth('DATE','=', $periodMonth)
                ->get();
            if ($check->isNotEmpty()) {
                return _Error('Sudah closing tidak bisa void');
            }
            if (!is_null($cek->posted_date)) {
                return _Error('DO Temp has been posted');
            }elseif($cek->period < date('Y-m')){
                return _Error('DO Temp has been closed');
            };

            DB::connection('db_tbs')->beginTransaction();
            try {
                $update = DoPendingEntry::where('do_no', $do_no)->update([
                    'voided_date' => Carbon::now(),
                    'voided_by' => Auth::user()->FullName
                ]);
                if ($update) {
                    $this->createGlobalLog('db_tbs.entry_do_pending_tbl_log', [
                        'do_no' => $do_no,
                        'status' => 'VOIDED',
                        'note' => $request->note,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::user()->FullName
                    ]);
                }
                DB::connection('db_tbs')->commit();
                return _Success('DO Temp voided successfully!');
            } catch (Exception $e) {
                DB::connection('db_tbs')->rollBack();
                return _Error($e->getMessage());
            }
        }
    }

    public function unvoided($do_no, Request $request)
    {
        if ($request->ajax()) {
            $cek = DoPendingEntry::where('do_no', $do_no)->first();
            $periodYear = Carbon::createFromFormat('Y-m',  $cek->period)->format('Y');
            $periodMonth = Carbon::createFromFormat('Y-m',  $cek->period)->format('m');
            $check =  DB::connection('db_tbs')
                ->table('stclose')
                ->whereYear('DATE','=',  $periodYear)
                ->whereMonth('DATE','=', $periodMonth)
                ->get();
            if ($check->isNotEmpty()) {
                return _Error('Sudah closing tidak bisa unvoid');
            }
            if($cek->period < date('Y-m')){
                return _Error('DO Temp has been closed');
            }
            if (!is_null($cek->revised_date)) {
                return $this->_Error("DO No. $do_no failed to unvoid, because it has been revised to DO No. $cek->revised_to!");
            }

            DB::connection('db_tbs')->beginTransaction();
            try {
                $update = DoPendingEntry::where('do_no', $do_no)->update([
                    'voided_date' => null,
                    'voided_by' => null
                ]);
                if ($update) {
                    $this->createGlobalLog('db_tbs.entry_do_pending_tbl_log', [
                        'do_no' => $do_no,
                        'status' => 'UNVOIDED',
                        'note' => $request->note,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::user()->FullName
                    ]);
                }
                DB::connection('db_tbs')->commit();
                return _Success('DO Temp unvoid successfully!');
            } catch (Exception $e) {
                DB::connection('db_tbs')->rollBack();
                return _Error($e->getMessage());
            }
        }
    }

    public function revise($do_no, Request $request)
    {
        $cek = DoPendingEntry::where('do_no', $do_no)->first();
        if($cek->period < date('Y-m')){
            return _Error('DO Temp has been closed');
        }
        if (is_null($cek->posted_date)) {
            return $this->_Error('Can\'t revised. it has not been posted');
        }
        $new_do_no = $this->headerToolsDoPendingEntryNo($request);
        DB::connection('db_tbs')->beginTransaction();
        $data = [];
        try {
            $old = DoPendingEntry::where('do_no', $do_no)->get();
            foreach ($old as $do) {
                $data[] = [
                    'do_no' => $new_do_no,
                    'item_code' => $do->item_code,
                    'quantity' => $do->quantity,
                    'unit' => $do->unit,
                    'so_no' => $do->so_no,
                    'sso_no' => $do->sso_no,
                    'ref_no' => $do->ref_no,
                    'po_no' => $do->po_no,
                    'dn_no' => $do->dn_no,
                    'period' => $request->period,
                    'cust_id' => $do->cust_id,
                    'do_address' => $do->do_address,
                    'cust_name' => $do->cust_name,
                    'id_driver' => $do->id_driver,
                    'remark' => $do->remark,
                    'branch' => $do->branch,
                    'warehouse' => $do->warehouse,
                    'delivery_date' => $request->date,
                    'created_by' => $do->created_by,
                    'created_date' => $do->created_date,
                    'sj_type' => $do->sj_type
                ];
            }
            $insert = DoPendingEntry::insert($data);
            if ($insert) {
                $update = DoPendingEntry::where('do_no', $do_no)->update([
                    'voided_date' => date('Y-m-d H:i:s'),
                    'voided_by' => Auth::user()->FullName,
                    'finished_date' => null,
                    'finished_by' => null,
                    'posted_date' => null,
                    'posted_by' => null,
                    'revised_date' => date('Y-m-d H:i:s'),
                    'revised_by' => Auth::user()->FullName,
                    'revised_to' => $new_do_no,
                    'rr_no' => null,
                    'rr_date' => null,
                    'scurity_stamp' => null
                ]);
                if ($update) {
                    $this->createGlobalLog('db_tbs.entry_do_pending_tbl_log', [
                        [
                            'do_no' => $do_no,
                            'status' => 'VOIDED',
                            'note' => "Revisi DO NO $do_no to $new_do_no",
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::user()->FullName
                        ],[
                            'do_no' => $new_do_no,
                            'status' => 'ADD',
                            'note' => "Revisi DO NO $do_no to $new_do_no",
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::user()->FullName
                        ],
                    ]);
                }
            }
            DB::connection('db_tbs')->commit();
            return _Success('DO Temp successfully revised!');
        } catch (Exception $e) {
            DB::connection('db_tbs')->rollBack();
            return _Error($e->getMessage());
        }
    }

    public function ng_entry($do_no, Request $request)
    {
        $cek = DoPendingEntry::where('do_no', $do_no)->first();
        if($cek->period < date('Y-m')){
            return _Error('DO Temp has been closed');
        }elseif (!is_null($cek->posted_date)) {
            return $this->_Error('Can\'t create NG. DO Temp has been posted');
        }elseif(!is_null($cek->voided_date)){
            return $this->_Error('Can\'t create NG. DO Temp has been voided');
        }
        DB::connection('db_tbs')->beginTransaction();
        $item = json_decode($request->item, true);
        try {
            $ng = [];
            for ($i=0; $i < count($item); $i++) { 
                if ((floatval($item[$i]['qty_sj']) - floatval($item[$i]['qty_ng'])) === 0) {
                    DoPendingEntry::where('do_no', $do_no)
                        ->where('item_code', $item[$i]['itemcode'])
                        ->delete();
                }else{
                    DoPendingEntry::where('do_no', $do_no)
                        ->where('item_code', $item[$i]['itemcode'])
                        ->update([
                            'quantity' => floatval($item[$i]['qty_sj']) - floatval($item[$i]['qty_ng'])
                        ]);
                }
                $ng[] = [
                    'do_no' => $do_no,
                    'itemcode' => $item[$i]['itemcode'],
                    'qty_ng' => $item[$i]['qty_ng'],
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::user()->FullName
                ];
            }

            DB::table('db_tbs.entry_do_pending_tbl_ng')->insert($ng);
            DB::connection('db_tbs')->commit();
            return _Success('Successfuly create Qty NG', 200, );
        } catch (Exception $e) {
            DB::connection('db_tbs')->rollBack();
            return _Error($e->getMessage());
        }
    }

    public function print($enc, Request $request)
    {
        $decode = base64_decode($enc);
        $arr = explode('&', $decode);

        $data = (object) [];
        $data->dari = $arr[0];
        $data->sampai = $arr[1];
        $data->type = $arr[2];

        if ($data->sampai <= $data->dari && $data->sampai !== $data->dari) {
            $request->session()->flash('message', 'Invalid data input!');
            return Redirect::back();
        }

        $result = $this->dataForPrint($data);

        if ($result->isEmpty()) {
            $request->session()->flash('message', 'Data tidak ditemukan!');
            return Redirect::back();
        }

        $groupItem = $result->groupBy('do_no');
        $getKey = [];
        $getValue = [];
        foreach ($groupItem as $key => $v) {
            $getKey[] = $key;
            $getValue[] = $v;
        }

        $template = 'tms.warehouse.do-pending-entry.report.report';
        if ($data->type == 'blank') {
            $template = 'tms.warehouse.do-pending-entry.report.report';
        }else{
            $template = 'tms.warehouse.do-pending-entry.report.reportTemplate';
        }
        $pdf = PDF::loadView($template, compact('data', 'groupItem', 'getKey'))->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public function header_tools(Request $request)
    {
        switch ($request->type) {
            case "DONo":
                return $this->headerToolsDoPendingEntryNo($request);
                // return $this->headerToolsDoEntryNoTbs();
                break;
            case "branch":
                return DataTables::of($this->headerToolsBranch($request))->make(true);
                break;
            case "warehouse":
                return DataTables::of($this->headerToolsWarehouse($request))->make(true);
                // return _Success(null, 200, $this->headerToolsWarehouse($request));
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
            case 'item_select':
                $query = 
                    DB::table('db_tbs.item')
                    ->selectRaw('ITEMCODE as itemcode, PART_NO as part_no, DESCRIPT as descript, UNIT as unit, DESCRIPT1 as model')
                    ->whereIn('ITEMCODE', json_decode($request->item_selected))
                    ->get();
                return _Success(null, 200, $query);
                break;
            case "validation":
                $query = DoPendingEntry::where('do_no', $request->do_no)->first();
                if ($request->cek == 'posted') {
                    if (!is_null($query->voided_date)) {
                        return _Error('DO Temp has been voided');
                    }elseif($query->period < date('Y-m')){
                        return _Error('DO Temp has been closed');
                    };
                }elseif($request->cek == 'voided'){
                    if (!is_null($query->posted_date)) {
                        return _Error('DO Temp has been posted');
                    }elseif($query->period < date('Y-m')){
                        return _Error('DO Temp has been closed');
                    }elseif (!is_null($query->revised_date)) {
                        return $this->_Error("DO No. $request->do_no has been revised to DO No. $query->revised_to!");
                    }
                }
                return _Success(true);
                break;
            case "log":
                $query = $this->headerToolsLog($request);
                return DataTables::of($query)
                    ->addColumn('created_date', function($query){
                        return date('d/m/Y', strtotime($query->created_at));
                    })->rawColumns(['created_date'])
                    ->addColumn('created_time', function($query){
                        return date('H:i:s', strtotime($query->created_at));
                    })->rawColumns(['created_time'])
                    ->editColumn('note', function($query) {
                        return (is_null($query->note)) ? '/ /' : $query->note;
                    })
                    ->make(true);
                break;
            case "dodataforprint":
                $query = $this->headerToolsDataDoForPrint($request);
                return DataTables::of($query)
                    ->editColumn('delivery_date', function($query) {
                        return date('d/m/Y', strtotime($query->delivery_date));
                    })
                    ->make(true);
                break;
            default:
                return $this->_Error('Methode Not Found');
        }
    }
}
