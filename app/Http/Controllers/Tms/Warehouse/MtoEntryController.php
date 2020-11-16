<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dbtbs\MtoEntry;
use DataTables;
use App\Models\Dbtbs\Formula;
use App\Models\Dbtbs\Item;
use App\Classes\ButtonBuilder As ButtonBuilder;
use Carbon\Carbon;
use DB;
use Auth;
use PDF;
use DateTime;
class MtoEntryController extends Controller
{
    public function index(Request $request)
    {
        $getDate = Carbon::now()->format('d/m/Y');
        $getDate1 =  Carbon::now()->format('Y/m');
        $data_mto = new MtoEntry();
        $get_no_mto = $data_mto->getMtoNo();
        return view('tms.warehouse.mto-entry.index', compact('getDate','getDate1','get_no_mto'));
    }

    public function getMtoDatatables(Request $request)
    {
        if ($request->ajax()) {
            $data =  MtoEntry::get();
            // dd($data);
            // if ($get_data == 1) {
            //     $data->posted()->get();
            // }
            return Datatables::of($data)    
                ->editColumn('written', function($data){
                    $data = Carbon::parse($data->written)->format('d/m/Y');
                    return $data;
                })->editColumn('posted', function($data){
                    if ($data->posted != null) {
                        return $data->posted;
                    } else {
                        return '//';
                    }
                })->editColumn('voided', function($data){
                    if ($data->voided != null) {
                        $get_data = Carbon::parse($data->voided)->format('d/m/Y');
                        return $get_data;
                    } else {
                        return '//';
                    }
                })->editColumn('posted', function($data){
                    if ($data->posted != null) {
                        $get_data = Carbon::parse($data->posted)->format('d/m/Y');
                        return $get_data;
                    } else {
                        return "//";
                    }
                })
                ->addColumn('action', function($data){
                    return view('tms.warehouse.mto-entry._action_datatables._actionmto', [
                            'model' => $data,
                            'url_print' => route('tms.warehouse.mto-entry_report_pdf_mtodata', base64_encode($data->id_mto))
                    ]);
                })->rawColumns(['action'])
                ->make(true);  
        }
    }

    public function getPopUpChoiceDataDatatables(Request $request)
    {
        if ($request->ajax()) {
            $getItem = Item::get();
            return Datatables::of($getItem)->make(true);  
        }
    }

    public function StoreMtoData(Request $request)
    {
        $request->validate([
            'types' => 'required',
            'quantity'=> 'required',
            'unit'=> 'required'
        ]);

        $data = new MtoEntry();
        $get_mto_no = $data->getMtoNo();
        $get_user_staff = Auth::user()->UserID;
        try {
            DB::beginTransaction();
            $data = MtoEntry::create([
                'mto_no' => $get_mto_no,
                'fin_code' => $request->fin_code,
                'frm_code' => $request->frm_code,
                'descript' => $request->descript,
                'fac_unit' => $request->fac_unit !== '' ? $request->fac_unit : null,
                'fac_qty'=> $request->fac_qty !== '' ? $request->fac_unit : null,
                'factor'=> $request->factor !== '' ? $request->factor : null,
                'unit'=> $request->unit !== '' ? $request->unit : null,
                'quantity'=> $request->quantity !== '' ? $request->quantity : null,
                'qty_ng'=> $request->qty_ng !== '' ? $request->qty_ng : '0,00',
                'cost'=> $request->cost !== '' ? $request->cost : null,
                'glinv'=> $request->glinv !== '' ? $request->glinv : null,
                'types'=> $request->types !== '' ? $request->types : null,
                'written'=> Carbon::now(),
                'posted'=> $request->posted !== '' ? $request->posted : null,
                'printed'=> $request->printed  !== '' ? $request->printed : null,
                'voided'=> $request->voided !== '' ? $request->voided : null,
                'warehouse'=> '90',
                'branch'=> 'HO',
                'ip_type'=> '-',
                'ref_no'=> $get_mto_no,
                'uid_export'=> $request->uid_export !== '' ? $request->uid_export : null,
                'period'=>  Carbon::now()->format('Y/m'),
                'vperiode'=>  $request->vperiod !== '' ? $request->vperiod : null,
                'staff'=>  $get_user_staff,
                'remark'=> $request->remark !== '' ? $request->remark : null,
                'lbom'=> $request->lbom !== '' ? $request->lbom : null,
                'xprinted'=> $request->xprinted !== '' ? $request->xprinted : null,
                'operator'=> $request->operator !== '' ? $request->operator : null
    
            ]);
               // INSERT LOG MTO
            date_default_timezone_set("Asia/Jakarta");
            $date = Carbon::now();
            $time = Carbon::now()->format('H:i:s');
            $status = "ADD";
            $mto_no = $get_mto_no;
            $userstaff = Auth::user()->FullName;
            $note = $data['warehouse'] . '/' . $data['fin_code'] .'/'. 'Qty:'. ' ' . $data['quantity'];
            DB::connection('db_tbs')->table('entry_mto_log')->insert([
                'mto_no' => $mto_no, 
                'date' => $date,
                'time' => $time,
                'status_change' => $status,
                'user' => $userstaff,
                'note' => $note 
            ]);

            DB::commit();
            return response()->json([ 'success' => true ]);

        } catch (Exception  $ex) {
            abort(404, $ex->getMessage());
        }
        

       
    }

