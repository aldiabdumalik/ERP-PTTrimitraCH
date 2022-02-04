<?php
namespace App\Http\Traits\TMS\Warehouse;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ToolsTrait {
    
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

    protected function dateConvertFrom($date, $from='Y-m-d', $to='d/m/Y')
    {
        return Carbon::createFromFormat($from, $date)->format($to);
    }

    protected function createGlobalLog($tbl, $data)
    {
        $log = DB::table($tbl)
            ->insert($data);
        return $log;
    }

    protected function customer()
    {
        $query = 
            DB::connection('ekanban')
            ->table('ekanban_customermaster')
            ->selectRaw('CustomerCode_eKanban as code, CustomerName as name, Contact as cont, Address1 as ad1, Address2 as ad2, Address3 as ad3, Address4 as ad4, GLAR as glcode')
            ->where('status_data', 'ACTIVE')
            ->get();
        return $query;
    }

    protected function sys_account(Request $request)
    {
        if (isset($request->number)) {
            $query = 
                DB::table('db_tbs.sys_account')
                    ->where('status', 'ACTIVE')
                    ->where('number', $request->number)
                    ->first();
        }else{
            $query = 
                DB::table('db_tbs.sys_account')
                    ->where('status', 'ACTIVE')
                    ->get();
        }
        return $query;
    }

    protected function items($id)
    {
        $query = 
            DB::connection('db_tbs')
                ->table('item')
                ->selectRaw('ITEMCODE as itemcode, PART_NO as part_no, DESCRIPT as descript, UNIT as unit, DESCRIPT1 as model')
                ->where('CUSTCODE', $id)
                ->get();
        return $query;
    }

    protected function item($itemcode)
    {
        $query = 
            DB::connection('db_tbs')
                ->table('item')
                ->selectRaw('ITEMCODE as itemcode, PART_NO as part_no, DESCRIPT as descript, UNIT as unit, DESCRIPT1 as model')
                ->where('ITEMCODE', $itemcode)
                ->first();
        return $query;
    }

    protected function unit()
    {
        $query = 
            DB::connection('db_tbs')
                ->table('unit')
                ->selectRaw('UNIT as unit, DESCRIPT as des')
                ->get();
        return $query;
    }

    protected function process_name()
    {
        return DB::table('oee.db_processname_tbl')
            ->select([
                'process_id', 'production_process', 'routing'
            ])
            ->where('status', 'ACTIVE')
            ->orderBy('production_process', 'ASC')
            ->get();
    }

    protected function process_detail($process_name)
    {
        return DB::table('oee.db_processdetailname_tbl')
            ->select(['process_detailname', 'production_process'])
            ->where(function ($on) use($process_name){
                $on->where('status', 'ACTIVE');
                $on->where('production_process', $process_name);
            })
            ->orderBy('production_process', 'ASC')
            ->get();
    }

    protected function addZeroes($num)
    {
        $res = explode('.', $num);
        if(count($res) == 1 || (strlen($res[1]) > 0)) {
            $num = number_format($num, 2, '.', "");
        }
        return $num;
    }

    protected function currency($angka) 
    {
        $exp = explode('.', $angka);
        $angka = $exp[0];
        $convert = number_format($angka, 0, '.', ',');
        $hasil = (empty($exp[1])) ? $convert : $convert .'.'. $exp[1];
        return $hasil;
    }

    protected function terbilang($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = $this->terbilang($nilai - 10). " Belas";
        } else if ($nilai < 100) {
            $temp = $this->terbilang($nilai/10)." Puluh". $this->terbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . $this->terbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->terbilang($nilai/100) . " Ratus" . $this->terbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . $this->terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->terbilang($nilai/1000) . " Ribu" . $this->terbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->terbilang($nilai/1000000) . " Juta" . $this->terbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->terbilang($nilai/1000000000) . " Milyar" . $this->terbilang(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->terbilang($nilai/1000000000000) . " Trilyun" . $this->terbilang(fmod($nilai,1000000000000));
        }     
        return $temp;
    }

    protected function processDetailCode($process_id)
    {
        $check = DB::table('db_tbs.dbparts_master_process_detail_tbl')
            ->select(DB::raw('max(process_detail_id) as code'))
            ->whereRaw('process_detail_id like ?', ["$process_id%"])
            ->first();
        $x = (int) substr($check->code, 3, 3);
        $x++;
        $code = $process_id . sprintf("%03s", $x);
        return $code;
    }

}