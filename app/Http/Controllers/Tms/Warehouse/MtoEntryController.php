<?php

namespace App\Http\Controllers\TMS\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dbtbs\MtoEntry;
class MtoEntryController extends Controller
{
    public function index(Request $request)
    {
      
        return view('tms.warehouse.mto-entry.index');
    }
}
