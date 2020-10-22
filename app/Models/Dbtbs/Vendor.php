<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $connection = 'db_tbs';
    protected $table = 'vendor';
    public $timestamps = false;
}
