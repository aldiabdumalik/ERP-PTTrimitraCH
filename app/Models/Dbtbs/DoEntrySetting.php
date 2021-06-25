<?php

namespace App\Models\Dbtbs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DoEntrySetting extends Model
{
    protected $connection = 'db_tbs';
    protected $table = 'entry_do_tbl_setting';
    protected $fillable = [
        'id',
        'title',
        'data',
        'user',
        'status',
        'idx',
    ];
    protected $hidden = [];
    public $timestamps = false;
}
