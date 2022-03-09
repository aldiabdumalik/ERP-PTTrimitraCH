<?php

namespace App\Http\Controllers\TMS\Engineering;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MasterProcessController extends Controller
{
    use ToolsTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.db_parts.master_process.index');
    }

    public function tableIndex(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('db_tbs.dbparts_master_process_tbl')->where('is_active', 1)->get();
            return DataTables::of($query)
                ->addColumn('action', function($query){
                    return view('tms.db_parts.master_process.button.btnTableIndex', [
                        'data' => $query,
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return redirect()->route('tms.db_parts.master.process');
    }

    public function detail($id)
    {
        $query = DB::table('db_tbs.dbparts_master_process_tbl')->where('process_id', $id)->first();
        if (is_null($query)) {
            return _Success('Failed', 200);
        }

        return _Success('OK', 200, $query);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $check = DB::table('db_tbs.dbparts_master_process_tbl')->where('process_id', $request->process_id)->first();
                if (!is_null($check)) {
                    return _Error('Process ID is exist!');   
                }

                $getId = DB::table('db_tbs.dbparts_master_process_tbl')->insertGetId([
                    'process_id' => $request->process_id,
                    'itemcode_process_id' => $request->itemcode_process_id,
                    'process_name' => $request->process_name,
                    'routing' => $request->routing,
                    'created_by' => Auth::user()->FullName,
                    'created_at' => Carbon::now()
                ]);

                if ($getId) {
                    DB::table('db_tbs.dbparts_master_process_tbl_log')->insert([
                        'id_process' => $getId,
                        'status' => 'ADD',
                        'process_id' => $request->process_id,
                        'itemcode_process_id' => $request->itemcode_process_id,
                        'process_name' => $request->process_name,
                        'routing' => $request->routing,
                        'log_by' => Auth::user()->FullName,
                        'log_date' => Carbon::now()
                    ]);
                }
                return _Success('Saved sucessfully!', 201);
            } catch (Exception $e) {
                return _Error('Failed to save!', 401, $e->getMessage());
            }
        }
    }

    public function update($id, Request $request)
    {
        if ($request->ajax()) {
            try {
                $update = DB::table('db_tbs.dbparts_master_process_tbl')->where('process_id', $id)
                ->update([
                    'process_id' => $request->process_id,
                    'itemcode_process_id' => $request->itemcode_process_id,
                    'process_name' => $request->process_name,
                    'routing' => $request->routing,
                    'updated_by' => Auth::user()->FullName,
                    'updated_at' => Carbon::now()
                ]);

                $getId = DB::table('db_tbs.dbparts_master_process_tbl')->where('process_id', $id)->first();

                if ($update) {
                    DB::table('db_tbs.dbparts_master_process_tbl_log')->insert([
                        'id_process' => $getId->id,
                        'status' => 'EDIT',
                        'process_id' => $id,
                        'itemcode_process_id' => $request->itemcode_process_id,
                        'process_name' => $request->process_name,
                        'routing' => $request->routing,
                        'log_by' => Auth::user()->FullName,
                        'log_date' => Carbon::now()
                    ]);
                }
                return _Success('Updated sucessfully!', 201);
            } catch (Exception $e) {
                return _Error('Failed to update!', 401, $e->getMessage());
            }
        }
    }

    public function destroy($id, Request $request)
    {
        if ($request->ajax()) {
            try {
                $update = DB::table('db_tbs.dbparts_master_process_tbl')->where('process_id', $id)
                ->update([
                    'deleted_by' => Auth::user()->FullName,
                    'deleted_at' => Carbon::now(),
                    'is_active' => 0
                ]);

                $getId = DB::table('db_tbs.dbparts_master_process_tbl')->where('process_id', $id)->first();

                if ($update) {
                    DB::table('db_tbs.dbparts_master_process_tbl_log')->insert([
                        'id_process' => $getId->id,
                        'status' => 'DELETED',
                        'process_id' => $id,
                        'itemcode_process_id' => $getId->itemcode_process_id,
                        'process_name' => $getId->process_name,
                        'routing' => $getId->routing,
                        'log_by' => Auth::user()->FullName,
                        'log_date' => Carbon::now(),
                        'is_active' => 0
                    ]);
                }
                return _Success('Deleted sucessfully! you can see it in the trash', 201);
            } catch (Exception $e) {
                return _Error('Failed to delete!', 401, $e->getMessage());
            }
        }
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('db_tbs.dbparts_master_process_tbl')->where('is_active', 0)->get();
            return DataTables::of($query)
                ->addColumn('action', function($query){
                    return view('tms.db_parts.master_process.button.btnTableIndex', [
                        'data' => $query,
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return redirect()->route('tms.db_parts.master.process');
    }

    public function trashToActive($id, Request $request)
    {
        if ($request->ajax()) {
            $update = DB::table('db_tbs.dbparts_master_process_tbl')->where('process_id', $id)
            ->update([
                'deleted_by' => null,
                'deleted_at' => null,
                'is_active' => 1
            ]);

            $getId = DB::table('db_tbs.dbparts_master_process_tbl')->where('process_id', $id)->first();
            if ($update) {
                DB::table('db_tbs.dbparts_master_process_tbl_log')->insert([
                    'id_process' => $getId->id,
                    'status' => 'ACTIVED',
                    'process_id' => $id,
                    'itemcode_process_id' => $getId->itemcode_process_id,
                    'process_name' => $getId->process_name,
                    'routing' => $getId->routing,
                    'log_by' => Auth::user()->FullName,
                    'log_date' => Carbon::now(),
                    'is_active' => 1
                ]);
            }

            return _Success('OK');
        }
    }

    public function logs(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('db_tbs.dbparts_master_process_tbl_log')->where('process_id', $request->id)->get();
            return DataTables::of($query)
            ->addColumn('time', function($query){
                return date('H:i:s', strtotime($query->log_date));
            })
            ->addColumn('date', function($query){
                return date('d/m/Y', strtotime($query->log_date));
            })
            ->rawColumns(['time', 'date'])
            ->make(true);
        }
        return redirect()->route('tms.db_parts.master.process');
    }
}