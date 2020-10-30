<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dbtbs\MtoEntry;
use DataTables;
use App\Models\Dbtbs\Formula;
use App\Models\Dbtbs\Item;
use Carbon\Carbon;
use DB;
use Auth;
class MtoEntryController extends Controller
{
    public function index(Request $request)
    {
        $getDate = Carbon::now()->format('d/m/Y');
        $getDate1 =  Carbon::now()->format('Y/m');
        $data_mto = new MtoEntry();
        $get_no_mto = $data_mto->getMtoNo();
        //passing data ke modal view
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
                    if ($data->voided) {
                        return $data->voided;
                    } else {
                        return '//';
                    }
                })->editColumn('action', function(){
                    return '-';
                })
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

        $data = new MtoEntry();
        $get_mto_no = $data->getMtoNo();
        $userStaff = Auth::user()->UserID;
        $data = MtoEntry::create([
            'mto_no' => $get_mto_no,
            'itemcode' => $request->itemcode,
            'part_no' => $request->part_no,
            'descript' => $request->descript,
            'fac_unit' => $request->fac_unit !== '' ? $request->fac_unit : null,
            'fac_qty'=> $request->fac_qty !== '' ? $request->fac_unit : '-',
            'factor'=> $request->factor !== '' ? $request->factor : '-',
            'unit'=> $request->unit !== '' ? $request->unit : '-',
            'quantity'=> $request->quantity !== '' ? $request->quantity : '-',
            'qty_ng'=> $request->qty_ng !== '' ? $request->qty_ng : '0.00',
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
            'uid_export'=> '-',
            'period'=>  '-',
            'vperiode'=>  '-',
            'staff'=>  $userStaff,
            'dept'=> '-',
            'remark'=> $request->remark !== '' ? $request->remark : '-',
            'lbom'=> $request->lbom !== '' ? $request->lbom : '-',
            'xprinted'=> $request->xprinted !== '' ? $request->xprinted : '-',
            'operator'=> $request->operator !== '' ? $request->operator : '-'

        ]);
        // dd($data);
        return redirect()->back();
    }

    

}
