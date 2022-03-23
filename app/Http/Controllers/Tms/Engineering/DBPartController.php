<?php

namespace App\Http\Controllers\TMS\Engineering;

use App\Http\Controllers\Controller;
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
                $revisionType = Revision::type($id)->lastNumber()->first();

                if (is_null($old_data) && is_null($new_data)) {
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

    public function tools(Request $request)
    {
        switch ($request->type) {
            case 'init':
                return static::init();
                break;
            case 'customer_enter':
                return static::customerEnter($request);
                break;

            case 'check_revision':
                return static::checkRevision($request);
                break;
            
            default:
                # code...
                break;
        }
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

    static function customerEnter($request)
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

    static function checkRevision($request)
    {
        if ($request->type_id) {
            $rev = Revision::type($request->type_id)->whereNotNull('posted_at')->lastNumber()->first();

            if ($rev) {
                return _Success(1, 200, $rev->revision_number);
            }

            return _Success(0, 200);
        }
        return _Error('Please check your params');
    }
}
