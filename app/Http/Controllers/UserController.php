<?php

namespace App\Http\Controllers;

// Laravel Libraries
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Http\Request;

// Classes
use App\Classes\ButtonBuilder As ButtonBuilder;

// Controllers
use App\Http\Controllers\RolePermissionController As RolePermissionControl;

// Models
use App\Models\Ekanban_Usersetup As user;
use App\Models\Role              As role;
use App\Models\User_Role         As UserRole;

class UserController extends Controller
{
    // 1. Datatable Page
    public function index(){
        return view('admin.user.user');
    }

    // 2. Edit Page
    public function pageEdit($id){
        $user       = user::find($id);
        $userRole   = userRole::where('ekanban_user_id', '=', $id)->first();
        $role       = role::All();
        $breadcrumbItem =  ['user' => $user];
        $data = [
            'data'      => $user,
            'roles'     => $role,
            'userRole'  => $userRole
        ];
        return  view('admin.user.user-form')
                    ->with($data)
                    ->with('breadcrumbItem', $breadcrumbItem);
    }

    // 3. Edit Post
    public function edit(Request $request, $id){
        $role  = $request->input('role');
        $email = $request->input('email');
        
        $user  = user::find($id);
        $user->email = $email;
        $user->save();

        $userRoles = userRole::where('ekanban_user_id', '=', $id)->first();
        if($userRoles !== null){
            $userRole = userRole::find($userRoles->id);
            $userRole->role_id = $role;
        } else {
            $user = user::find($id);

            $userRole = new userRole;
            $userRole->ekanban_user_id = $id;
            $userRole->nik = $user->UserID;
            $userRole->role_id = $role;
        }
        $userRole->save();

        return redirect()
                ->back()
                ->with("success", "User Edited Successfully");
    }

    /*
    *   ==========================================================
    *   Function to get user data for Datatables
    *   ==========================================================
    */

    public function getDatatables(){
        $userID       = Auth::user()->id;
        $userRole     = UserRole::where('ekanban_user_id', $userID)->first();
        $roleID       = $userRole->role_id;
        $viewAccess   = RolePermissionControl::CheckPermission($roleID, 'view_users');
        $editAccess   = RolePermissionControl::CheckPermission($roleID, 'edit_users');
        $deleteAccess = RolePermissionControl::CheckPermission($roleID, 'delete_users');
        $groupAccess  = [
            'view'    => $viewAccess,
            'edit'    => $editAccess,
            'delete'  => $deleteAccess
        ];
        
        return  DataTables::of(user::all())
                ->addColumn('action', function ($data) use($groupAccess){
                    $ActionButton = '';

                    if($groupAccess['view']){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE-LINK', 'VIEW', 'user-view-btn', 'ti-eye', 'View', route('admin.users.view', ['id' => $data->id]));
                    }

                    if($groupAccess['edit']){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE-LINK', 'EDIT', 'module-edit-btn', 'ti-pencil-alt', 'Edit', route('admin.users.edit', ['id' => $data->id]));
                    }

                    if($groupAccess['delete']){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'DELETE', $data->id, 'ti-trash', 'Delete', '#', "name='$data->name'");
                    }

                    return $ActionButton;
                })
                ->make(true);
    }


}
