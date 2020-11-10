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
            $data =  MtoEntry::all();
            return Datatables::of($data)
                ->editColumn('written', function(){
                    $data = Carbon::now()->format('d/m/Y');
                    return $data;
                })->editColumn('posted', function($data){
                    if ($data->posted != null) {
                        return $data->posted;
                    } else {
                        return '//';
                    }
                })->editColumn('voided', function($data){
                    if ($data->voided != null) {
                        return $data->voided;
                    } else {
                        return '//';
                    }
                })->editColumn('posted', function($data){
                    if ($data->posted != null) {
                        return Carbon::parse($data->posted)->format('d/m/Y');
                    } else {
                        return "//";
                    }
                })
                ->addColumn('action', function($data){
                    $ActionButton = '';
                   
                    // // $ActionButton .= '<a href="#" class="btn btn-info btn-sm" onclick="editForm('. $data->id_mto .')"><i class="ti-pencil"></i> Edit</a>';
                    // // $ActionButton .= '<a href="#" class="btn btn-sm btn-flat btn-info" onclick="editForm('. $data->mto_no .')"><i class="ti-pencil"></i> Edit</a>';

                    // // $ModuleEditAccess = RolePermissionControl::CheckPermission($RoleID, 'edit_modules');
                    // // if($ModuleEditAccess){


                        // $ActionButton = "<div class='pull-right'>";
                        // $ActionButton .= $ActionButton.ButtonBuilder::Build('DATATABLE', 'VIEW', 'view-btn', 'ti-eye', 'View','#', "row-id=$data->id_mto");
                        // $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE-LINK', 'EDIT', 'module-edit-btn', 'ti-pencil-alt', 'Edit', '#', "row-id=$data->id_mto");
                        // $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'DELETE', 'module-delete-btn', 'ti-trash', 'Delete', '', "row-id=$data->id_mto data-id=$data->mto_no");
 
                        // $ActionButton .= "<button type='button' class='btn btn-secondary btn-xs btn-flat'>";
                        // $ActionButton .= "<a href='". route('tms.warehouse.mto-entry_report_pdf_mtodata', base64_encode($data->id_mto)) ."' style='color: white;'><i class='fa fa-print'></i> Print</a>";
                        // $ActionButton .= "</button>";
                        // $ActionButton .= "<button type='button' class='btn btn-primary btn-xs btn-flat'>";
                        // $ActionButton .= "<a href='#' class='posted' data-id=$data->mto_no row-id=$data->id_mto style='color: white;'><i class='fa fa-paper-plane'></i> Post</a>";
                        // $ActionButton .= "</button>";
                        // $ActionButton .= "</div>";
                   
             
                // //     // $ModuleDeleteAccess = RolePermissionControl::CheckPermission($RoleID, 'delete_modules');
                // //     // if($ModuleDeleteAccess){
                // //     //     $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'DELETE', $data->id, 'ti-trash', 'Delete', '#', "name='$data->name'");
                // //     // }

                    // return $ActionButton;
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
            $getItem = Item::all();
            return Datatables::of($getItem)->make(true);  
        }
    }

    public function StoreMtoData(Request $request)
    {
        $request->validate([
            'types' => 'required',
        ]);

        $data = new MtoEntry();
        $get_mto_no = $data->getMtoNo();
        $userStaff = Auth::user()->UserID;
        $data = MtoEntry::create([
            'mto_no' => $get_mto_no,
            'fin_code' => $request->fin_code,
            'frm_code' => $request->frm_code,
            'descript' => $request->descript,
            'fac_unit' => $request->fac_unit !== '' ? $request->fac_unit : null,
            'fac_qty'=> $request->fac_qty !== '' ? $request->fac_unit : '-',
            'factor'=> $request->factor !== '' ? $request->factor : '-',
            'unit'=> $request->unit !== '' ? $request->unit : '-',
            'quantity'=> $request->quantity !== '' ? $request->quantity : '-',
            'qty_ng'=> $request->qty_ng !== '' ? $request->qty_ng : '0,00',
            'cost'=> $request->cost !== '' ? $request->cost : '-',
            'glinv'=> $request->glinv !== '' ? $request->glinv : '-',
            'types'=> $request->types !== '' ? $request->types : '-',
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
            'staff'=>  $userStaff,
            'dept'=> '-',
            'remark'=> $request->remark !== '' ? $request->remark : '-',
            'lbom'=> $request->lbom !== '' ? $request->lbom : '-',
            'xprinted'=> $request->xprinted !== '' ? $request->xprinted : '-',
            'operator'=> $request->operator !== '' ? $request->operator : '-'

        ]);
        return response()->json([
            'success' => true
        ]);
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
        $output = [
            'header' => $MTOHeader,
            'detail' => $MTODetail
        ];

        return response()->json($output);
    }

    public function editMtoData($id)
    {
        $data        = MtoEntry::find($id);
        $MTODetail   = MtoEntry::select(
                            'id_mto', 'mto_no', 'fin_code', 'frm_code', 'descript', 'fac_unit',
                            'fac_qty', 'factor', 'unit', 'quantity', 'qty_ng','cost','glinv','types','written','posted',
                            'warehouse','branch','ip_type','ref_no','uid_export'
                            )
                      ->where('id_mto', '=', $id)
                      ->get();
      
        $output = [
            'detail' => $MTODetail,
            'header' => $data
        ];
        return response()->json($output);
    }
    public function updateMtoEntry(Request $request, $id)
    {
        $data = MtoEntry::find($id);
        $data->update($request->all());
        return response()->json([
            'success' => true
        ]);

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
        return $pdf->download('report_mto'.'_'.  Carbon::now()->format('d/M/Y') . '.pdf');
    }

    public function postedMtoData($id)
    {
        $data = MtoEntry::find($id);
        $data['posted'] = Carbon::now();
        $data->save();

        return response()->json([
            'success' => true
        ]);
    }

    

}
