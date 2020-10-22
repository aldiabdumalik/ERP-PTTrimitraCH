<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    //Connect to db_tbs
    protected $connection = 'db_tbs';
    //Define table
    protected $table = 'item';

    public function scopeItem($query){
        $select = array('itemcode', 'part_no', 'descript', 'descript1', 'custcode',
                        'groups', 'types', 'state', 'inventory', 'formula', 'unit', 'factor', 'fac_unit');

        return $query = DB::connection('db_tbs')
                      ->table('item')
                      ->select($select);
     }
}
