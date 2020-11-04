<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use DB;
class MtoEntry extends Model
{
       //Connect to db_tbs
       protected $connection = 'db_tbs';
       //Define table
       protected $table = 'entry_mto_tbl';
       protected $fillable = [ 
              'mto_no','fin_code','frm_code','descript','fac_unit','fac_qty','factor','unit',
              'quantity','qty_ng','cost','glinv','types','written','posted','printed','voided',
              'warehouse','branch','ip_type','ref_no','uid_export','period','vperiod','staff',
              'dept','remark','lbom','xprinted','operator'
       ];
       protected $primaryKey = 'id_mto';
       public $timestamps = false;


     
       public function getMtoNo()
       {
         
         $get_sys_number = DB::connection('db_tbs')
                        ->table('sys_number')
                        ->select('contents')
                        ->where('LABEL','=', 'MTO NUMBER')
                        ->first();
                                     
          $a = $get_sys_number->contents;
          $b = '16608576';
          if ($a == $b) {
              $cek_nomto = $get_sys_number->contents + 1;
              $cek_data_mto = MtoEntry::where('mto_no', $cek_nomto)->select('mto_no')->get();

              if ($cek_data_mto->isEmpty()) {
                  $mto_no = $cek_nomto;
                  return $mto_no;
              } else {
                  do {
                    $cek_nomto++;
                    $cek_data_mto = MtoEntry::where('mto_no', $cek_nomto)->select('mto_no')->get();
                  } while (!$cek_data_mto->isEmpty());
                  $mto_no = $cek_nomto;
                  return $mto_no;
              }
          }  else {
              $cek_nomto = $b;
              $cek_nomto .= '1';
              $cek_data_mto = MtoEntry::where('mto_no', $cek_nomto)->select('mto_no')->get();
              if ($cek_data_mto->isEmpty()){
                $mto_no = $cek_nomto;
                return $mto_no;
              } else {
                do{
                    $cek_nomto++;
                    $cek_data_mto = MtoEntry::where('mto_no', $cek_nomto)->select('mto_no')->get();          
                }while (!$cek_data_mto->isEmpty());
                $mto_no = $cek_nomto;
                return $mto_no;
            }
          }          
       }
}
