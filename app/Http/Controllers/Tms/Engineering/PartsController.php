<?php

namespace App\Http\Controllers\TMS\Engineering;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use App\Models\Dbtbs\DB_parts\Parts;
use App\Models\Dbtbs\DB_parts\Projects;
use App\Models\Dbtbs\DB_parts\Revision;
use App\Models\Dbtbs\DB_parts\RevisionLogs;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class PartsController extends Controller
{
    use ToolsTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index($type)
    {
        if (!$type) {
            return redirect()->route('tms.db_parts');
        }

        $id_type = base64_decode($type);
        $projects = Projects::whereId($id_type)->first();

        if (!$projects) {
            return redirect()->route('tms.db_parts');
        }

        return view('tms.db_parts.parts.index');
    }

    public function dt(Request $request)
    {
        $id_type = base64_decode($request->type);
        $result = Parts::query()
        ->where('project_id', $id_type)
        ->where('is_active', 1)->get();

        return DataTables::of($result)
        ->addColumn('action', function($result){
            return view('tms.db_parts.parts.button.btnTableIndex', [
                'data' => $result,
            ]);
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function detail($id)
    {
        // $model = Parts::find($id);
        $model = Parts::leftJoin('ekanban.ekanban_customermaster as customer', 'customer.CustomerCode_eKanban', '=', 'db_tbs.dbparts_item_part_tbl.cust_id')
        // ->leftJoin('db_tbs.dbparts_item_part_tbl as part_parent', 'part_parent.parent_id', '=', 'db_tbs.dbparts_item_part_tbl.id')
        // ->leftJoin('db_tbs.dbparts_item_part_tbl as part_parent', function ($join){
        //     $join->on('part_parent.parent_id', '=', 'db_tbs.dbparts_item_part_tbl.id')
        //         ->whereNull('part_parent.parent_id');
        // })
        ->select([
            'db_tbs.dbparts_item_part_tbl.*',
            'customer.CustomerName as cust_name',
            // 'part_parent.parent_id as parent',
            // 'part_parent.part_no as parent_no',
            // 'part_parent.part_name as parent_name',
        ])
        ->where('db_tbs.dbparts_item_part_tbl.id', $id)
        // ->whereNull('part_parent.parent_id')
        ->first();
        if (!$model) {
            return _Success(0);
        }

        if (!is_null($model->parent_id)) {
            $parent = Parts::whereId($model->parent_id)
            ->select([
                'part_no as parent_partno',
                'part_name as parent_partname',
            ])
            ->first()->toArray();


            $model = array_merge($model->toArray(), $parent);
        }

        return _Success(1, 200, $model);
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_no' => 'required',
            'part_name' => 'required',
            'part_pict' => 'required',
        ]);

        try {
            File::move(public_path('db-parts/temp/' . $request->part_pict), public_path('db-parts/pictures/' . $request->part_pict));
            
            $model = new Parts;
            $model->project_id = base64_decode($request->type_id);
            $model->part_id = null;
            $model->parent_id = $request->parent_id;
            $model->type = $request->type;
            $model->reff = $request->reff;
            $model->cust_id = $request->cust_id;
            $model->part_no = $request->part_no;
            $model->part_name = $request->part_name;
            $model->part_pict = $request->part_pict;
            $model->part_vol = $request->part_vol;
            $model->qty_part_item = $request->qty_part_item;
            $model->gop_assy = $request->gop_assy;
            $model->gop_single = $request->gop_single;
            $model->purch_part = $request->purch_part;
            $model->spec = $request->spec;
            $model->ms_t = $request->ms_t;
            $model->ms_w = $request->ms_w;
            $model->ms_l = $request->ms_l;
            $model->ms_n_strip = $request->ms_n_strip;
            $model->ms_coil_pitch = $request->ms_coil_pitch;
            $model->part_weight = $request->part_weight;
            $model->vendor_name = $request->vendor_name;
            $model->spec_pict = null;
            $model->created_by = Auth::user()->FullName;
            $model->created_date = Carbon::now();
            $model->is_active = 1;

            if ($model->save()) {
                DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
                    'id_part' => $model->id,
                    'status' => 'ADD',
                    'note' => 'ADD NEW ITEM',
                    'log_date' => Carbon::now(),
                    'log_by' => Auth::user()->FullName
                ]);
            }

            return _Success('Data saved successfully');
        } catch (Exception $e) {
            return _Error($e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $prev = Parts::query()
                ->select(static::arr_select())
                ->where('id', $id)
                ->first();
            if ($request->part_pict !== $prev->part_pict) {
                if (File::exists(public_path('db-parts/temp/' . $request->part_pict))) {
                    File::move(public_path('db-parts/temp/' . $request->part_pict), public_path('db-parts/pictures/' . $request->part_pict));
                }
                if (File::exists(public_path('db-parts/pictures/'. $prev->part_pict))) {
                    File::delete(public_path('db-parts/pictures/'. $prev->part_pict));
                }
            }

            $model = Parts::find($id);
            $model->project_id = base64_decode($request->type_id);
            $model->part_id = null;
            $model->parent_id = $request->parent_id;
            $model->type = $request->type;
            $model->reff = $request->reff;
            $model->cust_id = $request->cust_id;
            $model->part_no = $request->part_no;
            $model->part_name = $request->part_name;
            $model->part_pict = $request->part_pict;
            $model->part_vol = $request->part_vol;
            $model->qty_part_item = $request->qty_part_item;
            $model->gop_assy = $request->gop_assy;
            $model->gop_single = $request->gop_single;
            $model->purch_part = $request->purch_part;
            $model->spec = $request->spec;
            $model->ms_t = $request->ms_t;
            $model->ms_w = $request->ms_w;
            $model->ms_l = $request->ms_l;
            $model->ms_n_strip = $request->ms_n_strip;
            $model->ms_coil_pitch = $request->ms_coil_pitch;
            $model->part_weight = $request->part_weight;
            $model->vendor_name = $request->vendor_name;
            $model->spec_pict = null;
            $model->created_by = Auth::user()->FullName;
            $model->created_date = Carbon::now();
            $model->is_active = 1;
            $model->save();

            $now = Parts::query()
                ->select(static::arr_select())
                ->where('id', $id)
                ->first();

            $old_data = array_diff_assoc($prev->toArray(), $now->toArray());
            $new_data = array_diff_assoc($now->toArray(), $prev->toArray());
            // $note = $this->_createLogOnUpdate($cek);
            DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
                'id_part' => $id,
                'status' => 'EDIT',
                'note' => null,
                'value' => (empty($old_data)) ? '' : json_encode($old_data),
                'log_date' => Carbon::now(),
                'log_by' => Auth::user()->FullName
            ]);
            $revisionNumber = 0;
            $revisionType = Revision::where('id_type', base64_decode($request->type_id))->lastNumber()->first();

            if (!empty($old_data) && !empty($new_data)) {
                if ($revisionType) {
                    if (is_null($revisionType->posted_at)) {
                        $revisionNumber = $revisionType->revision_number;
                    }else{
                        $revisionNumber = $revisionType->revision_number + 1;
                        Revision::create([
                            'revision_number' => $revisionNumber,
                            'id_type' => base64_decode($request->type_id)
                        ]);
                    }
                }else{
                    $revisionNumber = 1;
                    Revision::create([
                        'revision_number' => $revisionNumber,
                        'id_type' => base64_decode($request->type_id)
                    ]);
                }
                RevisionLogs::create([
                    'id_part' => $id,
                    'id_type' => base64_decode($request->type_id),
                    'revision_number' => $revisionNumber,
                    'type_revision' => 'PART',
                    'old_data' => (empty($old_data)) ? null : json_encode($old_data),
                    'new_data' => (empty($new_data)) ? null : json_encode($new_data),
                    'created_by' => Auth::user()->FullName,
                    'created_at' => Carbon::now()
                ]);
            }
            return _Success('Updated successfully!', 201, $old_data);

        } catch (Exception $e) {
            return _Error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = Parts::find($id);
        $model->is_active = 0;

        if ($model->save()) {
            DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
                'id_part' => $id,
                'status' => 'DELETE',
                'note' => 'ITEM DELETED',
                'log_date' => Carbon::now(),
                'log_by' => Auth::user()->FullName
            ]);

            return _Success('Item has been deleted');
        }
        return _Error('Error');
    }

    public function toActive($id)
    {
        $model = Parts::find($id);
        $model->is_active = 1;

        if ($model->save()) {
            DB::table('db_tbs.dbparts_item_part_tbl_log')->insert([
                'id_part' => $id,
                'status' => 'REACTIVED',
                'note' => 'ITEM DELETED',
                'log_date' => Carbon::now(),
                'log_by' => Auth::user()->FullName
            ]);

            return _Success('Item has been reactived');
        }
        return _Error('Error');
    }

    public function tableTrash(Request $request)
    {
        $id_type = base64_decode($request->type);
        $result = Parts::query()
        ->where('project_id', $id_type)
        ->where('is_active', 0)->get();

        return DataTables::of($result)
        ->addColumn('action', function($result){
            return view('tms.db_parts.parts.button.btnTableIndex', [
                'data' => $result,
            ]);
        })
        ->rawColumns(['action'])
        ->make(true);
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

    public function tools(Request $request)
    {
        if ($request->type) {
            $func = $request->type;
            if (!method_exists($this, $func)) {
                return _Error("Sorry, function $func does not exist.");
            }
            return static::$func($request);
        }
        return _Error("Sorry, params does not exist.");
    }

    static function modal_create($request)
    {
        if ($request->type_id) {
            $id_type = base64_decode($request->type_id);

            $model = Projects::details($id_type)->first();

            if (!$model) {
                return _Error('Project tidak ditemukan');
            }
            
            return _Success(1, 200, $model);
        }
        return _Error('Access denied');
    }

    static function item_parent($request)
    {
        $id_type = base64_decode($request->type_id);
        $result = Parts::query()
        ->where('project_id', $id_type)
        ->where('is_active', 1)->get();

        return DataTables::of($result)
        ->make(true);
    }

    static function delete_temp($request)
    {
        if ($request->old_file) {
            if (File::exists(public_path('db-parts/temp/'. $request->old_file))) {
                File::delete(public_path('db-parts/temp/'. $request->old_file));
            }
        }
        return _Success('OK');
    }

    static function logs($request)
    {
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
    }

    static function arr_select()
    {
        return [
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
        ];
    }
}