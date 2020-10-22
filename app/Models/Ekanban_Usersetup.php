<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Ekanban_Usersetup extends Authenticatable
{
    use Notifiable;

    protected $connection = 'ekanban';
    protected $table = 'ekanban_usersetup';

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'id', 'userid', 'fullname', 'password',
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [
       'password',
   ];
   
   public $timestamps = false;

}
