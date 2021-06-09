<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ClaimEntry extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'entry_cl_tbl';

    protected $fillable = [
        'id',
        'cl_no',
        'ref_no',
        'po_no',
        'rr_no',
        'period',
        'written',
        'printed',
        'date_do',
        'date_rg',
        'voided',
        'closed',
        'cust_code',
        'do_addr',
        'contact',
        'company',
        'addr1',
        'addr2',
        'addr3',
        'addr4',
        'tot_qty',
        'unit',
        'remark',
        'xprinted',
        'branch',
        'warehouse',
        'cl_date',
        'cl_time',
        'itemcode',
        'part_no',
        'descript',
        'price',
        'cost',
        'qty',
        'qty_rg',
        'fac_unit',
        'factor',
        'tmp_qty',
        'unit_item',
        'notes',
        'types',
        'groups',
        'branch_item',
        'warehouse_item',
        'operator',
        'creation_by',
        'creation_date',
        'update_by',
        'update_date',
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
   
   public $timestamps = false;
   
   
}
