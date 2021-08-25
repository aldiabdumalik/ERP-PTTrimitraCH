<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function convertDate($date, $from='d/m/Y', $to='Y-m-d')
{
    return Carbon::createFromFormat($from, $date)->format($to);
}

function _Success($message=null, $code=200, $content=null)
{
    return response()->json([
        'status' => true,
        'content' => $content,
        'message' => $message
    ], $code);
}

function _Error($message=null, $code=401, $content=null)
{
    return response()->json([
        'status' => false,
        'content' => $content,
        'message' => $message
    ], $code);
}

function createLog($tbl, $data=[])
{
    $log = DB::table($tbl)
        ->insert($data);
    return $log;
}

function FullName()
{
    return Auth::user()->FullName;
}