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
use App\Models\Module           As module;
use App\Models\Module_Item      As moduleItem;
use App\Models\Permission       As permission;
use App\Models\Role_Permission  As rolePermission;
use App\Models\User_Role        As userRole;

class ModuleItemController extends Controller
{
        // 1. Module Item Builder Page
        public function index($id){
            // Action Button
            $ActionButton = '';
            $UserID     = Auth::user()->id;
            $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
            $RoleID     = $UserRole->role_id;
            $AddAccess  = RolePermissionControl::CheckPermission($RoleID, 'add_role');
            if($AddAccess){
                $ActionButton = ButtonBuilder::Build('MAIN', 'ADD', 'add-btn', 'ti-plus', 'Add New Module Item');
            }
            
            $module = module::find($id);
            $breadcrumbItem = [
                'module' => $module
            ];

            $data = [
                'module'         => $module,
                'breadcrumbItem' => $breadcrumbItem,
                'ActionButton'   => $ActionButton
            ];

            return view('admin.module.module-item')
                ->with($data);
        }

        // 2. Add New Module Item
        public function add(Request $request, $module_id){
            $lastOrder = moduleItem::where([
                    ['module_id', '=', $module_id],
                    ['parent_id']
                ])
                ->max('order');
            $moduleItem             = new moduleItem;
            $moduleItem->module_id  = $module_id;
            $moduleItem->title      = $request->input('title');
            $moduleItem->url        = $request->input('url');
            $moduleItem->icon_class = $request->input('icon');
            $moduleItem->order      = $lastOrder + 1;
            $res = $moduleItem->save();
            if($res){
                $data = [ 
                    'status'    => 1,
                    'message'   => 'Success Added Module Item'
                ];
            } else {
                $data = [ 
                    'status'    => 0,
                    'message'   => 'Add Module Item Failed. Please Contact the IT Dept.'
                ];
            }
            return response()->json($data);
        }

        // 2. Edit Existed Module Item
        static function edit(Request $request){
            $item_id = $request->input('id');
            $moduleItem = moduleItem::find($item_id);
            $moduleItem->title      = $request->input('title');
            $moduleItem->url        = $request->input('url');
            $moduleItem->icon_class = $request->input('icon');
            $res = $moduleItem->save();
            if($res){
                $data = [ 
                    'status'    => 1,
                    'message'   => 'Success Edited Module Item'
                ];
            } else {
                $data = [ 
                    'status'    => 0,
                    'message'   => 'Edit Module Item Failed. Please Contact the IT Dept.'
                ];
            }
            return $data;
        }

