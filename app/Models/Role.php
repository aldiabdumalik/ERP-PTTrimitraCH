<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'tms_web';
     
    protected $table = 'roles';

    protected $fillable = [
       'id', 'name', 'description',
    ];

    public $timestamps = false;
}
