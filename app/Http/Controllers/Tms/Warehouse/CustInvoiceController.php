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

    public function inv_table(Request $request)
    {
        $query = $this->inv_tbl($request);
        return DataTables::of($query)
            ->editColumn('written_date', function($query) {
                return date('d/m/Y', strtotime($query->written_date));
            })
            ->editColumn('posted_date', function($query) {
                return ($query->posted_date == NULL) ? '/ /' : date('d/m/Y', strtotime($query->posted_date));
            })
            ->editColumn('voided_date', function($query) {
                return ($query->voided_date == NULL) ? '/ /' : date('d/m/Y', strtotime($query->voided_date));
            })
            ->addColumn('action', function($query){
                return view('tms.warehouse.cust-invoice.button.btnTableIndex', [
                    'data' => $query,
                ]);
            })->rawColumns(['action'])
            ->make(true);
    }

    public function inv_detail($inv_no)
    {
        $custinv = CustInvoice::select([
            'db_tbs.entry_custinvoice_tbl.*',
            'ekanban.ekanban_customermaster.CustomerName as cust_name',
            'ekanban.ekanban_customermaster.CustomerCode_eKanban as code',
            'ekanban.ekanban_customermaster.Address1 as ad1', 
            'ekanban.ekanban_customermaster.Address2 as ad2', 
            'ekanban.ekanban_customermaster.Address3 as ad3', 
            'ekanban.ekanban_customermaster.Address4 as ad4', 
            'ekanban.ekanban_customermaster.GLAR as glcode',
            'db_tbs.sys_account.name as glname'
        ])
        ->leftJoin('db_tbs.sys_account', 'db_tbs.sys_account.number', '=', 'db_tbs.entry_custinvoice_tbl.glar')
        ->leftJoin('ekanban.ekanban_customermaster', 'ekanban.ekanban_customermaster.CustomerCode_eKanban', '=', 'db_tbs.entry_custinvoice_tbl.cust_id')
        ->where('db_tbs.entry_custinvoice_tbl.inv_no', $inv_no)->get();
        if ($custinv->isNotEmpty()) {
            $arr_do = [];
            foreach ($custinv as $inv) {
                $arr_do[] = $inv->do_no;
            }
            $result = [
                'custinv' => $custinv,
                'by_item' => $this->callDoJoin($arr_do),
                'by_do' => $this->callDoEntryGB([
                    'cust_id' => $custinv[0]->cust_id,
                    'branch' => $custinv[0]->branch,
                    'arr_do' => $arr_do
                ])
            ];
            return _Success(null, 200, $result);
        }
        return _Error('Invoice not found!', 404);
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
        if ($request->ajax()) {
            $old = CustInvoice::where('inv_no', $inv_no)->first();
            $create_by = $old->written_by;
            $create_date = $old->written_date;
            $data_insert = [];
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
                    'written_date' => $create_date,
                    'written_by' => $create_by,
                    'updated_date' => Carbon::now(),
                    'updated_by' => Auth::user()->FullName
                ];
            }
            try {
                $query = CustInvoice::insert($data_insert);
                if ($query) {
                    $log = $this->createGlobalLog('db_tbs.entry_custinvoice_tbl_log', [
                        'inv_no' => $request->inv_no,
                        'status' => 'UPDATED',
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
