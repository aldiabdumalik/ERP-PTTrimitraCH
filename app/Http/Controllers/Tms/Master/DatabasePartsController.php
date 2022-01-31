<?php

namespace App\Http\Controllers\TMS\Master;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DatabasePartsController extends Controller
{
    use ToolsTrait;
    
    public function index()
    {
        return view('tms.master.db-parts.index');
    }

    public function index_tabel(Request $request)
    {
        return;
    }

    public function upload_temp(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg|max:10000'
            ]);
            
            if ($files = $request->file('file')) {
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
            case 'customer':
                return DataTables::of($this->customer())->make(true);
                break;
            case 'unit':
                return DataTables::of($this->unit())->make(true);
                break;
            case 'process':
                return _Success(null, 200, $this->process_name());
                break;
            case 'process_detail':
                if (!is_null($request->process)) {
                    return DataTables::of($this->process_detail($request->process))->make(true);
                }
                return _Error('Parameter is required!');
                break;
            default:
                return $this->_Error('Methode Not Found');
        }
    }
}
