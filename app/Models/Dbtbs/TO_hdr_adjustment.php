<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;

class TO_hdr_adjustment extends Model
{
    protected $connection = 'db_tbs';
     
    protected $table = 'to_hdr_adjustment';

    protected $fillable = [
       'id', 'to_no', 'ref_no', 'period', 'vperiod', 'written',
       'printed', 'voided', 'posted', 'finished', 'remark', 'total',
       'wh_from', 'wh_to', 'branch_to', 'branch', 'xprinted', 'operator'
    ];

    public $timestamps = false;
}
