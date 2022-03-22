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

function rupiah($angka) 
{
	$exp = explode('.', $angka);
	$angka = $exp[0];
	$convert = number_format($angka, 0, '.', ',');
	$hasil = (empty($exp[1])) ? $convert : $convert .'.'. $exp[1];
	return $hasil;
}

function addZero($num)
{
	$res = explode('.', $num);
	if(count($res) == 1 || (strlen($res[1]) > 0)) {
		$num = number_format($num, 2, '.', "");
	}
	return $num;
}

function addZero4($num)
{
	$res = explode('.', $num);
	if(count($res) == 1 || (strlen($res[1]) > 0)) {
		$num = number_format($num, 4, '.', "");
	}
	return $num;
}

function addZeroFirst($num)
{
	$jml = strlen($num);
	$hasil = null;
	if ($jml == 1) {
		$hasil = "00$num";
	}elseif ($jml == 2) {
		$hasil = "0$num";
	}else{
		$hasil = "$num";
	}
	return $hasil;
}

function bulan($tanggal)
{
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

function bulan_saja($tanggal)
{
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
 
	return $bulan[ (int)$pecahkan[1] ];
}