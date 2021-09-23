<?php

namespace App\Models\Dbtbs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CustInvoice extends Model
{
    use Notifiable;
    protected $connection = 'db_tbs';
    protected $table = 'entry_custinvoice_tbl';

    protected $hidden = [];

    public $timestamps = false;
}
