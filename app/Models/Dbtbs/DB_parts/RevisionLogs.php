<?php

namespace App\Models\Dbtbs\DB_parts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RevisionLogs extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'dbparts_revision_tbl_log';
    protected $fillable = [
        'id',
        'id_part',
        'id_type',
        'revision_number',
        'type_revision',
        'old_data',
        'new_data',
        'created_by',
        'created_at',
    ];   
    protected $hidden = [];
   
    public $timestamps = false;
}
