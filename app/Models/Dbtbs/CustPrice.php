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
        'price_new',
        'active_date',
        'range_date',
        'created_by',
        'created_date',
        'updated_date',
        'updated_by',
        'posted_by',
        'posted_date',
        'printed_date',
        'is_active',
        'is_update',
        'is_stock',
        'is_so',
        'is_sso',
        'is_sj',
    ];
    protected $hidden = [];
    public $timestamps = false;
}
