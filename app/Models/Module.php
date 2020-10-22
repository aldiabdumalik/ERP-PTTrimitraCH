<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Module extends Model
{
     protected $connection = 'tms_web';
     
     protected $table = 'modules';

     protected $fillable = [
        'id', 'name', 'url', 'is_shown'
     ];

     public static function get($select, $where){
          $result = static::select($select)
                    ->where($where);
          return $result;
     }


}
