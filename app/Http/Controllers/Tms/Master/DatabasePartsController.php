<?php

namespace App\Http\Controllers\TMS\Master;

use App\Http\Controllers\Controller;
use App\Http\Traits\TMS\Warehouse\ToolsTrait;
use Illuminate\Http\Request;
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

    public function headerTools(Request $request)
    {
        switch ($request->type) {
            case 'customer':
                return DataTables::of($this->customer())->make(true);
                break;
            case 'unit':
                return DataTables::of($this->unit())->make(true);
                break;
            default:
                return $this->_Error('Methode Not Found');
        }
    }
}