    public function show_view_detail($id)
    {
        $MTOHeader   = MtoEntry::where('id_mto', $id)->first();
        $MTOHeaderNo = $MTOHeader->mto_no;
        $MTODetail   = MtoEntry::select(
                            'id_mto', 'mto_no', 'fin_code', 'frm_code', 'descript', 'fac_unit',
                            'fac_qty', 'factor', 'unit', 'quantity', 'qty_ng','cost','glinv','types','written','posted',
                            'warehouse','branch','ip_type','ref_no','uid_export'
                            )   
                      ->where('mto_no', '=', $MTOHeaderNo)
                      ->get();
        // $format_des = ',00';              
        $output = [
            'header' => $MTOHeader,
            'detail' => $MTODetail
        ];

        return response()->json($output);
    }

    public function editMtoData($id)
    {
        $data        = MtoEntry::where('id_mto', $id)->first();
        $MTODetail   = MtoEntry::select(
                            'id_mto', 'mto_no', 'fin_code', 'frm_code', 'descript', 'fac_unit',
                            'fac_qty', 'factor', 'unit', 'quantity', 'qty_ng','cost','glinv','types','written','posted',
                            'warehouse','branch','ip_type','ref_no','uid_export'
                            )
                      ->where('id_mto', '=', $id)
                      ->get();
      
        $output = [
            'header' => $data,
            'detail' => $MTODetail
           
        ];
        return response()->json($output);
    }
    public function updateMtoEntry(Request $request, $id)
    {
        $data = MtoEntry::find($id);
        try {
            DB::beginTransaction();
            $data->update($request->all());

            // INSERT LOG MTO
            date_default_timezone_set("Asia/Jakarta");
            $date = Carbon::now();
            $time = Carbon::now()->format('H:i:s');
            $status = "EDIT";
            $mto_no = $data['mto_no'];
            $userstaff = Auth::user()->FullName;
            $note = $data['warehouse'] . '/' . $data['fin_code'] .'/'. 'Qty:'. ' ' . $data['quantity'];
            DB::connection('db_tbs')->table('entry_mto_log')->insert([
                'mto_no' => $mto_no, 
                'date' => $date,
                'time' => $time,
                'status_change' => $status,
                'user' => $userstaff,
                'note' => $note 
            ]);

            DB::commit();

            return response()->json([
                'success' => true
            ]);

        } catch (Exception  $ex) {
            abort(404, $ex->getMessage());
        }
        
    }

    public function DeleteMtoData($id)
    {
        $data = MtoEntry::find($id);
        $data['voided'] = Carbon::now();
        $data->save();
        $data->delete();
        return response()->json([
            'success' => true,
        ]);
    }

    public function reportPdfMto($id)
    {
        $get_id = base64_decode($id);
        $data = MtoEntry::find($get_id);  
        $data['printed'] = Carbon::now();
        $data->save();
        $pdf = PDF::loadView('tms.warehouse.mto-entry.report.report', ['data' => $data]);
        // return $pdf->download('report_mto'.'_'.  Carbon::now()->format('d/M/Y') . '.pdf');
        return $pdf->stream();
    }

    public function postedMtoData($id)
    {
        $data = MtoEntry::find($id);
        try {
            DB::beginTransaction();
            $get_posted =  $data['posted'];
            if ($get_posted != null) {
                //un-posted
                $data['posted'] = NULL;
                $data->update();

                // INSERT LOG UN-POSTED
                date_default_timezone_set("Asia/Jakarta");
                $date = Carbon::now();
                $time = Carbon::now()->format('H:i:s');
                $status = "UN-POSTED";
                $mto_no = $data['mto_no'];
                $userstaff = Auth::user()->FullName;
                $note = $data['warehouse'] . '/' . $data['fin_code'] .'/'. 'Qty:'. ' ' . $data['quantity'];
                DB::connection('db_tbs')->table('entry_mto_log')->insert([
                    'mto_no' => $mto_no, 
                    'date' => $date,
                    'time' => $time,
                    'status_change' => $status,
                    'user' => $userstaff,
                    'note' => $note 
                ]);
            } else {
                // posted-mto

               $data['posted'] = Carbon::now();
               $data->save();

            // INSERT LOG POSTED MTO
               date_default_timezone_set("Asia/Jakarta");
               $date = Carbon::now();
               $time = Carbon::now()->format('H:i:s');
               $status = "POSTED";
               $mto_no = $data['mto_no'];
               $userstaff = Auth::user()->FullName;
               $note = $data['warehouse'] . '/' . $data['fin_code'] .'/'. 'Qty:'. ' ' . $data['quantity'];
               DB::connection('db_tbs')->table('entry_mto_log')->insert([
                   'mto_no' => $mto_no, 
                   'date' => $date,
                   'time' => $time,
                   'status_change' => $status,
                   'user' => $userstaff,
                   'note' => $note 
               ]);
            }


            DB::commit();

            return response()->json([
                'success' => true
            ]);
        } catch (Exception $ex) {
            abort(404, $ex->getMessage());
        }
        
       
    }

    public function viewLogMtoEntry($mto_no)
    {   
       $data =  DB::connection('db_tbs')
                  ->table('entry_mto_log')
                  ->where('mto_no','=', $mto_no)
                  ->get();         
       return response()->json($data);
    }

    

}
