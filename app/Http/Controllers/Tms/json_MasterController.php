<?php

namespace App\Http\Controllers\Tms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Dbtbs\Item;

use DataTables;

class json_MasterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    //+++++++++++++++++++++++++++
    // Get Datatable
    //+++++++++++++++++++++++++++
    public function get_dtItem()
    {
        $item = Item::Item();
        // With Yajra Datatable
        return Datatables::of($item)
            ->addColumn('action', function ($item)
                {
                    return '<ul class="d-flex justify-content-center">
                                <li><a href="#edit-'.$item->itemcode.'" class="text-secondary">
                                    <i class="fa fa-edit"></i> Edit</a>
                                </li>
                                <li><a href="#delete-'.$item->itemcode.'" class="text-danger">
                                    <i class="ti-trash"></i> Delete</a>
                                </li>
                            </ul>';
                })
            ->make(true);
    }


    //+++++++++++++++++++++++++++
    // Posting
    //+++++++++++++++++++++++++++
    public function post_entryItem(Request $request)
    {
        $test = $request->input('email');
        echo json_encode('success');
        exit;
    }

}
