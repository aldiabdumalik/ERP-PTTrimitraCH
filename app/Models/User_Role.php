<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Role extends Model
{
    protected $connection = 'tms_web';
     
    protected $table = 'users';

    protected $fillable = [
       'id', 'ekanban_user_id', 'nik', 'role_id',
    ];

    public $timestamps = false;
}
