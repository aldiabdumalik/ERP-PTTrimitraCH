<?php

namespace App\Http\Controllers\TMS\DB_Parts;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MasterDetailProcessController extends Controller
{
    use ToolsTrait;

    function __construct() 
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        return view('tms.db_parts.master_process_detail.index');
    }

    public function tableIndex(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('db_tbs.dbparts_master_process_detail_tbl')
                ->leftJoin('db_tbs.dbparts_master_process_tbl', 'db_tbs.dbparts_master_process_detail_tbl.process_id', '=', 'db_tbs.dbparts_master_process_tbl.process_id')
                ->where('db_tbs.dbparts_master_process_detail_tbl.is_active', 1)
                ->get();
            return DataTables::of($query)
                ->addColumn('action', function($query){
                    return view('tms.db_parts.master_process_detail.button.btnTableIndex', [
                        'data' => $query,
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return redirect()->route('tms.db_parts.master.detail_process');
    }

    public function tableProcess(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('db_tbs.dbparts_master_process_tbl')->where('is_active', 1)->get();
            return DataTables::of($query)
                ->make(true);
        }
        return redirect()->route('tms.db_parts.master.detail_process');
    }

    public function detail($id)
    {
        $query = DB::table('db_tbs.dbparts_master_process_detail_tbl')
        ->leftJoin('db_tbs.dbparts_master_process_tbl', 'db_tbs.dbparts_master_process_detail_tbl.process_id', '=', 'db_tbs.dbparts_master_process_tbl.process_id')
        ->where('process_detail_id', $id)
        ->first();
        if (is_null($query)) {
            return _Success('Failed', 200);
        }

        return _Success('OK', 200, $query);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $code = $this->processDetailCode($request->process_id);
                $getId = DB::table('db_tbs.dbparts_master_process_detail_tbl')->insertGetId([
                    'process_id' => $request->process_id,
                    'process_detail_id' => $code,
                    'process_detail_name' => $request->process_detail,
                    'created_by' => Auth::user()->FullName,
                    'created_at' => Carbon::now()
                ]);

                if ($getId) {
                    DB::table('db_tbs.dbparts_master_process_detail_tbl_log')->insert([
                        'status' => 'ADD',
                        'process_id' => $request->process_id,
                        'process_detail_id' => $code,
                        'process_detail_name' => $request->process_detail,
                        'log_by' => Auth::user()->FullName,
                        'log_date' => Carbon::now()
                    ]);
                }
                return _Success('Saved sucessfully!', 201, $code);
            } catch (Exception $e) {
                return _Error('Failed to save!', 401, $e->getMessage());
            }
        }
    }

    public function update($id, Request $request)
    {
        if ($request->ajax()) {
            try {
                $update = DB::table('db_tbs.dbparts_master_process_detail_tbl')->where('process_detail_id', $id)
                ->update([
                    'process_id' => $request->process_id,
                    'process_detail_id' => $id,
                    'process_detail_name' => $request->process_detail,
                    'updated_by' => Auth::user()->FullName,
                    'updated_at' => Carbon::now()
                ]);

                $getId = DB::table('db_tbs.dbparts_master_process_detail_tbl')->where('process_detail_id', $id)->first();

                if ($update) {
                    DB::table('db_tbs.dbparts_master_process_detail_tbl_log')->insert([
                        'process_id' => $request->process_id,
                        'process_detail_id' => $getId->process_detail_id,
                        'process_detail_name' => $request->process_detail,
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
                $update = DB::table('db_tbs.dbparts_master_process_detail_tbl')->where('process_detail_id', $id)
                ->update([
                    'deleted_by' => Auth::user()->FullName,
                    'deleted_at' => Carbon::now(),
                    'is_active' => 0
                ]);

                $getId = DB::table('db_tbs.dbparts_master_process_detail_tbl')->where('process_detail_id', $id)->first();

                if ($update) {
                    DB::table('db_tbs.dbparts_master_process_detail_tbl_log')->insert([
                        'status' => 'DELETED',
                        'process_id' => $getId->process_id,
                        'process_detail_id' => $getId->process_detail_id,
                        'process_detail_name' => $getId->process_detail_name,
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
            $query = DB::table('db_tbs.dbparts_master_process_detail_tbl as process_detail')
            ->select([
                'process_detail.process_detail_id',
                'process_detail.process_detail_name',
                'process_detail.is_active',
                'process.process_id',
                'process.process_name',
            ])
            ->leftJoin('db_tbs.dbparts_master_process_tbl as process', 'process_detail.process_id', '=', 'process.process_id')
            ->where('process_detail.is_active', 0)
            ->get();
            return DataTables::of($query)
                ->addColumn('action', function($query){
                    return view('tms.db_parts.master_process_detail.button.btnTableIndex', [
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
            $update = DB::table('db_tbs.dbparts_master_process_detail_tbl')->where('process_detail_id', $id)
            ->update([
                'deleted_by' => null,
                'deleted_at' => null,
                'is_active' => 1
            ]);

            $getId = DB::table('db_tbs.dbparts_master_process_detail_tbl')->where('process_detail_id', $id)->first();
            if ($update) {
                DB::table('db_tbs.dbparts_master_process_detail_tbl_log')->insert([
                    'status' => 'ACTIVED',
                    'process_id' => $getId->process_id,
                    'process_detail_id' => $getId->process_detail_id,
                    'process_detail_name' => $getId->process_detail_name,
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
            $query = DB::table('db_tbs.dbparts_master_process_detail_tbl_log')->where('process_detail_id', $request->id)->get();
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
        return redirect()->route('tms.db_parts.master.detail_process');
    }
}
