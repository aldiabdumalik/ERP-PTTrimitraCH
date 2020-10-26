<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module_Item extends Model
{
    protected $connection = 'tms_web';   
    protected $table = 'module_items';

    

    protected $fillable = [
        'id', 'module_id', 'title', 'url', 'route', 'icon_class', 'order'
    ];

    // Relationship
    public function permissions(){
        return $this->hasMany('App\Models\Permission', 'module_item_id', 'id');
    }

    public function role_permissions(){
        return $this->hasManyThrough(
            'App\Models\Permission', 
            'App\Models\Role_Permission',
            'permission_id',
            'module_item_id',
            'id',
            'id'
        );
    }

    public static function get($select, $where){
        $result = static::select($select)
                  ->where($where)
                  ->orderBy('order');
        return $result;
    }

    public static function getParent($select, $where = null){
        if($where !== null) {
            $result = static::select($select)
                        ->where($where)
                        ->where(array(['parent_id']))
                        ->orderBy('order')
                        ->get();
        } else {
            $result = static::select($select)
                        ->where(array(['parent_id']))
                        ->orderBy('order')
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
                    ->orderBy('order')
                    ->get();
        return $result;
    }
}
