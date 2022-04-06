<?php

namespace App\Models\Dbtbs\DB_parts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProductionCode extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'dbparts_productioncode_tbl';
    protected $fillable = [
        'id',
        'id_part',
        'id_process',
        'id_detail_process',
        'cust_id',
        'production_code',
        'part_no',
        'process_x',
        'part_name',
        'part_type',
        'process_sequence_1',
        'process_sequence_2',
        'ct_second',
        'tool_parts',
        'tonage',
        'production_line',
        'company_name',
        'created_by',
        'created_at',
    ];   
    protected $hidden = [];
   
    public $timestamps = false;

    public function scopePartId($query, $part_id)
    {
        return $query->where('id_part', $part_id);
    }

    public function scopeDetail($query)
    {
        return $query->leftJoin('db_tbs.dbparts_item_part_tbl as tbl_part', 'tbl_part.id', '=', 'db_tbs.dbparts_productioncode_tbl.id_part')
            ->leftJoin('db_tbs.dbparts_master_process_tbl as tbl_process', 'tbl_process.process_id', '=', 'db_tbs.dbparts_productioncode_tbl.id_process')
            ->leftJoin('db_tbs.dbparts_master_process_detail_tbl as tbl_dprocess', 'tbl_dprocess.process_detail_id', '=', 'db_tbs.dbparts_productioncode_tbl.id_detail_process');
    }

    public function part()
    {
        return $this->belongsTo(Parts::class);
    }
}
