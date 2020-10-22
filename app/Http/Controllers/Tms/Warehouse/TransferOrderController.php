<?php

namespace App\Http\Controllers\TMS\Warehouse;

// Laravel Libraries
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

// Classes
use App\Classes\ButtonBuilder As ButtonBuilder;

// Controllers
use App\Http\Controllers\RolePermissionController As RolePermissionControl;

// Models
use App\Models\User_Role As UserRole;
use App\Models\Dbtbs\TO_hdr_adjustment As TOHdrAdjustment;
use App\Models\Dbtbs\TO_Dtl_Adjustment As TODtlAdjustment;

class TransferOrderController extends Controller
{
    public function index(){

        // $ActionButton = '';

        // $UserID     = Auth::user()->id;
        // $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
        // $RoleID     = $UserRole->role_id;

        // $AddAccess  = RolePermissionControl::CheckPermission($RoleID, 'add_role');
        // if($AddAccess){
        //     $ActionButton = ButtonBuilder::Build('MAIN', 'ADD', 'add-btn', 'ti-plus', 'Add New Role');
        // }

        // $data = [
        //     'ActionButton' => $ActionButton
        // ];

        return view('tms.warehouse.transfer-order.index');
    }

    public function getDatatablesHeader(){
        $UserID     = Auth::user()->id;
        $UserRole   = UserRole::where('ekanban_user_id', $UserID)->first();
        $RoleID     = $UserRole->role_id;
        $ViewAccess  = RolePermissionControl::CheckPermission($RoleID, 'tms_view_transfer_order');
        
        return  DataTables::of(TOHdrAdjustment::all())
                     ->addColumn('action', function ($data) use ($ViewAccess){
                        $ActionButton = '';
    
                        if($ViewAccess){
                            $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'VIEW', 'view-btn', 'ti-eye', 'View', '#', "row-id=$data->id");
                        }
    
                    //     // $ModuleEditAccess = RolePermissionControl::CheckPermission($RoleID, 'edit_modules');
                    //     // if($ModuleEditAccess){
                    //     //     $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE-LINK', 'EDIT', 'module-edit-btn', 'ti-pencil-alt', 'Edit', route('admin.modules.edit', ['id' => $data->id]));
                    //     // }
    
                    //     // $ModuleDeleteAccess = RolePermissionControl::CheckPermission($RoleID, 'delete_modules');
                    //     // if($ModuleDeleteAccess){
                    //     //     $ActionButton = $ActionButton.ButtonBuilder::Build('DATATABLE', 'DELETE', $data->id, 'ti-trash', 'Delete', '#', "name='$data->name'");
                    //     // }
    
                        return $ActionButton;
                     })
                    ->make(true);
        
    }

    public function getDetail($id){
        $TOHeader   = TOHdrAdjustment::where('id', $id)->first();
        $TOHeaderNo = $TOHeader->TO_NO;
        $TODetail   = TODtlAdjustment::select(
                            'id', 'TO_NO', 'ITEMCODE', 'PART_NO', 'DESCRIPT', 'UNIT',
                            'QUANTITY', 'COST', 'FAC_UNIT', 'FAC_QTY', 'FACTOR')
                      ->where([['TO_NO', '=', $TOHeaderNo], ['status', '=', 1]])
                      ->get();
        $output = [
            'header' => $TOHeader,
            'detail' => $TODetail
        ];
        return response()->json($output);
    }
}
