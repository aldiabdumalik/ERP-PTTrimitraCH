<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\CustInvTrait;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\CustInvoice;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustInvoiceController extends Controller
{
    use ToolsTrait, CustInvTrait;

    public function __construct() {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.warehouse.cust-invoice.index');
    }

    public function delivery_order(Request $request)
    {
        $customer = $request->cust_id;
        $branch = Auth::user()->Branch;
        
        if (!isset($request->call_do_id)) {
            $req_dt = $this->callDoEntry([
                'cust_id' => $customer,
                'branch' => $branch
            ]);

            return DataTables::of($req_dt)
                ->editColumn('do_date', function($req_dt) {
                        return convertDate($req_dt->do_date, 'Y-m-d', 'd/m/Y');
                    }
                )
                ->make(true);
        }

        $req_gb = $this->callDoEntryGB([
            'cust_id' => $customer,
            'branch' => $branch,
            'arr_do' => $request->arr_do
        ]);

        $req = $this->callDoJoin($request->arr_do);

        $response = [
            'do' => $req_gb,
            'itemcode' => $req
        ];

        return _Success(null, 200, $response);
    }

    public function save(Request $request)
    {
        $data_insert = [];
        if ($request->ajax()) {
            $item = $request->inv_item;
            for ($i=0; $i < count($item); $i++) { 
                $data_insert[] = [
                    'inv_no' => $request->inv_no,
                    'inv_type' => $request->inv_type,
                    'do_no' => $item[$i][1],
                    'cust_id' => $request->inv_customercode,
                    'combine_id' => $request->inv_customerdoaddr,
                    'cust_type' => null,
                    'do_addr' => null,
                    'cust_contact' => $request->inv_an,
                    'ref_no' => $request->inv_refno,
                    'pref_tax' => $request->inv_vat1,
                    'tax_no' => $request->inv_vat2,
                    'tax_rate' => $request->inv_vat3,
                    'periode' => $request->inv_priod,
                    'due_date' => $request->inv_duedate,
                    'branch' => $request->inv_branch,
                    // 'warehouse' => $request->inv_no,
                    'valas' => $request->inv_currencytype,
                    'rate' => str_replace(',', '', $request->inv_currencyvalue),
                    'amount_sub' => str_replace(',', '', $request->inv_subtotal),
                    'amount_dis' => str_replace(',', '', $request->inv_cndisc),
                    'amount_tax' => str_replace(',', '', $request->inv_vat),
                    'amount_cn' => 0.00,
                    'amount_dn' => 0.00,
                    'amount_pay' => str_replace(',', '', $request->inv_payment),
                    'amount_bal' => str_replace(',', '', $request->inv_balance),
                    'amount_exp' => 0.00,
                    'amount_cos' => 0.00,
                    'commission' => 0.00,
                    'term' => $request->inv_term,
                    'totline' => $request->inv_totline,
                    'glar' => $request->inv_glcode,
                    'remark' => $request->inv_remark,
                    'written_date' => Carbon::now(),
                    'written_by' => Auth::user()->FullName
                ];
            }
            try {
                $query = CustInvoice::insert($data_insert);
                if ($query) {
                    $log = $this->createGlobalLog('db_tbs.entry_custinvoice_tbl_log', [
                        'inv_no' => $request->inv_no,
                        'status' => 'ADD',
                        'note' => null,
                        'written_at' => Carbon::now(),
                        'written_by' => Auth::user()->FullName
                    ]);
                }
                return $this->_Success('Saved successfully!', 201);
            } catch (Exception $e) {
                return $this->_Error('failed to save, please check your form again', 401, $e->getMessage());
            }
        }
        return _Success(null, 200, $data_insert);
    }

    public function update($inv_no, Request $request)
    {
        return _Success(null, 200, $request);
    }

    public function posted(Request $request)
    {
        $cek = CustInvoice::where([
                'inv_no' => $request->inv_no
            ])
            ->whereNull('voided_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Invoice has been voided');
        }

        $posted = CustInvoice::where([
                'inv_no' => $request->inv_no
            ])->update([
                'posted_date' => Carbon::now(),
                'posted_by' => Auth::user()->FullName
            ]);
        if ($posted) {
            $log = $this->createGlobalLog('db_tbs.entry_custinvoice_tbl_log', [
                'inv_no' => $request->inv_no,
                'status' => 'POSTED',
                'note' => null,
                'written_at' => Carbon::now(),
                'written_by' => Auth::user()->FullName
            ]);
        }
        return _Success('Invoice has been Posted');
    }

    public function unposted(Request $request)
    {
        $cek = CustInvoice::where([
                'inv_no' => $request->inv_no
            ])
            ->whereNull('voided_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Invoice has been voided');
        }

        $unposted = CustInvoice::where([
                'inv_no' => $request->inv_no
            ])->update([
                'posted_date' => null,
                'posted_by' => null
            ]);
        if ($unposted) {
            $log = $this->createGlobalLog('db_tbs.entry_custinvoice_tbl_log', [
                'inv_no' => $request->inv_no,
                'status' => 'UNPOSTED',
                'note' => $request->note,
                'written_at' => Carbon::now(),
                'written_by' => Auth::user()->FullName
            ]);
        }
        return _Success('Invoice has been Posted');
    }

    public function voided(Request $request)
    {
        $cek = CustInvoice::where([
                'inv_no' => $request->inv_no
            ])
            ->whereNull('posted_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Invoice has been posted');
        }

        $voided = CustInvoice::where([
                'inv_no' => $request->inv_no
            ])->update([
                'voided_date' => Carbon::now(),
                'voided_by' => Auth::user()->FullName
            ]);
        if ($voided) {
            $log = $this->createGlobalLog('db_tbs.entry_custinvoice_tbl_log', [
                'inv_no' => $request->inv_no,
                'status' => 'VOIDED',
                'note' => $request->note,
                'written_at' => Carbon::now(),
                'written_by' => Auth::user()->FullName
            ]);
        }
        return _Success('Invoice has been voided');
    }

    public function unvoided(Request $request)
    {
        $cek = CustInvoice::where([
                'inv_no' => $request->inv_no
            ])
            ->whereNull('posted_date')
            ->get();

        if ($cek->isEmpty()) {
            return _Error('Invoice has been posted');
        }

        $unvoided = CustInvoice::where([
                'inv_no' => $request->inv_no
            ])->update([
                'voided_date' => null,
                'voided_by' => null
            ]);
        if ($unvoided) {
            $log = $this->createGlobalLog('db_tbs.entry_custinvoice_tbl_log', [
                'inv_no' => $request->inv_no,
                'status' => 'UNVOIDED',
                'note' => $request->note,
                'written_at' => Carbon::now(),
                'written_by' => Auth::user()->FullName
            ]);
        }
        return _Success('Invoice has been voided');
    }

    public function header(Request $request)
    {
        switch ($request->type) {
            case 'invno':
                return $this->custInvNo($request);
                break;
            
            case 'customer':
                return DataTables::of($this->customer($request))->make(true);
                break;

            case 'cek_invno':
                $query = CustInvoice::where('inv_no', $request->inv_no)->first();
                if (isset($query)) {
                    return _Success('is_exist');
                }
                return _Success('isnt_exist');
                break;

            case 'sys_account':
                $req = $this->sys_account($request);
                if (isset($request->number)) {
                    return _Success(null, 200, $req);
                }
                return DataTables::of($req)
                    ->editColumn('ldept', function($req) {
                            return view('tms.warehouse.cust-invoice.button.ldept', [
                                'req' => $req,
                            ]);
                        }
                    )
                    ->editColumn('ldiv', function($req) {
                            return view('tms.warehouse.cust-invoice.button.ldiv', [
                                'req' => $req,
                            ]);
                        }
                    )
                    ->make(true);
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
                
            default:
                return _Error('Params not exist!', 404);
                break;
        }
    }
}
