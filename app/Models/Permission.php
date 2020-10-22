<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'tms_web';
     
    protected $table = 'permissions';

    protected $fillable = [
       'id', 'module_item_id', 'name', 'key', 'controller', 'method', 'description', 'parent_id'
    ];

    // Relationship
    public function role_permissions(){
        return $this->hasMany('App\Models\Role_Permission', 'permission_id', 'id');
    }


    public static function getParent($select, $where = null){
        if($where !== null) {
            $result = static::select($select)
                        ->where($where)
                        ->where(array(['parent_id']))
                        ->get();
        } else {
            $result = static::select($select)
                        ->where(array(['parent_id']))
                        ->get();
        }
        return $result;
    }

    public static function isParent($id){
        $result =   static::select('id')
                    ->where(array(['parent_id', $id]))
                    ->count();
        return $result;
    }

    public static function getChildren($id, $select){
        $result =   static::select($select)
                    ->where(array(['parent_id', $id]))
                    ->get();
        return $result;
    }

}
