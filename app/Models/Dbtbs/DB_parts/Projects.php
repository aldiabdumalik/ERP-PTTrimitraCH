<?php

namespace App\Models\Dbtbs\DB_parts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Projects extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'dbparts_projects_tbl';
    protected $fillable = [
        'id',
        'cust_id',
        'type',
        'reff',
        'created_by',
        'created_at',
        'deleted_at',
    ];   
    protected $hidden = [];
   
    public $timestamps = false;

    public function scopeCustomer($query, $customer)
    {
        $query = $query->leftJoin('ekanban.ekanban_customermaster as customer', 'customer.CustomerCode_eKanban', '=', 'db_tbs.dbparts_projects_tbl.cust_id')
                    ->where('customer.CustomerCode_eKanban', $customer);
        return $query;
    }

    public function scopeCheckDeleted($query, $deleted)
    {
        if ($deleted == 1) {
            return $query->whereNotNull('deleted_at');
        }

        return $query->whereNull('deleted_at');
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeDetails($query, $id)
    {
        return $query->leftJoin('ekanban.ekanban_customermaster as customer', 'customer.CustomerCode_eKanban', '=', 'db_tbs.dbparts_projects_tbl.cust_id')
                    ->where('db_tbs.dbparts_projects_tbl.id', $id)
                    ->select(['db_tbs.dbparts_projects_tbl.*', 'customer.CustomerCode_eKanban as custcode', 'customer.CustomerName as custname']);

    }
}
