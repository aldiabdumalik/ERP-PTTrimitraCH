<?php

namespace App\Models\Dbtbs\DB_parts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Parts extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'dbparts_item_part_tbl';
    protected $fillable = [
        'id',
        'project_id',
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
        'purch_part',
        'spec',
        'ms_t',
        'ms_w',
        'ms_l',
        'ms_n_strip',
        'ms_coil_pitch',
        'part_weight',
        'vendor_name',
        'spec_pict',
        'squence_process',
        'created_by',
        'created_date',
        'is_active'
    ];
    protected $hidden = [];
   
    public $timestamps = false;

    public function production()
    {
        return $this->hasMany(ProductionCode::class, 'id_part', 'id')
        
        ->leftJoin('db_tbs.dbparts_master_process_tbl as tbl_process', 'tbl_process.process_id', '=', 'db_tbs.dbparts_productioncode_tbl.id_process')
        ->leftJoin('db_tbs.dbparts_master_process_detail_tbl as tbl_dprocess', 'tbl_dprocess.process_detail_id', '=', 'db_tbs.dbparts_productioncode_tbl.id_detail_process')
        ->select([
            'db_tbs.dbparts_productioncode_tbl.*',
            'tbl_process.process_name',
            'tbl_dprocess.process_detail_name',
        ]);
    }
}
