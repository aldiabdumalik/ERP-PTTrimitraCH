<?php

namespace App\Http\Controllers\TMS\DB_Parts;

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
            $result = InputParts::where('is_active', 1)->get();
            return DataTables::of($result)
            ->make(true);
        }
    }

    public function detail()
    {
        # code...
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
                $new_name = rand() . '.' . $files->getClientOriginalExtension();
                $files->move(public_path('db-parts/temp'), $new_name);

                return _Success('success', 200, $new_name);
            }
        }
        return _Success('message');
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
            
            default:
                # code...
                break;
        }
    }
}