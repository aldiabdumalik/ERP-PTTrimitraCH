<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;

class MtoEntry extends Model
{
       //Connect to db_tbs
       protected $connection = 'db_tbs';
       //Define table
       protected $table = 'entry_mto_tbl';
       protected $fillable = [];

       public $timestamps = false;
}
