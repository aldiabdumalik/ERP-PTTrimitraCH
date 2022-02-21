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
}
