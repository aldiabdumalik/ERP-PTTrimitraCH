<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Module As module;
use App\Models\User_Role as userRole;

class PortalController extends Controller
{
    public function index()
    {
        $userID     = Auth::user()->id;
        $userRole   = userRole::where('ekanban_user_id', $userID)->first();
        $roleID     = $userRole->role_id;

        $permittedModules = module::select('modules.name AS name', 'modules.url AS url', 'modules.icon AS icon')
            ->join('module_items', 'module_items.module_id', '=', 'modules.id')
            ->join('permissions', 'permissions.module_item_id', '=', 'module_items.id')
            ->join('role_permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role_id', '=', $roleID)
            ->where('modules.is_shown', '>=', 1)
            ->groupBy('modules.id', 'modules.name', 'modules.url', 'modules.icon')
            ->get();
        
        return view('portal.index', ['modules' => $permittedModules]);
    }
}
