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
        'id_cust',
        'part_number',
        'part_name',
        'part_type',
        'plan',
        'ct',
        'route',
        'ton',
        'process',
        'production_process',
        'time',
        'plan_hour',
        'plan_1',
        'plan_2',
        'actual_1',
        'actual_2',
        'act_hour',
        'note',
        'apnormality',
        'action_plan',
        'status',
        'closed',
        'printed',
        'user',
        'date',
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
   
   public $timestamps = false;
}
