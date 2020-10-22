<?php

namespace App\Models\dbtbs;

use Illuminate\Database\Eloquent\Model;

class TO_Dtl_Adjustment extends Model
{
    protected $connection = 'db_tbs';
     
    protected $table = 'to_dtl_adjustment';

    protected $fillable = [
       'id', 'to_no', 'itemcode', 'part_no', 'descript', 'unit',
       'quantity', 'cost', 'fac_unit', 'fac_qty', 'factor', 'total', 'status'
    ];

    public $timestamps = false;
}
