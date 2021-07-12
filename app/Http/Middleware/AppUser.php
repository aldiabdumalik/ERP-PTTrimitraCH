<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Route;

use App\Models\Ekanban_Usersetup as user;
use App\Models\User_Role as userRole;
use App\Models\Role_Permission As rolePermission;
use App\Models\Permission as permission;

class AppUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            $userId     = Auth::user()->id;
            $userRole   = userRole::where('ekanban_user_id', $userId)->first();
            
            if($userRole->count() >= 1){
                $roleId     = $userRole->role_id;
            } else {
                session(['url.intended' => url()->current()]);
                return redirect()->route('login');
            }
                
            $routeAction = Route::currentRouteAction();
            list($controllerPath, $method) = explode('@', $routeAction); // Method Name $method
            $splitRouteAction = explode('\\', $controllerPath);
            $controller = $splitRouteAction[count($splitRouteAction) -1]; // Controller Name $controller

            $permission = permission::join('role_permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role_id', $roleId)
                ->where('permissions.controller', $controller)
                ->where('permissions.method', $method)
                ->get();
            
            if($permission->count() >= 1){
            // If User's Role has access to this permission //
                return $next($request);
            } else {
            // If User's Role doesn't have access to this permission //
            
                // Check Parent
                $thisPermission = permission::select('id', 'parent_id', 'controller', 'method')->where('controller', $controller)->where('method', $method)->first();
                
                // If Parent Exists, check whether the user have access to this parent permission
                if($thisPermission->parent_id !== null){
                    $parentExists = TRUE;

                    // Loop until the highest parent permission
                    while($parentExists == TRUE) {
                        // Get This Permission's Parent Details
                        $parentPermission = permission::select('id', 'controller', 'method', 'parent_id')->where('id', $thisPermission->parent_id)->first();

                        // Check whether the user's role have access to this parent
                        $permission = permission::join('role_permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                            ->where('role_permissions.role_id', $roleId)
                            ->where('permissions.controller', $parentPermission->controller)
                            ->where('permissions.method', $parentPermission->method)
                            ->get();
                        
                        // If user's role has access to the Parent
                        if($permission->count() >= 1){
                            // Add Role Permission to this Child Automatically
                            $rolePermission = new rolePermission;
                            $rolePermission->role_id = $roleId;
                            $rolePermission->permission_id = $thisPermission->id;
                            $save = $rolePermission->save();            
                        }

                        // Check if this parent has parent again
                        $thisPermission = permission::select('id', 'parent_id', 'controller', 'method')->where('controller', $parentPermission->controller)->where('method', $parentPermission->method)->first();

                        // If this parent has parent, Repeat loop
                        if($thisPermission->parent_id !== null){
                            $parentExists = TRUE;
                        } else {
                            $parentExists = FALSE;
                        }
                    }

                    // Reload this verification process
                    return $this->handle($request, $next);
                }

                // Redirect Back
                return redirect()->back();
            }

            
        }
        session(['url.intended' => url()->current()]);
        return redirect()->route('login');
    }

}
