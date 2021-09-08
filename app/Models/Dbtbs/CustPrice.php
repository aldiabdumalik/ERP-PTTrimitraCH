<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustPrice extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'entry_custprice_tbl';
    
    protected $fillable = [
        'id_price',
        'status',
        'cust_id',
        'item_code',
        'currency',
        'price',
        'active_date',
    ];
    protected $hidden = [];
    public $timestamps = false;
}
