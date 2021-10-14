<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CustInvoice extends Model
{
    use Notifiable;
    protected $connection = 'db_tbs';
    protected $table = 'entry_custinvoice_tbl';

    protected $fillable = [
        'id_inv',
        'inv_no',
        'inv_type',
        'do_no',
        'cust_id',
        'combine_id',
        'cust_type',
        'do_addr',
        'cust_contact',
        'ref_no',
        'pref_tax',
        'tax_no',
        'periode',
        'due_date',
        'branch',
        'warehouse',
        'valas',
        'rate',
        'amount_sub',
        'amount_dis',
        'amount_tax',
        'amount_cn',
        'amount_dn',
        'amount_pay',
        'amount_bal',
        'amount_exp',
        'amount_cos',
        'commission',
        'tax_rate',
        'term',
        'totline',
        'glar',
        'remark',
        'posted_date',
        'voided_date',
        'printed_date',
        'written_date',
        'written_by',
        'updated_date',
        'updated_by'
    ];

    protected $hidden = [];

    public $timestamps = false;
}
