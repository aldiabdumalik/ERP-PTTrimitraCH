<?php

namespace App\Http\Controllers\Tms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Oee\db_processname_tbl;
use App\Models\Oee\db_machinenumber_tbl;
use App\Models\Dbtbs\entry_production_scheduler_detail_tbl;
use App\Models\StoredProcedure\proc_ManufacturingPlanning;

use DataTables;

class json_ManufacturingController extends Controller
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
    public function get_dtPlanningDetailPerMachine($process, $period, $machine, $plan_date, $shift, $flag)
    {
        $planDetailPerMachine = proc_ManufacturingPlanning::PlanDetailPerMachine($process, $period, $machine, $plan_date, $shift, $flag);
        return Datatables::of($planDetailPerMachine)
            ->make(true);
    }

    public function get_dtPlanningDetailPerMachineByOp($process, $period, $machine, $plan_date, $flag)
    {
        $planDetailPerMachineByOp = proc_ManufacturingPlanning::PlanDetailPerMachineByOp($process, $period, $machine, $plan_date, $flag);
        return Datatables::of($planDetailPerMachineByOp)
            ->make(true);
    }

}
