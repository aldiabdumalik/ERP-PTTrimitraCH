<?php

namespace App\Http\Controllers\Tms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Oee\db_processname_tbl;
use App\Models\Oee\db_machinenumber_tbl;

class tms_ManufacturingController extends Controller
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
    public function ManufacturingPlanning_Index()
    {
        return view('tms.manufacturing.planning_main');
    }

    public function ManufacturingPlanning_LoadingCapacityPerMonth_index()
    {
        return view('tms.manufacturing.planning_capacityloadingpermonth');
    }

    public function ManufacturingPlanning_LoadingCapacityPerMachine_index()
    {
        return view('tms.manufacturing.planning_capacityloadingpermachine');
    }

    public function ManufacturingPlanning_LoadingCapacityPerMachineDetails_index()
    {
        return view('tms.manufacturing.planning_capacityloadingpermachinedetails');
    }

    public function ManufacturingPlanning_LoadingCapacityPerDate_index()
    {
        return view('tms.manufacturing.planning_capacityloadingperdate');
    }

    public function ManufacturingPlanning_LoadingCapacityPerDateDetails_index()
    {
        return view('tms.manufacturing.planning_capacityloadingperdatedetails');
    }
}
