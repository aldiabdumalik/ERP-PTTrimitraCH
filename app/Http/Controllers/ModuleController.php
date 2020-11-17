<?php

namespace App\Http\Controllers;

// Laravel Libraries
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;

// Classes
use App\Classes\ButtonBuilder As ButtonBuilder;

// Controllers
use App\Http\Controllers\RolePermissionController As RolePermissionControl;

// Models
use App\Models\Module As module;
use App\Models\Module_Item As moduleItem;
use App\Models\User_Role As userRole;
use App\Models\Role_Permission As rolePermission;

class ModuleController extends Controller
{
    // 1. Datatable Page
    public function index(){
        $ActionButton = '';

        $UserID     = Auth::user()->id;
        $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
        $RoleID     = $UserRole->role_id;
        $AddAccess  = RolePermissionControl::CheckPermission($RoleID, 'add_modules');
        if($AddAccess){
            $ActionButton = ButtonBuilder::Build('MAIN-LINK', 'ADD', 'add-btn', 'ti-plus', 'Add New Module', route('admin.modules.add'));
        }

        $data = [
            'ActionButton' => $ActionButton
        ];

        return view('admin.module.module')->with($data);
    }

    // 2. Redirect to Add Page and Add Function
    public function add(){
        return view('admin.module.module-form');
    }

    public function post(Request $request){
        $this->validate($request, [
            'name'    => 'required',
            'url'     => 'required',
            'icon'    => 'required',
        ]);

        $duplicate = module::where('name', '=', $request->input('name'))->count();

        if($duplicate >= 1){
            // If duplicate Exists
            
            return redirect()
            ->back()
            ->with("failed", "Module name already exists!");
        } else {
        
            // Get file extension
            $ext    = $request->file('icon')->getClientOriginalExtension();

            // Filename to store
            $fileNameToStore = $request->input('name').'-icon.'.$ext;

            // Upload icon to public
            Storage::disk('module_icons')->put($fileNameToStore, file_get_contents($request->file('icon')));
            
            // Save data to database
            $module = new module();
            $module->name = $request->input('name');
            $module->url  = $request->input('url');
            $module->icon = $fileNameToStore;
            $module->save();
            
            return redirect()
                ->back()
                ->with("success", "Module Created Successfully");
        }
    }

    //  3. Redirect to Edit Page and Edit Function
    public function edit($id){
        $module = module::find($id);
        $breadcrumbItem = ['module' => $module];
        return view('admin.module.module-form')
            ->with('data', $module)
            ->with('breadcrumbItem', $breadcrumbItem);
    }

    public function postEdit(Request $request, $id){
        $this->validate($request, [
            'name'    => 'required',
            'url'     => 'required'
        ]);

        $duplicate = module::where('name', '=', $request->input('name'), 'and')
            ->where('id', '<>', $id)
            ->count();

        if($duplicate >= 1){
            // If duplicate Exists

            return redirect()
            ->back()
            ->with("failed", "Module name already exists!");
        } else {

            $existedModule = module::where('id', '=', $id)->first();
            $existedIconName = $existedModule->icon;

            if($request->hasFile('icon')){
                // Delete icon image
                Storage::disk('module_icons')->delete($existedIconName);
                
                // Get file extension
                $ext    = $request->file('icon')->getClientOriginalExtension();

                // Filename to store
                $fileNameToStore = $request->input('name').'-icon.'.$ext;

                // Upload new icon to public
                Storage::disk('module_icons')->put($fileNameToStore, file_get_contents($request->file('icon')));
            } else {
                // Rename icon image file
                $getExt = explode('.', $existedIconName);
                $ext    = $getExt[count($getExt)-1];
                $fileNameToStore = $request->input('name').'-icon.'.$ext;
                if($existedIconName !== $fileNameToStore){ 
                    Storage::disk('module_icons')->rename($existedIconName, $fileNameToStore);
                }
            }
                        
            // Edit data to database
            $module = module::find($id);
            $module->name = $request->input('name');
            $module->url  = $request->input('url');
            $module->icon = $fileNameToStore;
            $module->save();
            
            return redirect()
                ->back()
                ->with("success", "Module Edited Successfully");
        }
    }

