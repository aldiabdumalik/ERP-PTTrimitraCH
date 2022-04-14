<?php

namespace App\Http\Controllers\TMS\Engineering;

use App\Http\Controllers\Controller;
use App\Models\Dbtbs\DB_parts\Parts;
use App\Models\Dbtbs\DB_parts\Projects;
use App\Models\Dbtbs\DB_parts\Revision;
use App\Models\Dbtbs\DB_parts\RevisionLogs;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DBPartController extends Controller
{
    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $bycust = Projects::groupBy('cust_id')->get();
        $projects = Projects::count();
        $revisi = Revision::count();
        $countcustomer = ($bycust->isEmpty()) ? 0 : count($bycust);
        return view('tms.db_parts.dashboard.index', compact('projects', 'countcustomer', 'revisi'));
    }

    public function dtProjects(Request $request)
    {
        $customer = $request->cust_id;
        $deleted = $request->deleted;
        $model = Projects::query()
            ->customer($customer)
            ->checkDeleted($deleted)
            ->select(['db_tbs.dbparts_projects_tbl.*', 'customer.CustomerCode_eKanban as custcode', 'customer.CustomerName as custname'])
            ->get();
        
        return DataTables::of($model)
        ->addColumn('action', function($model){
            return view('tms.db_parts.dashboard.button.btnTableIndex', [
                'data' => $model,
            ]);
            return 'Action';
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);

        // return _Success($model);
    }

    public function detail($id)
    {
        $model = Projects::details($id)->first();

        if ($model) {
            return _Success(1, 200, $model);
        }

        return _Success(null);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cust_id' => 'required',
            'type' => 'required',
            'reff' => 'required',
        ]);

        $cek = Projects::type($request->type)->first();

        if ($cek) {
            return _Error('Type is exist!');
        }

        try {

            $execute = Projects::create([
                'cust_id' => $request->cust_id,
                'type' => $request->type,
                'reff' => $request->reff,
                'created_at' => Carbon::now(),
                'created_by' => Auth::user()->FullName,
            ]);

            if ($execute->id) {
                DB::table('db_tbs.dbparts_projects_tbl_log')
                ->insert([
                    'id_projects' => $execute->id,
                    'status' => 'ADD',
                    'note' => null,
                    'log_date' => Carbon::now(),
                    'log_by' => Auth::user()->FullName
                ]);
            }

            return _Success('Project saved successfully!');
        } catch (Exception $e) {
            return _Error('Something was wroong', 401, $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'cust_id' => 'required',
            'type' => 'required',
            'reff' => 'required',
        ]);

        
        $cek = Projects::type($request->type)->where('id', '<>', $id)->first();
        
        if ($cek) {
            return _Error('Type is exist!');
        }

        try {
            $prev = Projects::where('id', $id)->select(['cust_id', 'type', 'reff'])->first();
            $find = Projects::find($id);

            $find->cust_id = $request->cust_id;
            $find->type = $request->type;
            $find->reff = $request->reff;

            // $find->save();

            if ($find->save()) {
                $now = Projects::where('id', $id)->select(['cust_id', 'type', 'reff'])->first();
                $old_data = array_diff_assoc($prev->toArray(), $now->toArray());
                $new_data = array_diff_assoc($now->toArray(), $prev->toArray());

                DB::table('db_tbs.dbparts_projects_tbl_log')
                ->insert([
                    'id_projects' => $id,
                    'status' => 'EDIT',
                    'note' => null,
                    'log_date' => Carbon::now(),
                    'log_by' => Auth::user()->FullName
                ]);

                // Revision Session
                $revisionNumber = 0;
                $revisionType = Revision::where('id_type', $id)->lastNumber()->first();

                if (!empty($old_data) && !empty($new_data)) {
                    if ($revisionType) {
                        if (is_null($revisionType->posted_at)) {
                            $revisionNumber = $revisionType->revision_number;
                        }else{
                            $revisionNumber = $revisionType->revision_number + 1;
                            Revision::create([
                                'revision_number' => $revisionNumber,
                                'id_type' => $id
                            ]);
                        }
                    }else{
                        $revisionNumber = 1;
                        Revision::create([
                            'revision_number' => $revisionNumber,
                            'id_type' => $id
                        ]);
                    }
                    RevisionLogs::create([
                        'id_type' => $id,
                        'revision_number' => $revisionNumber,
                        'type_revision' => 'PROJECT',
                        'old_data' => (empty($old_data)) ? null : json_encode($old_data),
                        'new_data' => (empty($new_data)) ? null : json_encode($new_data),
                        'created_by' => Auth::user()->FullName,
                        'created_at' => Carbon::now()
                    ]);
                }

            }
            return _Success('Updated successfully and added to Revision!');
        } catch (Exception $e) {
            return _Error('Failed to update', 401, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $find = Projects::find($id);

        $find->deleted_at = Carbon::now();

        $find->save();

        DB::table('db_tbs.dbparts_projects_tbl_log')
        ->insert([
            'id_projects' => $id,
            'status' => 'NON ACTIVE',
            'note' => null,
            'log_date' => Carbon::now(),
            'log_by' => Auth::user()->FullName
        ]);

        return _Success('Data successfully non actived, you can see on view trash');
    }

    public function toActive($id)
    {
        $find = Projects::find($id);

        $find->deleted_at = null;

        $find->save();

        DB::table('db_tbs.dbparts_projects_tbl_log')
        ->insert([
            'id_projects' => $id,
            'status' => 'REACTIVE',
            'note' => null,
            'log_date' => Carbon::now(),
            'log_by' => Auth::user()->FullName
        ]);

        return _Success('Data successfully non actived, you can see on view trash');
    }

    public function postedRevision($id, Request $request)
    {
        $request->validate([
            'note' => 'required',
        ]);
        $model = Revision::where('id_type', $id)->lastNumber()->first();

        if (!$model) {
            return _Success('Belum ada revisi pada type/project ini!', 200, 3);
        }
        
        if (!is_null($model->posted_at)) {
            return _Success('Revisi pada type/project ini sudah dipost', 200, 3);
        }
        $revisionNumber = $model->revision_number;
        Revision::where(function ($on) use($id, $revisionNumber){
            $on->where('id_type', $id);
            $on->where('revision_number', $revisionNumber);
        })->update([
            'posted_at' => Carbon::now(),
            'posted_by' => Auth::user()->FullName,
            'note' => $request->note
        ]);

        return _Success("Type/Project ini berhasil di POST dengan jumlah revisi $revisionNumber");
    }

    public function revLogs($id)
    {
        $revLogs = RevisionLogs::where('id_type', $id)
        ->orderBy('created_at', 'ASC')
        ->get();

        if ($revLogs->isEmpty()) {
            return _Error('Revisi tidak di temukan');
        }

        $byLogType = $revLogs->groupBy('type_revision')->toArray();
        $arr_1 = [];
        foreach ($byLogType as $key => $val) {
            foreach($val as $v){
                if ($key=='PART') {
                    $pconvert = static::convertParts($v['id_part']);
                    foreach (json_decode($v['old_data']) as $oldKey => $old) {
                        foreach (json_decode($v['new_data']) as $newKey => $new) {
                            if ($oldKey == $newKey) {
                                $arr_1[$key][$v['id_part']][$oldKey] = [
                                    'type_log' => $key,
                                    'part_no' => $pconvert->part_no,
                                    'part_name' => $pconvert->part_name,
                                    'field' => $oldKey,
                                    'name' => static::convertFieldName($oldKey),
                                    'old' => $old,
                                    'new' => $new
                                ];
                                
                            }
                        }
                    }
                }else{
                    foreach (json_decode($v['old_data']) as $oldKey => $old) {
                        foreach (json_decode($v['new_data']) as $newKey => $new) {
                            if ($oldKey == $newKey) {
                                $arr_1[$key][$oldKey] = [
                                    'type_log' => $key,
                                    'part_no' => " ",
                                    'part_name' => " ",
                                    'field' => $oldKey,
                                    'name' => $oldKey,
                                    'old' => $old,
                                    'new' => $new
                                ];
                                
                            }
                        }
                    }
                }
            }
        }

        $arr_fix = [];

        foreach ($arr_1 as $key => $val) {
            if ($key == 'PART') {
                foreach ($val as $id => $valKey) {
                    foreach ($valKey as $field => $field_value) {
                        $arr_fix[] = $field_value;
                    }
                }
            }else{
                foreach ($val as $field => $field_value) {
                    $arr_fix[] = $field_value;
                }
            }
        }
        // print_r($arr_fix);die;
        return DataTables::of($arr_fix)
        ->addColumn('group', function ($arr_fix){
            return $arr_fix['type_log'] . "|" . $arr_fix['part_no'] . "|" . $arr_fix['part_name'];
        })
        ->addIndexColumn()
        ->skipPaging()
        ->toJson();
        // return _Success(1, 200, $arr_fix);
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

    static function init()
    {
        $bycust = Projects::groupBy('cust_id')->get();
        $arr = [
            'projects' => Projects::count(),
            'revisi' => Revision::count(),
            'countcustomer' => ($bycust->isEmpty()) ? 0 : count($bycust)
        ];

        return _Success(1, 200, $arr);
    }

    static function customer_enter($request)
    {
        if (isset($request->cust_id)) {
            $customer = DB::connection('ekanban')
            ->table('ekanban_customermaster')
            ->selectRaw('CustomerCode_eKanban as cuscode, CustomerName as custname')
            ->where('CustomerCode_eKanban', $request->cust_id)
            ->first();

            if ($customer) {
                return _Success(null, 200, $customer);
            }
            return _Error('Customer not found');
        }
        return _Error('Please check your params');
    }

    static function check_revision($request)
    {
        if ($request->type_id) {
            $rev = Revision::whereId($request->type_id)->whereNotNull('posted_at')->lastNumber()->first();

            if ($rev) {
                return _Success(1, 200, $rev->revision_number);
            }

            return _Success(0, 200);
        }
        return _Error('Please check your params');
    }

    static function logs($request)
    {
        $result = DB::table('db_tbs.dbparts_projects_tbl_log')->where('id_projects', $request->id)->orderBy('log_date', 'DESC')->get();
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

    static function convertFieldName($key)
    {
        $arr = [
            'part_no' => 'Part No',
            'part_name' => 'Part Name',
            'part_pict' => 'Part Picture',
            'part_vol' => 'Volume',
            'qty_part_item' => 'Qty Part Item',
            'gop_assy' => 'Group of Parts Assy',
            'gop_single' => 'Group of Parts Single',
            'purch_part' => 'Purch Part',
            'spec' => 'Spec',
            'ms_t' => 'Tall',
            'ms_w' => 'Width',
            'ms_l' => 'Length',
            'ms_n_strip' => 'N/Strip',
            'ms_coil_pitch' => 'Coil/Pitch',
            'part_weight' => 'Weight',
            'vendor_name' => 'Plan mass prod. vendor name',
        ];

        return $arr[$key];
    }

    static function convertParts($id)
    {
        $model = Parts::whereId($id)->select(['part_no', 'part_name'])->first();

        return $model;
    }
}
