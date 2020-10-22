<?php

namespace App\Http\Controllers;

// Laravel Libraries
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Classes
use App\Classes\ButtonBuilder As ButtonBuilder;

// Controllers
use App\Http\Controllers\RolePermissionController As RolePermissionControl;

// Models
use App\Models\Module          As module;
use App\Models\role            As role;
use App\Models\Role_Permission As rolePermission;
use App\Models\User_Role       As UserRole;

class RolePermissionController extends Controller
{
    // 1. Datatable Page
    public function index($id){
        $ActionButton = '';

        $UserID     = Auth::user()->id;
        $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
        $RoleID     = $UserRole->role_id;

        $SaveAccess  = RolePermissionControl::CheckPermission($RoleID, 'save_permission_role');
        if($SaveAccess){
            $ActionButton = ButtonBuilder::Build('MAIN', 'SAVE', 'save-btn', 'ti-check', 'Save');
        }

        $role = role::find($id);
        $data = [
            'role'           => $role,
            'ActionButton'   => $ActionButton,
            'breadcrumbItem' => [
                'role'  => $role
            ]
        ];
        return  view('admin.role.role-permission')
                ->with($data);
    }

    public function get($roleId){
        $rolePermission = rolePermission::where('role_id', '=', $roleId)->get();
        $module = module::all();
        $output = [
            'rolePermission' => $rolePermission,
            'module'         => $module
        ];
        return response()->json($output);
    }

    public function save(Request $request, $roleId){
        $permission = $request->input('permission');
        $moduleID   = $request->input('moduleID');
        $addRes     = static::add($permission, $roleId);
        $deleteRes  = static::delete($permission, $roleId, $moduleID);
        $data = [
            'status'    => 1,
            'message'   => $addRes['message'].'. '.$deleteRes['message']
        ];
        return response()->json($data);
    }

    static function add($permissions, $roleId){
        $saveCount = 0;
        if($permissions !== null){
            for($i = 0; $i < count($permissions); $i++){
                $existedRolePermissions = rolePermission::where([
                    ['role_id', '=', $roleId],
                    ['permission_id', '=', $permissions[$i]['id']]
                ])->get();
                if($existedRolePermissions->count() <= 0){
                    $rolePermission = new rolePermission;
                    $rolePermission->role_id = $roleId;
                    $rolePermission->permission_id = $permissions[$i]['id'];
                    $save = $rolePermission->save();
                    if($save){
                        $saveCount = $saveCount + 1;
                    }
                }
            }
        }
        $data = [ 
            'status'    => 1,
            'message'   => 'Set '.$saveCount.' Permission(s)'
        ];
        return $data;
    }

    static function delete($permission, $roleId, $moduleID){
        $deleteCount = 0;
        $existedRolePermissions = rolePermission::join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->join('module_items', 'module_items.id', '=', 'permissions.module_item_id')
            ->where([
                ['role_permissions.role_id', '=', $roleId],
                ['module_items.module_id', '=', $moduleID]
            ])->get();
        foreach($existedRolePermissions as $existedRolePermission){
            if($permission !== null){
                $search = in_array($existedRolePermission->permission_id, array_column($permission, 'id'));
            } else {
                $search = false;
            }            
            if($search == false){
                $delete = rolePermission::where([
                    ['role_id', '=', $roleId],
                    ['permission_id', '=', $existedRolePermission->permission_id]
                ])->delete();  
                if($delete){
                    $deleteCount = $deleteCount + 1;
                }
            }
        }
        $data = [ 
            'status'    => 1,
            'message'   => 'Remove '.$deleteCount.' Permission(s)'
        ];
        return $data;
    }

    public static function CheckPermission($RoleID, $PermissionKey){
        $RolePermission = rolePermission::join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where([
                ['role_permissions.role_id', '=', $RoleID],
                ['permissions.key', '=', $PermissionKey]
            ])
            ->count();
        return $RolePermission;
    }
}