    //  4. Delete Module
    public function delete($id){
        // Delete icon
        $existedModule = module::where('id', '=', $id)->first();
        $existedIconName = $existedModule->icon;
        Storage::disk('module_icons')->delete($existedIconName);
        
        // Delete data in database
        $res = module::where('id', $id)->delete();
        if($res){
            $data = [ 'status' => 1 ];
        } else {
            $data = [ 'status' => 0 ];
        }
        return response()->json($data);
    }

    static function getAll($select = null){
        if($select == null){
            $module = module::all();
        } else {
            $module = module::select($select)->get();
        }
        return $module;
    }

    /*
    *   ==========================================================
    *   Function to get module data for Datatables
    *   ==========================================================
    */

    public function getDatatables(){
        return  DataTables::of(module::all())
                ->addColumn('action', function ($data){
                    $ActionButton = '';

                    $UserID     = Auth::user()->id;
                    $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
                    $RoleID     = $UserRole->role_id;

                    $ModuleItemAccess  = RolePermissionControl::CheckPermission($RoleID, 'view_item_modules');
                    if($ModuleItemAccess){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE-LINK', 'TEMPLATE', 'module-item-btn', 'ti-menu-alt', 'Module Items', route('admin.modules.item', ['id' => $data->id]));
                    }

                    $ModuleEditAccess = RolePermissionControl::CheckPermission($RoleID, 'edit_modules');
                    if($ModuleEditAccess){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE-LINK', 'EDIT', 'module-edit-btn', 'ti-pencil-alt', 'Edit', route('admin.modules.edit', ['id' => $data->id]));
                    }

                    $ModuleDeleteAccess = RolePermissionControl::CheckPermission($RoleID, 'delete_modules');
                    if($ModuleDeleteAccess){
                        $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'DELETE', $data->id, 'ti-trash', 'Delete', '#', "name='$data->name'");
                    }

                    return $ActionButton;
                })
                ->make(true);
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

        $moduleItems = moduleItem::fromQuery('CALL sp_getPermittedItem(?,?)', array($moduleId, $roleId));

        // Insert all module item results to Array
        for($i = 0; $i < $moduleItems->count(); $i++){
            $isPermittedParent = static::IsPermittedParent($moduleItems[$i]->id, $moduleItems);
            $arrModuleItems[] = [
                'id'         => $moduleItems[$i]->id,
                'title'      => $moduleItems[$i]->title,
                'url'        => $moduleItems[$i]->url,
                'icon_class' => $moduleItems[$i]->icon_class,
                'parent_id'  => $moduleItems[$i]->parent_id,
                'order'      => $moduleItems[$i]->order,
                'isParent'   => $isPermittedParent
            ];
        }
        
        // Insert Children
        for($i = 0; $i < count($arrModuleItems); $i++){
            if($arrModuleItems[$i]['parent_id'] !== null){
                $arrIndex = array_search($arrModuleItems[$i]['parent_id'], array_column($arrModuleItems, 'id'));
                $arrModuleItems[$arrIndex]['children'][] = $arrModuleItems[$i];
                array_splice($arrModuleItems, $i, 1);
                $i--;
            }
        }
        
