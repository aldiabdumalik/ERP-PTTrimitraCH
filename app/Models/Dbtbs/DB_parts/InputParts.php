<?php

namespace App\Models\Dbtbs\DB_parts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class InputParts extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'dbparts_item_part_tbl';
    protected $fillable = [
        'id',
        'part_id',
        'parent_id',
        'type',
        'reff',
        'cust_id',
        'part_no',
        'part_name',
        'part_pict',
        'part_vol',
        'qty_part_item',
        'gop_assy',
        'gop_single',
        'spec',
        'ms_t',
        'ms_w',
        'ms_l',
        'ms_n_strip',
        'ms_coil_pitch',
        'part_weight',
        'vendor_name',
        'spec_pict',
        'created_by',
        'created_date',
        'is_active'
    ];
    protected $hidden = [];
   
    public $timestamps = false;
}
