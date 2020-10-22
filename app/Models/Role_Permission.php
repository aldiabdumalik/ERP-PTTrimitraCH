<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role_Permission extends Model
{
    protected $connection = 'tms_web';
     
    protected $table = 'role_permissions';

    protected $fillable = [
       'id', 'role_id', 'permission_id'
    ];

    public $timestamps = false;
}
