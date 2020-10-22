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
use App\Models\Module       As module;
use App\Models\Module_Item  As moduleItem;
use App\Models\Permission   As permission;
use App\Models\User_Role    As UserRole;

class ModuleItemPermissionController extends Controller
{
    // 1. Datatable Page
    public function index($id, $item_id){

        // Action Button
        $ActionButton = '';
        $UserID     = Auth::user()->id;
        $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
        $RoleID     = $UserRole->role_id;
        $AddAccess  = RolePermissionControl::CheckPermission($RoleID, 'add_role');
        if($AddAccess){
            $ActionButton = ButtonBuilder::Build('MAIN', 'ADD', 'add-btn', 'ti-plus', 'Add New Permission');
        }

        $module = module::find($id);
        $moduleItem = moduleItem::find($item_id);
        $data = [
            'module'         => $module,
            'moduleItem'     => $moduleItem,
            'ActionButton'   => $ActionButton,
            'breadcrumbItem' => [
                'module'     => $module, 
                'moduleItem' => $moduleItem,
            ]
        ];
        return view('admin.module.module-item-permission')
            ->with($data);
    }

    // 2. Get Datatable
    public function getDatatable($id, $item_id){
        return  DataTables::of(permission::where('module_item_id', '=', $item_id)->get())
                ->addColumn('action', function ($data){
                    $viewBtn    = "<button class='btn btn-flat btn-warning' id='$data->id'><i class='ti-eye'></i> View</button> &nbsp;";
                    $editBtn    = "<button class='btn btn-flat btn-info edit' id='$data->id'><i class='ti-pencil-alt'></i> Edit</button> &nbsp;";
                    $deleteBtn  = "<button class='btn btn-flat btn-danger delete' id='$data->id' name='$data->key'><i class='ti-trash'></i> Delete</button>";
                    return $viewBtn.$editBtn.$deleteBtn;
                })
                ->make(true);
    }

    // 3. Get Select2
    public function getSelect2(Request $request, $module_id, $item_id){
        $search = $request->search;
        if($search == ''){
            $permissions = permission::orderby('name', 'asc')
                ->select('id', 'name', 'key')
                ->where('module_item_id', '=', $item_id)
                ->get();
        } else {
            $permissions = permission::orderby('name', 'asc')
                ->select('id', 'name', 'key')
                ->where('module_item_id', '=', $item_id)
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('key', 'like', '%'.$search.'%')
                ->get();
        }
        $response = array();
        foreach($permissions as $permission){
            $response[] = array(
                'id'    => $permission->id,
                'text'  => $permission->name.' :: '.$permission->key
            );
        }
        return response()->json($response);
    }

    // 4. Detail
    public function detail($module_id, $item_id, $permission_id){
        $permission = permission::find($permission_id);
        return response()->json($permission);
    }

