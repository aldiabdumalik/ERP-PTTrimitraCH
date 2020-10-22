<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Http\Request;

use App\Classes\ButtonBuilder As ButtonBuilder;

use App\Http\Controllers\RolePermissionController As RolePermissionControl;

use App\Models\role As role;
use App\Models\User_Role As UserRole;

class RoleController extends Controller
{
    // 1. Datatable Page
    public function index(){

        $ActionButton = '';

        $UserID     = Auth::user()->id;
        $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
        $RoleID     = $UserRole->role_id;

        $AddAccess  = RolePermissionControl::CheckPermission($RoleID, 'add_role');
        if($AddAccess){
            $ActionButton = ButtonBuilder::Build('MAIN', 'ADD', 'add-btn', 'ti-plus', 'Add New Role');
        }

        $data = [
            'ActionButton' => $ActionButton
        ];

        return view('admin.role.role')->with($data);
    }

    // 2. Get Role Detail
    public function detail($id){
       $role = role::find($id);
       return response()->json($role);
    }

    // 3. Add
    public function add(Request $request){
        $role = new role;
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $res = $role->save();
        if($res){
            $data = [ 
                'status'    => 1,
                'message'   => 'Success Added Role'
            ];
        } else {
            $data = [ 
                'status'    => 0,
                'message'   => 'Add Permission Failed. Please Contact the IT Dept.'
            ];
        }
        return $data;
    }

    // 4. Edit :: POST ::
    public function edit(Request $request){
        $role = role::find($request->input('id'));
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $res = $role->save();
        if($res){
            $data = [ 
                'status'    => 1,
                'message'   => 'Success Edited Role'
            ];
        } else {
            $data = [ 
                'status'    => 0,
                'message'   => 'Edit Role Failed. Please Contact the IT Dept.'
            ];
        }
        return $data;
    }

    // 5. Delete :: POST ::
    public function delete(Request $request){
        $id       = $request->input('id');
        $role     = role::find($id);
        $roleName = $role->name;
        $res      = role::where('id', $id)->delete();
        if($res){
            $data = [ 
               'status'    => 1,
                'message'   => 'Success Deleted '.$roleName
            ];
        } else {
            $data = [ 
                'status'    => 0,
                'message'   => 'Failed Delete '.$roleName
            ];
        }
        return response()->json($data);
    }



    
    /*
    *   ==========================================================
    *   Function to get user data for Datatables
    *   ==========================================================
    */
    
    public function getDatatables(){
        return  
            DataTables::of(role::all())
                ->addColumn('action', function ($data){

                    $ActionButton = '';

                    $UserID     = Auth::user()->id;
                    $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
                    $RoleID     = $UserRole->role_id;
                    
                    $RolePermissionAccess  = RolePermissionControl::CheckPermission($RoleID, 'view_permission_role');
                    if($RolePermissionAccess){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE-LINK', 'TEMPLATE', 'role-permission-btn', 'ti-key', 'Role Permission', route('admin.roles.permission', ['id' => $data->id]));
                    }

                    $RoleViewAccess = RolePermissionControl::CheckPermission($RoleID, 'detail_role');
                    if($RoleViewAccess){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'VIEW', 'role-view-btn', 'ti-eye', 'View', '#', "role-id='$data->id'");
                    }

                    $RoleEditAccess = RolePermissionControl::CheckPermission($RoleID, 'edit_role');
                    if($RoleViewAccess){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'EDIT', 'role-edit-btn', 'ti-pencil-alt', 'Edit', '#', "role-id='$data->id'");
                    }

                    $RoleDeleteAccess = RolePermissionControl::CheckPermission($RoleID, 'delete_role');
                    if($RoleViewAccess){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'DELETE', 'role-delete-btn', 'ti-trash', 'Delete', '#', "role-id='$data->id' role-name='$data->name'");
                    }
                    
                    return $ActionButton;
                })
                ->make(true);
    }

}