        // for($i = 0; $i < $moduleItems->count(); $i++){
        //     $isPermittedParent = static::IsPermittedParent($moduleItems[$i]->id, $moduleItems);
        //     if($moduleItems[$i]->parent_id == null){
        //         $arrModuleItems[] = [
        //             'id'         => $moduleItems[$i]->id,
        //             'title'      => $moduleItems[$i]->title,
        //             'url'        => $moduleItems[$i]->url,
        //             'icon_class' => $moduleItems[$i]->icon_class,
        //             'parent_id'  => $moduleItems[$i]->parent_id,
        //             'order'      => $moduleItems[$i]->order,
        //             'isParent'   => $isPermittedParent
        //         ];
        //     } else {
        //         $arrIndex = array_search($moduleItems[$i]->parent_id, array_column($arrModuleItems, 'id'));
        //         $arrModuleItems[$arrIndex]['children'][] = [
        //                 'id'         => $moduleItems[$i]->id,
        //                 'title'      => $moduleItems[$i]->title,
        //                 'url'        => $moduleItems[$i]->url,
        //                 'icon_class' => $moduleItems[$i]->icon_class,
        //                 'parent_id'  => $moduleItems[$i]->parent_id,
        //                 'order'      => $moduleItems[$i]->order,
        //                 'isParent'   => $isPermittedParent 
        //         ];
        //     }
        // }
        // dd($arrModuleItems);
        return $arrModuleItems;
    }

    static function IsPermittedParent($id, $moduleItems = null){
        if($moduleItems == null){
            $moduleUrl  = \Route::current()->getPrefix();
            $module     = module::get('id', array(['url', $moduleUrl]))->first();
            $moduleId   = $module->id;

            $userId     = Auth::user()->id;
            $userRole   = userRole::where('ekanban_user_id', $userId)->first();
            $roleId     = $userRole->role_id;

            $moduleItems = moduleItem::fromQuery('CALL sp_getPermittedItem(?,?)', array($moduleId, $roleId));
        }
        foreach($moduleItems as $item){
            if($item->parent_id == $id){
                return 1;
            }
        }
        return 0;
    }

    static function getPermittedChildren($masterPermittedItem, $permittedModuleItem){
        for($i = 0; $i < $permittedModuleItem->count(); $i++){
            if($permittedModuleItem->parent_id = $masterPermittedItem->id){
                $masterPermittedItem->children = $permittedModuleItem[$i];    
            }
        }
        // dd($permittedModuleItem);
        for($i = 0; $i < $masterPermittedItem->children->count(); $i++){
            $isParent = moduleItem::isParent($masterPermittedItem->children[$i]->id);
            if($isParent >= 1){
                $masterPermittedItem->children[$i]->isParent = 1;
                $masterPermittedItem->children[$i] = static::getPermittedChildren($masterPermittedItem->children[$i]);
            } else {
                $masterPermittedItem->children[$i]->isParent = 0;
            } 
        }
        return $masterPermittedItem;
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
    *   Function to make permitted module object into html (Template: Srtdash)
    *   ==========================================================
    */

    private static $html    ;

    static function generateMenu(){
        $item       = static::getPermittedItems();
        $prefix     = \Route::current()->getPrefix();
        $currentUrl = "/".\Route::current()->uri;
        for($i = 0; $i < count($item); $i++){
            if($item[$i]['url'] == '/'){
                $itemUrl = $prefix;
            } else {
                $itemUrl = $prefix.$item[$i]['url'];
            }
            if($itemUrl == $currentUrl){
                $activeClass = "class='active active-menu'";
            } else {
                $activeClass = "";
            }
            self::$html = self::$html."\t <li $activeClass id='menu-".$item[$i]['id']."'> \r\n";            
            if($item[$i]['isParent'] >= 1){
                self::$html = self::$html."\t \t <a href='#' id='menu-aria-".$item[$i]['id']."' aria-expanded='true'> \r\n";
                self::$html = self::$html."\t \t \t <i class='".$item[$i]['icon_class']."'></i> \r\n";
                self::$html = self::$html."\t \t \t <span> ".$item[$i]['title']."</span> \r\n";
                self::$html = self::$html."\t \t </a> \r\n";
                static::generateMenuChildren($item[$i]);
            } else {
                self::$html = self::$html."\t \t <a href='".$prefix.$item[$i]['url']."'> \r\n";
                self::$html = self::$html."\t \t \t <i class='".$item[$i]['icon_class']."'></i>";
                self::$html = self::$html."\t \t \t <span> ".$item[$i]['title']."</span> \r\n";
                self::$html = self::$html."\t \t </a> \r\n";
            }
            self::$html = self::$html."\t </li>";
        }
        return self::$html;
    }

    static function generateMenuChildren($item){
        $prefix  = \Route::current()->getPrefix();
        $currentUrl = "/".\Route::current()->uri;
        self::$html = self::$html."\t \t <ul id='menu-ul-".$item['id']."' class='collapse'> \r\n";
        
        for($i = 0; $i < count($item['children']); $i++){
            if($item['children'][$i]['url'] == '/'){
                $itemUrl = $prefix;
            } else {
                $itemUrl = $prefix.$item['children'][$i]['url'];
            }
            if($itemUrl == $currentUrl){
                $activeClass = "class='active active-menu'";
            } else {
                $activeClass = "";
            }
            self::$html = self::$html."\t \t \t <li $activeClass id='menu-".$item['children'][$i]['id']."' parent-id='".$item['children'][$i]['parent_id']."'> \r\n";
            if($item['children'][$i]['isParent'] >= 1){
                self::$html = self::$html."\t \t \t \t <a href='".$prefix.$item['children'][$i]['url']."' id='menu-aria-".$item['children'][$i]['id']."' aria-expanded='true'>".$item['children'][$i]['title']."</a> \r\n";
                static::generateMenuChildren($item['children'][$i]);
            } else {
                self::$html = self::$html."\t \t \t \t <a href='".$prefix.$item['children'][$i]['url']."'>".$item['children'][$i]['title']."</a> \r\n";
            }
            self::$html = self::$html."\t \t \t </li> \r\n";
        }
        self::$html = self::$html."\t \t </ul> \r\n";
    }

    /*
    *   ==========================================================
    *   Function to make nestable
    *   ==========================================================
    */

    private static $nestableHtml;

    static function generateNestable($id){
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
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <a class='btn btn-flat btn-xs btn-warning permission' href='".route('admin.modules.item.permission', ['id' => $id, 'item_id' => $item[$i]->id])."'><i class='ti-direction-alt'></i> Permission</a> &nbsp;";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-info edit' item-id='".$item[$i]->id."' name='".$item[$i]->title."'><i class='ti-pencil-alt'></i> Edit</button> &nbsp;";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-danger delete' item-id='".$item[$i]->id."' name='".$item[$i]->title."'><i class='ti-trash'></i> Delete</button>";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t </div> \r\n";
            if($item[$i]->isParent >= 1){
                static::generateNestableChildren($item[$i], $id);
            }
            self::$nestableHtml = self::$nestableHtml."\t \t </li> \r\n";
        }
        self::$nestableHtml = self::$nestableHtml."\t \t </ol>";

        return response()->json(self::$nestableHtml);
    }

    static function generateNestableChildren($item, $id){
        $prefix  = module::get('url', array(['id', $id]))->first();
        self::$nestableHtml = self::$nestableHtml."\t \t <ol class='dd-list'> \r\n";
        for($i = 0; $i < $item->children->count(); $i++){
            self::$nestableHtml = self::$nestableHtml."\t \t \t <li class='dd-item dd3-item' data-id='".$item->children[$i]->id."'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd-handle dd3-handle'><i class='ti-move'></i></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t <div class='dd3-content'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t <div class='row'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col auto-margin'><h6>".$item->children[$i]->title." &nbsp; &nbsp; <small class='text-muted'>".$item->children[$i]->url."</small></h6></div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t <div class='col-auto'> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <a class='btn btn-flat btn-xs btn-warning permission' href='".route('admin.modules.item.permission', ['id' => $id, 'item_id' => $item->children[$i]->id])."'><i class='ti-direction-alt'></i> Permission</a> &nbsp;";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-info edit' item-id='".$item->children[$i]->id."' name='".$item->children[$i]->title."'><i class='ti-pencil-alt'></i> Edit</button> &nbsp;";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t \t <button class='btn btn-flat btn-xs btn-danger delete' item-id='".$item->children[$i]->id."' name='".$item->children[$i]->title."'><i class='ti-trash'></i> Delete</button>";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t \t </div> \r\n";
            self::$nestableHtml = self::$nestableHtml."\t \t \t </div> \r\n";
            if($item->children[$i]->isParent >= 1){    
                static::generateNestableChildren($item->children[$i], $id);
            }
            self::$nestableHtml = self::$nestableHtml."\t \t \t </li> \r\n";
        }
        self::$nestableHtml = self::$nestableHtml."\t \t </ol> \r\n";
    }
}
