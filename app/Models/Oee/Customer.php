<?php

namespace App\Models\Oee;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use Notifiable;

    protected $connection = 'oee';
    protected $table = 'db_customername_tbl';

    protected $fillable = [
        'customer_id', 'customer_code', 'customer_name'
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
   
   public $timestamps = false;
}
