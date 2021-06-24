<?php
namespace App\Http\Traits\TMS\Warehouse;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Redirect;

trait DoEntryTrait {

    protected function _Error($message=null, $code=401, $content=null)
    {
        return response()->json([
            'status' => false,
            'content' => $content,
            'message' => $message
        ], $code);
    }

    protected function _Success($message=null, $code=200, $content=null)
    {
        return response()->json([
            'status' => true,
            'content' => $content,
            'message' => $message
        ], $code);
    }
}