<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ekanban_Usersetup As user;
use App\Models\Module As module;
use App\Models\Role As role;

class AdminController extends Controller
{
    public function dashboard(){
        $user   = user::all();
        $role   = role::all();
        $module = module::all();
        $data = [
            'countUser'   => $user->count(),
            'countRole'   => $role->count(),
            'countModule' => $module->count()
        ];
        return view('admin.index')->with($data);
    }


}