    // 3. Add :: POST ::
    public function add(Request $request){
        $permission = new permission;
        $permission->module_item_id = $request->input('module_item_id');
        $permission->name = $request->input('name');
        $permission->key = $request->input('key');
        $permission->controller = $request->input('controller');
        $permission->method = $request->input('method');
        $permission->description = $request->input('description');
        $res = $permission->save();
        if($res){
            $data = [ 
                'status'    => 1,
                'message'   => 'Success Added Permission'
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
        $permission = permission::find($request->input('id'));
        $permission->module_item_id = $request->input('module_item_id');
        $permission->name = $request->input('name');
        $permission->key = $request->input('key');
        $permission->controller = $request->input('controller');
        $permission->method = $request->input('method');
        $permission->description = $request->input('description');
        $res = $permission->save();
        if($res){
            $data = [ 
                'status'    => 1,
                'message'   => 'Success Edited Permission'
            ];
        } else {
            $data = [ 
                'status'    => 0,
                'message'   => 'Edit Permission Failed. Please Contact the IT Dept.'
            ];
        }
        return $data;
    }

    // 5. Delete :: POST ::
    public function delete(Request $request){
        $permission_id  = $request->input('id');
        $permission     = permission::find($permission_id);
        $permissionKey  = $permission->key;
        $res            = permission::where('id', $permission_id)->delete();
        if($res){
            $data = [ 
               'status'    => 1,
                'message'   => 'Success Deleted '.$permissionKey
            ];
        } else {
            $data = [ 
                'status'    => 0,
                'message'   => 'Failed Delete '.$permissionKey
            ];
        }
        return response()->json($data);
    }

    // 6. Reorder :: POST ::
    public function reorder(Request $request){
        $orderData = $request->input('data');
        for($i = 0; $i < count($orderData); $i++){
            $permission = permission::find($orderData[$i]['id']);
            $permission->parent_id = null;
            $permission->save();
            if(isset($orderData[$i]['children'])){
                $parentId = $orderData[$i]['id'];
                static::reorderChildren($orderData[$i]['children'], $parentId);
            }
        }
        $data = [ 
            'status'    => 1,
            'message'   => 'Success Reordering Permission'
        ];
        return $data;
    }

    static function reorderChildren($childrenData, $parentId){
        for($i = 0; $i < count($childrenData); $i++){
            $permission = permission::find($childrenData[$i]['id']);
            $permission->parent_id = $parentId;
            $permission->save();
            if(isset($childrenData[$i]['children'])){
                $parent = $childrenData[$i]['id'];
                static::reorderChildren($childrenData[$i]['children'], $parent);
            }
        }
    }

    /*
    *   ==========================================================
    *   Function to make permitted module item into object
    *   ==========================================================
    */
    static function getAllItems($moduleItemId){
        $permission = permission::getParent(array('id', 'name', 'key', 'controller', 'method'), array(['module_item_id', '=', $moduleItemId]));
        for($i = 0; $i < $permission->count(); $i++){
            $isParent = permission::isParent($permission[$i]->id);
            if($isParent >= 1){
                $permission[$i]->isParent = 1;
                $permission[$i] = static::getChildren($permission[$i]);  
            } else {
                $permission[$i]->isParent = 0;
            }    
        }
        return $permission;
    }

    static function getChildren($permission){
        $permission->children = permission::getChildren($permission->id, array('id', 'name', 'key', 'controller', 'method'));
        for($i = 0; $i < $permission->children->count(); $i++){
            $isParent = permission::isParent($permission->children[$i]->id);
            if($isParent >= 1){
                $permission->children[$i]->isParent = 1;
                $permission->children[$i] = static::getChildren($permission->children[$i]);
            } else {
                $permission->children[$i]->isParent = 0;
            } 
        }
        return $permission;
    }

    /*
    *   ==========================================================
    *   Function to make nestable
    *   ==========================================================
    */

    private static $nestableHtml;

    static function getNestable($module_id, $item_id){
        // Get data for checking permission        
        $UserID         = Auth::user()->id;
        $UserRole       = UserRole::where('ekanban_user_id', $UserID)->first();
        $RoleID         = $UserRole->role_id;
        $viewAccess     = RolePermissionControl::CheckPermission($RoleID, 'detail_permission_item_modules');
        $editAccess     = RolePermissionControl::CheckPermission($RoleID, 'edit_permission_item_modules');
        $deleteAccess   = RolePermissionControl::CheckPermission($RoleID, 'delete_permission_item_modules');
        $zipAccess      = [
            'view'      => $viewAccess,
            'edit'      => $editAccess,
            'delete'    => $deleteAccess
        ];
        $item    = static::getAllItems($item_id);
        self::$nestableHtml = self::$nestableHtml."\t \t <ol class='dd-list'> \r\n";
        for($i = 0; $i < $item->count(); $i++){
            self::$nestableHtml = self::$nestableHtml."\t \t <li class='dd-item dd3-item' data-id='".$item[$i]->id."'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd-handle dd3-handle'><i class='ti-move'></i></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd3-content'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t <div class='row'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col auto-margin'><h6>".$item[$i]->name." &nbsp; &nbsp; <small class='text-muted'>".$item[$i]->key."</small></h6></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col-auto'> \r\n";
            if($viewAccess){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-warning view' id='".$item[$i]->id."' name='".$item[$i]->name."'><i class='ti-eye'></i> View</button> &nbsp;";
            }
            if($editAccess){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-info edit' id='".$item[$i]->id."' name='".$item[$i]->name."'><i class='ti-pencil-alt'></i> Edit</button> &nbsp;";
            }
            if($deleteAccess){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-danger delete' id='".$item[$i]->id."' name='".$item[$i]->name."'><i class='ti-trash'></i> Delete</button>";
            }
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t </div> \r\n";
            if($item[$i]->isParent >= 1){
                static::getNestableChildren($item[$i], $item_id, $zipAccess);
            }
            self::$nestableHtml = self::$nestableHtml."\t \t </li> \r\n";
        }
        self::$nestableHtml = self::$nestableHtml."\t \t </ol>";

        return response()->json(self::$nestableHtml);
    }

    static function getNestableChildren($item, $id, $zipAccess){
        self::$nestableHtml = self::$nestableHtml."\t \t <ol class='dd-list'> \r\n";
        for($i = 0; $i < $item->children->count(); $i++){
            self::$nestableHtml = self::$nestableHtml."\t \t \t <li class='dd-item dd3-item' data-id='".$item->children[$i]->id."'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd-handle dd3-handle'><i class='ti-move'></i></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd3-content'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t <div class='row'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col auto-margin'><h6>".$item->children[$i]->name." &nbsp; &nbsp; <small class='text-muted'>".$item->children[$i]->key."</small></h6></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col-auto'> \r\n";
            if($zipAccess['view']){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-warning view' id='".$item->children[$i]->id."' name='".$item->children[$i]->name."'><i class='ti-eye'></i> View</button> &nbsp;";
            }
            if($zipAccess['edit']){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-info edit' id='".$item->children[$i]->id."' name='".$item->children[$i]->name."'><i class='ti-pencil-alt'></i> Edit</button> &nbsp;";
            }
            if($zipAccess['delete']){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-danger delete' id='".$item->children[$i]->id."' name='".$item->children[$i]->name."'><i class='ti-trash'></i> Delete</button>";
            }
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t </div> \r\n";
            if($item->children[$i]->isParent >= 1){    
                static::getNestableChildren($item->children[$i], $id, $zipAccess);
            }
            self::$nestableHtml = self::$nestableHtml."\t \t \t </li> \r\n";
        }
        self::$nestableHtml = self::$nestableHtml."\t \t </ol> \r\n";
    }

    /*
    *   ==========================================================
    *   Function to make JStree
    *   ==========================================================
    */

    private static $jstreeHtml;

    static function getJstree($item_id){
        self::$jstreeHtml = '';
        $item    = static::getAllItems($item_id);
        if(!$item->isEmpty()){
            self::$jstreeHtml = self::$jstreeHtml."\t <ul> \r\n";
            for($i = 0; $i < $item->count(); $i++){
                $currentCount = $i;
                $lastCount = $item->count() -1;
                if($currentCount == $lastCount) { $marginClass = 'margin-bottom-20'; } else { $marginClass = null; }
                self::$jstreeHtml = self::$jstreeHtml."\t \t <li id='".$item[$i]->id."' class='".$marginClass."'>".$item[$i]->name." &nbsp; &nbsp; <small class='text-muted'>".$item[$i]->key."</small> \r\n";
                if($item[$i]->isParent >= 1){
                    static::getJstreeChildren($item[$i], $item_id);
                }
                self::$jstreeHtml = self::$jstreeHtml."\t \t </li> \r\n";
            }
            self::$jstreeHtml = self::$jstreeHtml."\t \t </ul>";
        }
        return self::$jstreeHtml;
    }

    static function getJstreeChildren($item, $id){
        self::$jstreeHtml = self::$jstreeHtml."\t <ul> \r\n";
        for($i = 0; $i < $item->children->count(); $i++){
            self::$jstreeHtml = self::$jstreeHtml."\t \t <li id='".$item->children[$i]->id."'>".$item->children[$i]->name." &nbsp; &nbsp; <small class='text-muted'>".$item->children[$i]->key."</small> \r\n";
            if($item->children[$i]->isParent >= 1){    
                static::getJstreeChildren($item->children[$i], $id);
            }
            self::$jstreeHtml = self::$jstreeHtml."\t \t </li> \r\n";
        }
        self::$jstreeHtml = self::$jstreeHtml."\t </ul> \r\n";
    }

}