        // 3. Delete Data
        public function delete($id, $item_id){

            // Delete Role Permission related to Item
            $deleteRolePermission = rolePermission::where('permissions.module_item_id', $item_id)
                ->leftJoin('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                ->delete();

            // Delete Permission Related to Item
            $deletePermission = permission::where('module_item_id', $item_id)
                ->delete();

            // Find, Get Title, then Delete Item
            $moduleItem = moduleItem::find($item_id);
            $itemTitle  = $moduleItem->title;
            $deleteItem = moduleItem::where('id', $item_id)->delete();
            
            if($deleteItem){
                $data = [ 
                    'status'    => 1,
                    'message'   => 'Success Deleted '.$itemTitle
                 ];
            } else {
                $data = [ 
                    'status'    => 0,
                    'message'   => 'Failed Delete '.$itemTitle
                ];
            }
            return response()->json($data);
        }

        // 4. Get Detail
        public function detail($id, $item_id){
            $moduleItem = moduleItem::find($item_id);
            return response()->json($moduleItem);
        }

        // 5. Reorder
        public function reorder(Request $request, $id){
            $orderData = $request->input('data');
            for($i = 0; $i < count($orderData); $i++){
                $order = $i + 1;
                $moduleItem = moduleItem::find($orderData[$i]['id']);
                $moduleItem->parent_id = null;
                $moduleItem->order = $order;
                $moduleItem->save();
                if(isset($orderData[$i]['children'])){
                    $parentId = $orderData[$i]['id'];
                    static::reorderChildren($orderData[$i]['children'], $parentId);
                }
            }
            $data = [ 
                'status'    => 1,
                'message'   => 'Success Reordering Item'
            ];
            return $data;
        }

        static function reorderChildren($childrenData, $parentId){
            for($i = 0; $i < count($childrenData); $i++){
                $order = $i + 1;
                $moduleItem = moduleItem::find($childrenData[$i]['id']);
                $moduleItem->parent_id = $parentId;
                $moduleItem->order = $order;
                $moduleItem->save();
                if(isset($childrenData[$i]['children'])){
                    $parent = $childrenData[$i]['id'];
                    static::reorderChildren($childrenData[$i]['children'], $parent);
                }
            }
        }

        // Static:: Function

        static function getItem($moduleId = null, $select = null){
            if($moduleId !== null){
                if($select !== null){
                    $moduleItem = moduleItem::where('module_id', $moduleId)
                        ->select($select)
                        ->get();
                } else {
                    $moduleItem = moduleItem::where('module_id', $moduleId)
                        ->get();
                }
            } else {
                $moduleItem = moduleItem::all();
            }
            return $moduleItem;
        }

        static function generateTreejs(array $elements, $parentId = 0) {
            $branch = array();
            foreach ($elements as $element) {
                if ($element['parent_id'] == $parentId) {
                    $children = buildTree($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }
            return $branch;
        }

    /*
    *   ==========================================================
    *   Function to make permitted module item into object
    *   ==========================================================
    */
    static function getAllItems($moduleId){
        $moduleItem = moduleItem::getParent(array('id', 'title', 'url', 'icon_class', 'parent_id'), array(['module_id', '=', $moduleId]));
        for($i = 0; $i < $moduleItem->count(); $i++){
            $isParent = moduleItem::isParent($moduleItem[$i]->id);
            if($isParent >= 1){
                $moduleItem[$i]->isParent = 1;
                $moduleItem[$i] = static::getChildren($moduleItem[$i]);  
            } else {
                $moduleItem[$i]->isParent = 0;
            }    
        }
        return $moduleItem;
    }


    static function getPermittedItems(){
        $moduleUrl  = \Route::current()->getPrefix();
        $module     = module::get('id', array(['url', $moduleUrl]))->first();
        $moduleId   = $module->id;

        $userId     = Auth::user()->id;
        $userRole   = userRole::where('ekanban_user_id', $userId)->first();
        $roleId     = $userRole->role_id;

        $moduleItem = moduleItem::fromQuery('CALL sp_getPermittedItem(?,?)', array($moduleId, $roleId));

        for($i = 0; $i < $moduleItem->count(); $i++){
            $isParent = moduleItem::isParent($moduleItem[$i]->id);
            if($isParent >= 1){
                $moduleItem[$i]->isParent = 1;
                $moduleItem[$i] = static::getChildren($moduleItem[$i]);  
            } else {
                $moduleItem[$i]->isParent = 0;
            }    
        }
        return $moduleItem;
    }

    static function getChildren($moduleItem){
        $moduleItem->children = moduleItem::getChildren($moduleItem->id, array('id', 'title', 'url', 'icon_class', 'parent_id'));
        for($i = 0; $i < $moduleItem->children->count(); $i++){
            $isParent = moduleItem::isParent($moduleItem->children[$i]->id);
            if($isParent >= 1){
                $moduleItem->children[$i]->isParent = 1;
                $moduleItem->children[$i] = static::getChildren($moduleItem->children[$i]);
            } else {
                $moduleItem->children[$i]->isParent = 0;
            } 
        }
        return $moduleItem;
    }

    /*
    *   ==========================================================
    *   Function to make nestable
    *   ==========================================================
    */

    private static $nestableHtml;

    static function generateNestable($id){
        // Get data for checking permission        
        $UserID            = Auth::user()->id;
        $UserRole          = UserRole::where('ekanban_user_id', $UserID)->first();
        $RoleID            = $UserRole->role_id;
        $PermissionAccess  = RolePermissionControl::CheckPermission($RoleID, 'view_permission_item_modules');
        $EditAccess        = RolePermissionControl::CheckPermission($RoleID, 'edit_item_modules');
        $DeleteAccess      = RolePermissionControl::CheckPermission($RoleID, 'delete_item_modules');
        $zipAccess         = [
            'permission'   => $PermissionAccess,
            'edit'         => $EditAccess,
            'delete'       => $DeleteAccess
        ];
        $item    = static::getAllItems($id);
        $prefix  = module::get('url', array(['id', $id]))->first();
        self::$nestableHtml = self::$nestableHtml."\t \t <ol class='dd-list'> \r\n";
        for($i = 0; $i < $item->count(); $i++){
            self::$nestableHtml = self::$nestableHtml."\t \t <li class='dd-item dd3-item' data-id='".$item[$i]->id."'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd-handle dd3-handle'><i class='ti-move'></i></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd3-content'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t <div class='row'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col auto-margin'><h6>".$item[$i]->title." &nbsp; &nbsp; <small class='text-muted'>".$item[$i]->url."</small></h6></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col-auto'> \r\n";
            if($PermissionAccess){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <a class='btn btn-flat btn-xs btn-warning permission' href='".route('admin.modules.item.permission', ['id' => $id, 'item_id' => $item[$i]->id])."'><i class='ti-direction-alt'></i> Permission</a> &nbsp;";
            }
            if($EditAccess){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-info edit' item-id='".$item[$i]->id."' name='".$item[$i]->title."'><i class='ti-pencil-alt'></i> Edit</button> &nbsp;";
            }
            if($DeleteAccess){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-danger delete' item-id='".$item[$i]->id."' name='".$item[$i]->title."'><i class='ti-trash'></i> Delete</button>";
            }
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t </div> \r\n";
            if($item[$i]->isParent >= 1){
                static::generateNestableChildren($item[$i], $id, $zipAccess);
            }
            self::$nestableHtml = self::$nestableHtml."\t \t </li> \r\n";
        }
        self::$nestableHtml = self::$nestableHtml."\t \t </ol>";

        return response()->json(self::$nestableHtml);
    }

    static function generateNestableChildren($item, $id, $zipAccess = null){
        $prefix  = module::get('url', array(['id', $id]))->first();
        self::$nestableHtml = self::$nestableHtml."\t \t <ol class='dd-list'> \r\n";
        for($i = 0; $i < $item->children->count(); $i++){
            self::$nestableHtml = self::$nestableHtml."\t \t \t <li class='dd-item dd3-item' data-id='".$item->children[$i]->id."'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd-handle dd3-handle'><i class='ti-move'></i></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd3-content'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t <div class='row'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col auto-margin'><h6>".$item->children[$i]->title." &nbsp; &nbsp; <small class='text-muted'>".$item->children[$i]->url."</small></h6></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col-auto'> \r\n";
            if($zipAccess['permission']){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <a class='btn btn-flat btn-xs btn-warning permission' href='".route('admin.modules.item.permission', ['id' => $id, 'item_id' => $item->children[$i]->id])."'><i class='ti-direction-alt'></i> Permission</a> &nbsp;";
            }
            if($zipAccess['edit']){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-info edit' item-id='".$item->children[$i]->id."' name='".$item->children[$i]->title."'><i class='ti-pencil-alt'></i> Edit</button> &nbsp;";
            }
            if($zipAccess['delete']){
                self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-danger delete' item-id='".$item->children[$i]->id."' name='".$item->children[$i]->title."'><i class='ti-trash'></i> Delete</button>";
            }
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t </div> \r\n";
            if($item->children[$i]->isParent >= 1){    
                static::generateNestableChildren($item->children[$i], $id, $zipAccess);
            }
            self::$nestableHtml = self::$nestableHtml."\t \t \t </li> \r\n";
        }
        self::$nestableHtml = self::$nestableHtml."\t \t </ol> \r\n";
    }

}
