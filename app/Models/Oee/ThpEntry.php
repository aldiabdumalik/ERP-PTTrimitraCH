<?php

namespace App\Models\Oee;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ThpEntry extends Model
{
    use Notifiable;

    protected $connection = 'oee';
    protected $table = 'entry_thp_tbl';

    protected $fillable = [
        'id_thp', 
        'production_code', 
        'customer_code',
        'item_code',
        'part_number',
        'part_name',
        'part_type',
        'production_process',
        'route',
        'process_sequence_1',
        'process_sequence_2',
        'ct',
        'plan',
        'ton',
        'time',
        'plan_hour',
        'thp_qty',
        'lhp_qty',
        'thp_remark',
        'act_hour',
        'note',
        'apnormality',
        'action_plan',
        'status',
        'thp_date',
        'closed',
        'printed',
        'user',
        'thp_written',
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
   
   public $timestamps = false;
}
