<?php

namespace App\Http\Controllers\TMS\Engineering;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DB_parts\InputParts;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class InputPartsController extends Controller
{
    use ToolsTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.db_parts.input_parts.index');
    }

    public function tableIndex(Request $request)
    {
        if ($request->ajax()) {
            $result = InputParts::query()->where('is_active', 1)->get();
            return DataTables::of($result)
            ->addColumn('action', function($result){
                return view('tms.db_parts.input_parts.button.btnTableIndex', [
                    'data' => $result,
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function detail($id, Request $request)
    {
        $req = InputParts::query()
        ->leftJoin('ekanban.ekanban_customermaster as customer', 'customer.CustomerCode_eKanban', '=', 'db_tbs.dbparts_item_part_tbl.cust_id')
        ->leftJoin('db_tbs.dbparts_item_part_tbl as part_parent', 'part_parent.parent_id', '=', 'db_tbs.dbparts_item_part_tbl.id')
        ->select([
            'db_tbs.dbparts_item_part_tbl.*',
            'customer.CustomerName as cust_name',
            'part_parent.parent_id as parent',
            'part_parent.part_no as parent_no',
            'part_parent.part_name as parent_name',
        ])
        ->where('db_tbs.dbparts_item_part_tbl.id', $id)->first();

        if (is_null($req)) {
            return _Success('Data Not Found', 200);
        }

        return _Success('OK', 200, $req);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $parent_id = null;
                if ($request->parent_id) {
                    $parent = InputParts::query()->where('part_no', $request->parent_id)->first();
                    $parent_id = $parent->id;
                }
                File::move(public_path('db-parts/temp/' . $request->part_pict), public_path('db-parts/pictures/' . $request->part_pict));

                $data = [
                    // 'part_id' => $request->part_id,
                    'parent_id' => $parent_id,
                    'type' => $request->type,
                    'reff' => $request->reff,
                    'cust_id' => $request->cust_id,
                    'part_no' => $request->part_no,
                    'part_name' => $request->part_name,
                    'part_pict' => $request->part_pict,
                    'part_vol' => $request->part_vol,
                    'qty_part_item' => $request->qty_part_item,
                    'gop_assy' => $request->gop_assy,
                    'gop_single' => $request->gop_single,
                    'purch_part' => $request->purch_part,
                    'spec' => $request->spec,
                    'ms_t' => $request->ms_t,
                    'ms_w' => $request->ms_w,
                    'ms_l' => $request->ms_l,
                    'ms_n_strip' => $request->ms_n_strip,
                    'ms_coil_pitch' => $request->ms_coil_pitch,
                    'part_weight' => $request->part_weight,
                    'vendor_name' => $request->vendor_name,
                    'created_by' => Auth::user()->FullName,
                    'created_date' => Carbon::now(),
                    'is_active' => 1
                ];
                
                $insrtGetId = InputParts::insertGetId($data);

                DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
                    'id_part' => $insrtGetId,
                    'status' => 'ADD',
                    'note' => 'ADD NEW ITEM',
                    'log_date' => Carbon::now(),
                    'log_by' => Auth::user()->FullName
                ]);

                return _Success('Saved successfully!');
            } catch (Exception $e) {
                return _Error('Failed to save, please check form again!', 401, $e->getMessage());
            }
        }
    }

    public function update($id, Request $request)
    {
        if ($request->ajax()) {
            try {
                $prev = InputParts::query()
                    ->select([
                        'id',
                        'parent_id',
                        'type',
                        'reff',
                        'cust_id',
                        'part_no',
                        'part_name',
                        'part_pict',
                        'part_vol',
                        'qty_part_item',
                        'gop_assy',
                        'gop_single',
                        'purch_part',
                        'spec',
                        'ms_t',
                        'ms_w',
                        'ms_l',
                        'ms_n_strip',
                        'ms_coil_pitch',
                        'part_weight',
                        'vendor_name',
                        'spec_pict',
                    ])
                    ->where('id', $id)
                    ->first();

                $parent_id = null;
                if ($request->parent_id) {
                    $parent = InputParts::query()->where('part_no', $request->parent_id)->first();
                    $parent_id = $parent->id;
                }

                if ($request->part_pict !== $prev->part_pict) {
                    if (File::exists(public_path('db-parts/temp/' . $request->part_pict))) {
                        File::move(public_path('db-parts/temp/' . $request->part_pict), public_path('db-parts/pictures/' . $request->part_pict));
                    }
                    if (File::exists(public_path('db-parts/pictures/'. $prev->part_pict))) {
                        File::delete(public_path('db-parts/pictures/'. $prev->part_pict));
                    }
                }

                $data = [
                    // 'part_id' => $request->part_id,
                    'parent_id' => $parent_id,
                    'type' => $request->type,
                    'reff' => $request->reff,
                    'cust_id' => $request->cust_id,
                    'part_no' => $request->part_no,
                    'part_name' => $request->part_name,
                    'part_pict' => $request->part_pict,
                    'part_vol' => $request->part_vol,
                    'qty_part_item' => $request->qty_part_item,
                    'gop_assy' => $request->gop_assy,
                    'gop_single' => $request->gop_single,
                    'purch_part' => $request->purch_part,
                    'spec' => $request->spec,
                    'ms_t' => $request->ms_t,
                    'ms_w' => $request->ms_w,
                    'ms_l' => $request->ms_l,
                    'ms_n_strip' => $request->ms_n_strip,
                    'ms_coil_pitch' => $request->ms_coil_pitch,
                    'part_weight' => $request->part_weight,
                    'vendor_name' => $request->vendor_name
                ];
                
                InputParts::where('id', $prev->id)->update($data);

                $now = InputParts::query()
                    ->select([
                        'id',
                        'parent_id',
                        'type',
                        'reff',
                        'cust_id',
                        'part_no',
                        'part_name',
                        'part_pict',
                        'part_vol',
                        'qty_part_item',
                        'gop_assy',
                        'gop_single',
                        'spec',
                        'ms_t',
                        'ms_w',
                        'ms_l',
                        'ms_n_strip',
                        'ms_coil_pitch',
                        'part_weight',
                        'vendor_name',
                        'spec_pict',
                    ])
                    ->where('id', $prev->id)
                    ->first();

                $cek = array_diff_assoc($prev->toArray(), $now->toArray());
                $note = $this->_createLogOnUpdate($cek);

                $log_note = '';
                if (!empty($note)) {
                    $i = 0;
                    $len = count($note);
                    foreach ($note as $val) {
                        if ($i == 0) {
                            $log_note .= $val;
                        } else if ($i == $len - 1) {
                            $log_note .= " & $val";
                        }else{
                            $log_note .= ", $val";
                        }

                        $i++;
                    }
                }

                DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
                    'id_part' => $prev->id,
                    'status' => 'EDIT',
                    'note' => $log_note,
                    'value' => (empty($cek)) ? '' : json_encode($cek),
                    'log_date' => Carbon::now(),
                    'log_by' => Auth::user()->FullName
                ]);

                return _Success('Updated successfully!', 201, $log_note);
            } catch (Exception $e) {
                return _Error('Failed to save, please check form again!', 401, $e->getMessage());
            }
        }
    }

    public function uploadTemp(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg|max:10000'
            ]);
            
            if ($files = $request->file('file')) {
                if ($request->old_file) {
                    if (File::exists(public_path('db-parts/temp/'. $request->old_file))) {
                        File::delete(public_path('db-parts/temp/'. $request->old_file));
                    }
                }
                $new_name = $this->randNum(25) . '.' . $files->getClientOriginalExtension();
                $files->move(public_path('db-parts/temp'), $new_name);

                return _Success('success', 200, $new_name);
            }
        }
        return _Success('message');
    }

    public function destroy($id, Request $request)
    {
        if ($request->ajax()) {
            try {
                InputParts::query()->where('id', $id)->update([
                    'is_active' => 0
                ]);

                DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
                    'id_part' => $id,
                    'status' => 'DELETE',
                    'note' => 'ITEM DELETED',
                    'log_date' => Carbon::now(),
                    'log_by' => Auth::user()->FullName
                ]);

                return _Success('Item has been deleted');
            } catch (Exception $e) {
                return _Error($e->getMessage());
            }
        }
    }

    public function trashToActive($id, Request $request)
    {
        if ($request->ajax()) {
            try {
                InputParts::query()->where('id', $id)->update([
                    'is_active' => 1
                ]);
                DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
                    'id_part' => $id,
                    'status' => 'ACTIVED',
                    'note' => 'ITEM ACTIVED',
                    'log_date' => Carbon::now(),
                    'log_by' => Auth::user()->FullName
                ]);
                return _Success('Part has been reactived');
            } catch (Exception $e) {
                return _Error($e->getMessage());
            }
        }
    }

    public function tableTrash(Request $request)
    {
        if ($request->ajax()) {
            $result = InputParts::query()->where('is_active', 0)->get();
            return DataTables::of($result)
            ->addColumn('action', function($result){
                return view('tms.db_parts.input_parts.button.btnTableIndex', [
                    'data' => $result,
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function revision(Request $request)
    {
        DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
            'id_part' => $request->id,
            'status' => 'REVISION',
            'note' => $request->note,
            'value' => $request->fields,
            'log_date' => Carbon::now(),
            'log_by' => Auth::user()->FullName
        ]);
        return _Success('Data saved successfully');
    }

    public function headerTools(Request $request)
    {
        switch ($request->type) {
            case 'delete_temp':
                if ($request->old_file) {
                    if (File::exists(public_path('db-parts/temp/'. $request->old_file))) {
                        File::delete(public_path('db-parts/temp/'. $request->old_file));
                    }
                }
                return _Success('OK');
                break;
            
            case "customer":
                return DataTables::of($this->customer())->make(true);
                break;

            case "item_parent":
                $result = InputParts::query()->where('is_active', 1)->get();
                return DataTables::of($result)
                ->make(true);
                break;

            case "logs":
                $result = DB::table('db_tbs.dbparts_item_part_tbl_log')->where('id_part', $request->id)->orderBy('log_date', 'DESC')->get();
                return DataTables::of($result)
                ->addColumn('date', function($result){
                    return date('d/m/Y', strtotime($result->log_date));
                })
                ->addColumn('time', function($result){
                    return date('H:i:s', strtotime($result->log_date));
                })
                ->rawColumns(['date', 'time'])
                ->make(true);
                break;
            case "fields":
                return _Success(null, 200, [
                    'type' => 'PART TYPE',
                    'reff' => 'REFF',
                    'cust_id' => 'CUSTOMER',
                    'part_no' => 'PART NO',
                    'part_name' => 'PART NAME',
                    'part_pict' => 'PART PICTURE',
                    'part_vol' => 'PART VOLUME',
                    'qty_part_item' => 'QTY PART ITEM',
                    'gop_assy' => 'Good of Part ASSY',
                    'gop_single' => 'Good of Part Single',
                    'purch_part' => 'Purch Part',
                    'spec' => 'SPEC',
                    'ms_t' => 'PART TALL',
                    'ms_w' => 'PART WIDTH',
                    'ms_l' => 'PART LENGTH',
                    'ms_n_strip' => 'N Strip',
                    'ms_coil_pitch' => 'COIL PITCH',
                    'part_weight' => 'PART WIGHT',
                    'vendor_name' => 'COMPANY NAME'
                ]);
                break;
            default:
                # code...
                break;
        }
    }

    private function _createLogOnUpdate($arr)
    {
        if (!empty($arr)) {
            $note = 'Update ';
            $arr_note = [];
            foreach ($arr as $key => $val) {
                
                if ($key == 'spec') {
                    $note = 'CHG. MATERIAL SPEC';
                    if(!in_array($note, $arr_note, true)){
                        array_push($arr_note, $note);
                    }
                }elseif ($key == 'part_name') {
                    $note = 'CHG. PART NAME';
                    if(!in_array($note, $arr_note, true)){
                        array_push($arr_note, $note);
                    }
                }elseif ($key == 'ms_t' || $key == 'ms_w' || $key == 'ms_l' || $key == 'ms_n_strip' || $key == 'ms_coil_pitch') {
                    $note = 'UPDATE MATERIAL SIZE';
                    if(!in_array($note, $arr_note, true)){
                        array_push($arr_note, $note);
                    }
                }elseif ($key == 'part_no') {
                    $note = 'UPDATE PART NO';
                    if(!in_array($note, $arr_note, true)){
                        array_push($arr_note, $note);
                    }
                }elseif ($key == 'type') {
                    $note = 'CHG. TYPE/MODEL CODE';
                    if(!in_array($note, $arr_note, true)){
                        array_push($arr_note, $note);
                    }
                }elseif ($key == 'part_pict') {
                    $note = 'CHG. PART PICTURE';
                    if(!in_array($note, $arr_note, true)){
                        array_push($arr_note, $note);
                    }
                }elseif ($key == 'part_weight') {
                    $note = 'UPDATE PART WEIGHT';
                    if(!in_array($note, $arr_note, true)){
                        array_push($arr_note, $note);
                    }
                }elseif ($key == 'gop_assy' || $key == 'gop_single' || $key == 'qty_part_item' || $key == 'part_vol' || $key == 'purch_part') {
                    $note = 'CORRECTION STUCTURE OF PART';
                    if(!in_array($note, $arr_note, true)){
                        array_push($arr_note, $note);
                    }
                }

            }
            return $arr_note;
        }
    }
}