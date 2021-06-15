<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ClaimEntryRG extends Model
{
    use Notifiable;
    protected $connection = 'db_tbs';
    protected $table = 'entry_cl_rg';

    protected $fillable = [
        'id',
        'cl_no',
        'doc_no',
        'itemcode',
        'descript',
        'unit',
        'qty_rg',
        'remark',
        'creation_at',
        'creation_by'
    ];
    protected $hidden = [];

    public $timestamps = false;
}
