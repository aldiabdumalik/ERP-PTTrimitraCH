<?php

namespace App\Http\Controllers\Tms\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\DoEntryTrait;
use App\Models\Dbtbs\DoEntry;
use App\Models\Dbtbs\DoEntrySetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
        $query = DoEntry::query()->groupBy('do_no')->get();
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

    public function DoEntryRead(Request $request)
    {
        if (isset($request->do_no)) {
            $query = DoEntry::where('do_no', $request->do_no)->get();
            if (isset($request->view_do)) {
                $check = $query->first();
                if ($check->sso_no !== '*') {
                    $check_by = 'sso';
                }else{
                    $check_by = 'so';
                }

                $data = (object)[
                    'do_no' => $request->do_no,
                    'check_by' => $check_by
                ];
                $query = $this->headerToolsViewDo($data);
                return $this->_Success('Data exist!', 200, $query);
            }elseif (isset($request->check)) {
                $message = $this->headerToolsCheckDO($request);
                return $this->_Success($message);
            }elseif (isset($request->check_print)) {
                $message = $this->headerToolsCheckDO($request);
                return $this->_Success($message);
            }
            if ($query->isEmpty()) {
                return $this->_Success('false');
            }else{
                return $this->_Success('Data exist!', 200, $query);
            }
        }
        return $this->_Error('Methode not exist!');
    }

    public function DoEntryCreate(Request $request)
    {
        $items = $request->items;
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
        for ($i=0; $i < count($items); $i++) { 
            $data[] = [
                'do_no' => $request->do_no,
                'row_no' => $items[$i][0],
                'item_code' => $items[$i][1],
                'quantity' => $items[$i][4],
                'unit' => $items[$i][3],
                'so_no' => $request->so,
                'sso_no' => $request->sso,
                'ref_no' => $request->refno,
                'po_no' => $request->pono,
                'rr_no' => $request->pono,
                'dn_no' => $request->dnno,
                'invoice' => $request->inv,
                'period' => $request->priod,
                'cust_id' => $request->customercode,
                'do_address' => $request->customerdoaddr,
                'cust_name' => $request->customername,
                'source' => "",
                'id_driver' => "",
                'drv_name' => $request->delivery2,
                'remark' => $request->remark,
                'branch' => $request->branch,
                'warehouse' => $request->warehouse,
                'sj_type' => $request->sj_type,
                'delivery_date' => Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'),
                'direct_date' => null,
                'do_trans' => 0,
                'created_by' => Auth::user()->FullName,
                'created_date' => date('Y-m-d H:i:s')
            ];
        }
        try {
            $query = DoEntry::insert($data);
            $log = $this->createLOG($request->do_no, 'ADD');
            return $this->_Success('Saved successfully!', 201);
        } catch (Exception $e) {
            return $this->_Error('failed to save, please check your form again', 401, $e->getMessage());
        }
    }

    public function DoEntryUpdate(Request $request)
    {
        $periodYear = Carbon::createFromFormat('Y-m',  $request->priod)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $request->priod)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isNotEmpty()) {
            return _Error('Sudah closing tidak bisa edit');
        }

        $items = $request->items;
        $data = [];
        $old_data = DoEntry::where('do_no', $request->do_no)->first();
        $create_by = $old_data->created_by;
        $create_date = $old_data->created_date;
        $old_data = DoEntry::where('do_no', $request->do_no)->delete();

        for ($i=0; $i < count($items); $i++) { 
            $data[] = [
                'do_no' => $request->do_no,
                'row_no' => $items[$i][0],
                'item_code' => $items[$i][1],
                'quantity' => $items[$i][4],
                'unit' => $items[$i][3],
                'so_no' => $request->so,
                'sso_no' => $request->sso,
                'ref_no' => $request->refno,
                'po_no' => $request->pono,
                'rr_no' => $request->pono,
                'dn_no' => $request->dnno,
                'invoice' => $request->inv,
                'period' => $request->priod,
                'cust_id' => $request->customercode,
                'do_address' => $request->customerdoaddr,
                'cust_name' => $request->customername,
                'source' => "",
                'id_driver' => "",
                'drv_name' => $request->delivery2,
                'remark' => $request->remark,
                'branch' => $request->branch,
                'warehouse' => $request->warehouse,
                'sj_type' => $request->sj_type,
                'delivery_date' => Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'),
                'direct_date' => null,
                'do_trans' => 0,
                'created_by' => $create_by,
                'created_date' => $create_date,
                'update_by' => Auth::user()->FullName,
                'update_date' => date('Y-m-d H:i:s')
            ];
        }
        try {
            $query = DoEntry::insert($data);
            $log = $this->createLOG($request->do_no, 'EDIT');
            return $this->_Success('Updated successfully!', 201);
        } catch (Exception $e) {
            return $this->_Error('failed to update, please check your form again', 401, $e->getMessage());
        }
    }

    public function DoEntryDeleteItem(Request $request)
    {
        $do = DoEntry::where('do_no', $request->do_no)->get();
        if ($do->isEmpty()) {
            return $this->_Success(NULL);
        }
        $update = DoEntry::where('do_no', $request->do_no)
            ->where('item_code', $request->itemcode)
            ->update([
                'delete_by' => Auth::user()->FullName,
                'delete_date' => date('Y-m-d H:i:s')
            ]);
        return $this->_Success('Data Exist');
    }

    public function DoEntryCancel(Request $request)
    {
        $do = DoEntry::where('do_no', $request->do_no)->whereNotNull('delete_date')->first();
        if ($do) {
            return $this->_Success(NULL);
        }
        $update = DoEntry::where('do_no', $request->do_no)
            ->update([
                'delete_by' => null,
                'delete_date' => null
            ]);
        return $this->_Success('Data Exist');
    }

    public function DoEntryPost(Request $request)
    {
        $query = DoEntry::where('do_no', $request->do_no)->first();
        $periodYear = Carbon::createFromFormat('Y-m',  $query->period)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $query->period)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isNotEmpty()) {
            return _Error('Sudah closing tidak bisa dipost');
        }
        if (isset($query)) {
            $posted = DoEntry::where('do_no', $request->do_no)->update([
                'posted_date' => date('Y-m-d H:i:s'),
                'posted_by' => Auth::user()->FullName,
                'rr_no' => $request->rr_no
            ]);
            $log = $this->createLOG($request->do_no, 'POST');
            if ($posted) {
                return $this->_Success("DO No. $request->do_no, has been posted!", 201);
            }else{
                return $this->_Error("DO No. $request->do_no, failed to posting!");
            }
        }
        return $this->_Error('Data or Method Not Found!');
    }

    public function DoEntryVoid(Request $request)
    {
        $query = DoEntry::where('do_no', $request->do_no)->first();
        $periodYear = Carbon::createFromFormat('Y-m',  $query->period)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $query->period)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isNotEmpty()) {
            return _Error('Sudah closing tidak bisa void');
        }
        if (isset($query)) {
            $voided = DoEntry::where('do_no', $request->do_no)->update([
                'voided_date' => date('Y-m-d H:i:s'),
                'voided_by' => Auth::user()->FullName
            ]);
            $log = $this->createLOG($request->do_no, 'VOID');
            if ($voided) {
                return $this->_Success("DO No. $request->do_no, has been voided!", 201);
            }else{
                return $this->_Error("DO No. $request->do_no, failed to void!");
            }
        }
        return $this->_Error('Data or Method Not Found!');
    }

    public function DoEntryUnpost(Request $request)
    {
        $query = DoEntry::where('do_no', $request->do_no)->first();
        $periodYear = Carbon::createFromFormat('Y-m',  $query->period)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $query->period)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isNotEmpty()) {
            return _Error('Sudah closing tidak bisa unpost');
        }
        if (isset($query)) {
            $posted = DoEntry::where('do_no', $request->do_no)->update([
                'posted_date' => null,
                'posted_by' => null,
                'rr_no' => null
            ]);
            $log = $this->createLOG($request->do_no, 'UNPOST', $request->note);
            if ($posted) {
                return $this->_Success("DO No. $request->do_no, has been unposted!", 201);
            }else{
                return $this->_Error("DO No. $request->do_no, failed to unposting!");
            }
        }
        return $this->_Error('Data or Method Not Found!');
    }

    public function DoEntryUnvoid(Request $request)
    {
        $query = DoEntry::where('do_no', $request->do_no)->first();
        $periodYear = Carbon::createFromFormat('Y-m',  $query->period)->format('Y');
        $periodMonth = Carbon::createFromFormat('Y-m',  $query->period)->format('m');
        $check =  DB::connection('db_tbs')
            ->table('stclose')
            ->whereYear('DATE','=',  $periodYear)
            ->whereMonth('DATE','=', $periodMonth)
            ->get();
        if ($check->isNotEmpty()) {
            return _Error('Sudah closing tidak bisa unvoid');
        }
        if (isset($query)) {
            if ($query->revised_date != null) {
                return $this->_Error("DO No. $request->do_no failed to unvoid, because it has been revised to DO No. $query->revised_to!");
            }
            $voided = DoEntry::where('do_no', $request->do_no)->update([
                'voided_date' => null,
                'voided_by' => null
            ]);
            $log = $this->createLOG($request->do_no, 'UNVOID', $request->note);
            if ($voided) {
                return $this->_Success("DO No. $request->do_no, has been unvoided!", 201);
            }else{
                return $this->_Error("DO No. $request->do_no, failed to unvoid!");
            }
        }
        return $this->_Error('Data or Method Not Found!');
    }

    public function DoEntryPrint(Request $request)
    {
        if (isset($request->params) && $request->params != "") {
            $decode = base64_decode($request->params);
            $arr = explode('&', $decode);

            $data = (object) [];
            $data->dari = $arr[0];
            $data->sampai = $arr[1];
            $data->type = $arr[2];

            if ($data->sampai <= $data->dari && $data->sampai !== $data->dari) {
                $request->session()->flash('message', 'Invalid data input!');
                return Redirect::back();
            }

            $result = $this->headerToolsDataForPrint($data);

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

            $log_print = [];
            $log_post = [];
            for ($i=0; $i < count($getKey); $i++) { 
                $log_print[] = [
                    'do_no' => $getKey[$i],
                    'date_log' => date('Y-m-d'),
                    'time_log' => date('H:i:s'),
                    'status_log' => 'PRINT',
                    'user' => Auth::user()->FullName,
                    'note' => null
                ];

                if ($getValue[$i][0]['posted'] === null) {
                    $log_post[] = [
                        'do_no' => $getKey[$i],
                        'date_log' => date('Y-m-d'),
                        'time_log' => date('H:i:s'),
                        'status_log' => 'POST',
                        'user' => Auth::user()->FullName,
                        'note' => null
                    ];
                }
            }

            $posted = DoEntry::where('do_no', '>=', $data->dari)
                ->where('do_no', '<=', $data->sampai)
                ->where('voided_date', '=', null)
                ->update([
                    'posted_date' => date('Y-m-d H:i:s'),
                    'posted_by' => Auth::user()->FullName,
                ]);
            $insert_log_print = $this->createLOGBatch($log_print);
            $insert_log_post = $this->createLOGBatch($log_post);
            
            $template = 'tms.warehouse.do-entry.report.report';
            if ($data->type == 'blank') {
                $template = 'tms.warehouse.do-entry.report.report';
            }else{
                $template = 'tms.warehouse.do-entry.report.reportTemplate';
            }
            $pdf = PDF::loadView($template, compact('data', 'groupItem', 'getKey'))->setPaper('a4', 'potrait');
            return $pdf->stream();
        }else{
            $request->session()->flash('message', 'Data tidak ditemukan!');
            return Redirect::back();
        }
    }

    public function DoEntryRevise(Request $request)
    {
        if (!isset($request->posted)) {
            return $this->_Error('Can\'t revised. it has not been posted');
        }
        $do_no = $request->do_no;
        $do_new = $this->headerToolsDoEntryNo($request);
        $voided = DoEntry::where('do_no', $do_no)->update([
            'voided_date' => date('Y-m-d H:i:s'),
            'voided_by' => Auth::user()->FullName,
            'finished_date' => null,
            'finished_by' => null,
            'posted_date' => null,
            'posted_by' => null,
            'revised_date' => date('Y-m-d H:i:s'),
            'revised_by' => Auth::user()->FullName,
            'revised_to' => $do_new,
        ]);
        $log = $this->createLOG($do_no, 'VOID', 'VOIDED BY SYSTEM');

        $items = $request->items;
        $data = [];
        for ($i=0; $i < count($items); $i++) { 
            $data[] = [
                'do_no' => $do_new,
                'row_no' => $items[$i][0],
                'item_code' => $items[$i][1],
                'quantity' => $items[$i][4],
                'unit' => $items[$i][3],
                'so_no' => $request->so,
                'sso_no' => $request->sso,
                'ref_no' => $request->refno,
                'po_no' => $request->pono,
                'dn_no' => $request->dnno,
                'invoice' => $request->inv,
                'period' => $request->priod,
                'cust_id' => $request->customercode,
                'do_address' => $request->customerdoaddr,
                'cust_name' => $request->customername,
                'source' => "",
                'id_driver' => "",
                'drv_name' => $request->delivery2,
                'remark' => $request->remark,
                'branch' => $request->branch,
                'warehouse' => $request->warehouse,
                'sj_type' => $request->sj_type,
                'delivery_date' => Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'),
                'direct_date' => null,
                'do_trans' => 0,
                'created_by' => Auth::user()->FullName,
                'created_date' => date('Y-m-d H:i:s')
            ];
        }
        try {
            $query = DoEntry::insert($data);
            $log = $this->createLOG($do_new, "ADD", "REVISE FROM DO No. $do_no");
            return $this->_Success("Revise successfully! New DO No. $do_new", 201);
        } catch (Exception $e) {
            return $this->_Error('failed to Revise, please check your form again', 401, $e->getMessage());
        }
    }

    public function DoEntryFinish(Request $request)
    {
        $check = DoEntry::where('do_no', $request->do_no)->first();
        if ($check->posted_date == null) {
            return $this->_Error('Can\'t finished. it has not been posted');
        }else{
            $update = DoEntry::where('do_no', $request->do_no)
                ->update([
                    'finished_date' => date('Y-m-d H:i:s'),
                    'finished_by' => Auth::user()->FullName,
                ]);
            $log = $this->createLOG($request->do_no, "FINISH");
            return $this->_Success('DO entry has been finished');
        }
    }

    public function DoEntryUnfinish(Request $request)
    {
        $check = DoEntry::where('do_no', $request->do_no)->first();
        if ($check->finished_date == null) {
            return $this->_Error('Can\'t unfinished. it has not been finished');
        }
        $update = DoEntry::where('do_no', $request->do_no)
            ->update([
                'finished_date' => null,
                'finished_by' => null,
            ]);
        $log = $this->createLOG($request->do_no, "UNFINISH", $request->note);
        return $this->_Success('DO entry has been unfinished');
    }

    public function DoEntryNG(Request $request)
    {
        $items = $request->items;
        if (count($items) > 0) {
            $ng = 0;
            for ($i=0; $i < count($items); $i++) {
                $ng = (($items[$i]['qty_ng'] == "") ? 0 : $items[$i]['qty_ng']);
                if (((int)$items[$i]['qty_sj'] - (int)$ng) === 0) {
                    DoEntry::where('do_no', $items[$i]['do_no'])
                        ->where('item_code', $items[$i]['itemcode'])
                        ->delete();
                }else{
                    DoEntry::where('do_no', $items[$i]['do_no'])
                        ->where('item_code', $items[$i]['itemcode'])
                        ->update([
                            'quantity' => (int)$items[$i]['qty_sj'] - (int)$items[$i]['qty_ng']
                        ]);
                }
            }
        }
        return $this->_Success('Successfuly create Qty NG');
    }

    public function DoEntryHeader(Request $request)
    {
        switch ($request->type) {
            case "DONo":
                // return $this->headerToolsDoEntryNo($request);
                return $this->headerToolsDoEntryNoTbs();
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
                // return $this->headerToolsCustomerClick($request);
                $query = DB::connection('ekanban')
                    ->table('ekanban_customermaster')
                    ->where('CustomerCode_eKanban', $request->cust_code)
                    ->selectRaw('CustomerCode_eKanban as code, CustomerName as name, Cus_Group as cg')
                    ->first();
                if (!$query) {
                    return _Error('Customer tidak ditemukan');
                }
                return _Success(null, 200, $query);
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
                    if ($sso->branch !== Auth::user()->Branch) {
                        return $this->_Error('SSO/SO data not match with your branch!', 404);
                    }
                    return $this->_Success('OK!', 200, $sso);
                }
                break;
            case "so_header":
                $so = $this->headerToolsSOHeader($request);
                if (!isset($so)) {
                    return $this->_Error('SO data not found!', 404);
                }else{
                    if ($so->branch !== Auth::user()->Branch) {
                        return $this->_Error('SSO/SO data not match with your branch!', 404);
                    }
                    return $this->_Success('OK!', 200, $so);
                }
                break;
            case "sso_detail":
                $result = $this->headerToolsSSODetail($request);
                $do = $this->qtySJ($request->do_no);
                if ($result->isEmpty()) {
                    return $this->_Error('SSO/SO data not found!', 404);
                }
                $result = [
                    'result' => $result,
                    'do' => $do
                ];
                return $this->_Success('OK!', 200, $result);
                break;
            case "dodataforprint":
                $query = $this->headerToolsDataDoForPrint($request);
                return DataTables::of($query)
                    ->editColumn('delivery_date', function($query) {
                        return date('d/m/Y', strtotime($query->delivery_date));
                    })
                    ->make(true);
                break;
            case 'customer_list':
                $query = 
                    DB::connection('ekanban')
                    ->table('ekanban_customermaster')
                    ->selectRaw('CustomerCode_eKanban as code, CustomerName as name')
                    ->where('status_data', 'ACTIVE')
                    ->where('CustomerCode_eKanban', 'like', '%'. $request->like .'%')
                    ->get();
                return _Success(null, 200, $query);
                break;
            default:
                return $this->_Error('Methode Not Found');
        }
    }
}
