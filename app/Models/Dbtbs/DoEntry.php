<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DoEntry extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'entry_do_tbl';
    // protected $primaryKey = 'do_no';
    // public $incrementing = false;
    protected $fillable = [
        'id_do',
        'do_no',
        'row_no',
        'item_code',
        'quantity',
        'unit',
        'so_no',
        'sso_no',
        'ref_no',
        'po_no',
        'dn_no',
        'invoice',
        'period',
        'cust_id',
        'do_address',
        'cust_name',
        'source',
        'id_driver',
        'remark',
        'branch',
        'warehouse',
        'delivery_date',
        'drv_name',
        'direct_date',
        'printed_by',
        'printed_date',
        'posted_by',
        'posted_date',
        'finished_by',
        'finished_date',
        'voided_by',
        'voided_date',
        'revised_by',
        'revised_date',
        'revised_to',
        'created_by',
        'created_date',
        'update_by',
        'update_date',
        'do_trans',
        'invoice_date',
        'id_custprice'
    ];
    protected $hidden = [];
    public $timestamps = false;
}
