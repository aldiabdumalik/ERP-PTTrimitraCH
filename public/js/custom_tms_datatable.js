/*
| -------------------------------------------------------------
|   TMS DATATABLE FUNCTION
| -------------------------------------------------------------
|   1. Manufacturing - Raw Material
|   2. Manufacturing - Production Plan
|   3. Master Data - Master Item
| -------------------------------------------------------------
*/

// ++++++++++++++++++++++++++++++++++++
// 1. Manufacturing - Raw Material
// ++++++++++++++++++++++++++++++++++++

/* Supplier Setup */
function get_dtMappingDistributionWarning(id, url){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        paging: false,
        info: false,
        searching: false,
        //scrollY:"250px",
        //scrollCollapse: true,
        destroy: true,
        ajax:url,
        columnDefs: [
            {"className": "align-center", "targets": "_all"},
        ],
        columns:[
            {data: 'item_code', name: 'item_code'},
            {data: 'freq', name: 'freq'},
        ],
        order: [[0, 'asc']]
    })
};
function get_datRawMaterialMappingByModel(id, url){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        paging: false,
        info: false,
        searching: false,
        //scrollY:"250px",
        //scrollCollapse: true,
        destroy: true,
        ajax:url,
        columnDefs: [
            {"className": "align-left", "targets": "_all"},
        ],
        columns:[
            {data: 'id', name: 'id'},
            {data: 'vendor_code', name: 'vendor_code'},
            {data: 'company', name: 'company'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'asc']]
    });
};
function get_datSupplierInformation(id, url){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        paging: false,
        info: false,
        //scrollY:"250px",
        //scrollCollapse: true,
        destroy: true,
        ajax:url,
        columnDefs: [
            {"className": "align-left", "targets": "_all"},
        ],
        columns:[
            {data: 'vendor_code', name: 'vendor_code'},
            {data: 'company', name: 'company'},
            {data: 'contact', name: 'contact'},
            {data: 'phone', name: 'phone'},
            {data: 'fax', name: 'fax'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'asc']]
    });
};
function get_datSupplierReportParameter(id, url, flag){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        paging: false,
        searching: false,
        info: false,
        //scrollY:"250px",
        //scrollCollapse: true,
        destroy: true,
        ajax:url,
        columnDefs: [
            {"visible": false, "targets": 1},
            {"className": "align-center", "targets": "_all"},
        ],
        columns:[
            {data: 'report_ld_number', name: 'report_ld_number'},
            {data: 'id', name: 'id'},
            {data: 'report_prepared_by', name: 'report_prepared_by'},
            {data: 'report_checked_by', name: 'report_checked_by'},
            {data: 'report_approved_by', name: 'report_approved_by'},
            {data: 'report_start_date', name: 'report_start_date'},
            {data: 'report_end_date', name: 'report_end_date'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'asc']]
    });
};
function get_dtMappedRawMaterialWithSupplier(id, url){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        destroy:true,
        ajax:url,
        columnDefs: [
            {"visible": false, "targets": 0},
            {"className": "align-left", "targets": [2, 4, 5]},
            {"className": "align-right", "targets": 7},
            {"className": "align-center", "targets": "_all"}
        ],
        columns:[
            {data: 'id', name: 'id'},
            {data: 'vendor_code', name: 'vendor_code'},
            {data: 'COMPANY', name: 'COMPANY'},
            {data: 'item_code', name: 'item_code'},
            {data: 'DESCRIPT', name: 'DESCRIPT'},
            {data: 'DESCRIPT1', name: 'DESCRIPT1'},
            {data: 'distribution_pctg', name: 'distribution_pctg'},
            {data: 'frm_qty', name: 'frm_qty'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']]
    });
};
/* Dashboard */
function get_dtDashboardForecastNote(id, url){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        paging: false,
        searching: false,
        scrollY:"250px",
        scrollCollapse: true,
        destroy: true,
        ajax:url,
        columnDefs: [
            {"className": "align-center", "targets": "_all"},
        ],
        columns:[
            {data: 'vendor_code', name: 'vendor_code'},
            {data: 'ver_no', name: 'ver_no'},
            {data: 'ver_date', name: 'ver_date'},
            {data: 'approved_date', name: 'approved_date'},
        ],
        order: [[0, 'asc']]
    });
};
/* Forecast Note */
function get_dtViewForecastNoteOp(id, url, period){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        paging: true,
        //scrollY:"350px",
        //scrollCollapse: true,
        destroy: true,
        ajax:url,
        columnDefs: [
            {"visible": false, "targets": 1},
            {"className": "align-left", "targets": [0, 2]},
            {"className": "align-right", "targets": [4, 5, 6, 7, 8, 9, 10]},
            {"className": "align-center", "targets": "_all"},
        ],
        columns:[
            {data: 'item_code',   name: 'item_code'  },
            {data: 'id',          name: 'id'         },
            {data: 'descript',    name: 'descript'   },
            {data: 'model',       name: 'model'  },
            {data: 'sc_tm_qty',   name: 'sc_tm_qty'  },
            {data: 'sc_nm_1_qty', name: 'sc_nm_1_qty'},
            {data: 'sc_nm_2_qty', name: 'sc_nm_2_qty'},
            {data: 'sc_nm_3_qty', name: 'sc_nm_3_qty'},
            {data: 'sc_nm_4_qty', name: 'sc_nm_4_qty'},
            {data: 'sc_nm_5_qty', name: 'sc_nm_5_qty'},
            {data: 'sc_nm_6_qty', name: 'sc_nm_6_qty'}
        ],
        order: [[0, 'asc']]
    });

    // Change Datatable headers dynamically

    // Declare Month Constants
    const $months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    const colMonth = 4;

    // N Period
    var $period  = new Date(period);
    var $head_n  = $(id).DataTable().column( colMonth + 0 ).header();
    $($head_n).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+1 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n1 = $(id).DataTable().column( colMonth + 1 ).header();
    $($head_n1).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+2 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n2 = $(id).DataTable().column( colMonth + 2 ).header();
    $($head_n2).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+3 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n3 = $(id).DataTable().column( colMonth + 3 ).header();
    $($head_n3).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+4 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n4 = $(id).DataTable().column( colMonth + 4 ).header();
    $($head_n4).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+5 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n5 = $(id).DataTable().column( colMonth + 5 ).header();
    $($head_n5).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+6 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n6 = $(id).DataTable().column( colMonth + 6 ).header();
    $($head_n6).html($months[$period.getMonth()] + "-" + $period.getFullYear());
};
function get_dtForecastNoteOp(id, url, period){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        paging: false,
        scrollY:"350px",
        scrollCollapse: true,
        destroy: true,
        ajax:url,
        columnDefs: [
            {"visible": false, "targets": 1},
            {"className": "align-left", "targets": [0, 2]},
            {"className": "align-right", "targets": [4, 5, 6, 7, 8, 9, 10, 11]},
            {"className": "align-center", "targets": "_all"},
        ],
        columns:[
            {data: 'item_code', name: 'item_code'},
            {data: 'id',        name: 'id'       },
            {data: 'descript',  name: 'descript' },
            {data: 'model',     name: 'model'},
            {data: 'factor',    name: 'factor'   },
            {data: 'dist',      name: 'dist'     },
            {data: 'tm_kg',     name: 'tm_kg'    },
            {data: 'nm1_kg',    name: 'nm1_kg'   },
            {data: 'nm2_kg',    name: 'nm2_kg'   },
            {data: 'nm3_kg',    name: 'nm3_kg'   },
            {data: 'nm4_kg',    name: 'nm4_kg'   },
            {data: 'nm5_kg',    name: 'nm5_kg'   },
            {data: 'nm6_kg',    name: 'nm6_kg'   }
        ],
        order: [[0, 'asc']]
    });

    // Change Datatable headers dynamically

    // Declare Month Constants
    const $months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    const colMonth = 6;

    // N Period
    var $period  = new Date(period);
    var $head_n  = $(id).DataTable().column( colMonth + 0 ).header();
    $($head_n).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+1 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n1 = $(id).DataTable().column( colMonth + 1 ).header();
    $($head_n1).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+2 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n2 = $(id).DataTable().column( colMonth + 2 ).header();
    $($head_n2).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+3 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n3 = $(id).DataTable().column( colMonth + 3 ).header();
    $($head_n3).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+4 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n4 = $(id).DataTable().column( colMonth + 4 ).header();
    $($head_n4).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+5 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n5 = $(id).DataTable().column( colMonth + 5 ).header();
    $($head_n5).html($months[$period.getMonth()] + "-" + $period.getFullYear());

    // N+6 Period
    $period.setMonth($period.getMonth() + 1);
    var $head_n6 = $(id).DataTable().column( colMonth + 6 ).header();
    $($head_n6).html($months[$period.getMonth()] + "-" + $period.getFullYear());
};


// ++++++++++++++++++++++++++++++++++++
// 2. Manufacturing - Production Plan
// ++++++++++++++++++++++++++++++++++++
function initTableMachine_master(id, process){
    flag = 0;
    period = null;
    machine = null;
    plan_date = null;
    shift = null;

    $(id).DataTable({
        responsive:true,
        processing: true,
        serverSide: true,
        destroy:true,
        ajax:'get_dtPlanningDetailPerMachine/'+process+'/'+period+'/'+machine+'/'+plan_date+'/'+shift+'/'+flag,
        columns: [
            {
                "className":      'details-control',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": '<a class="text-secondary"><i class="fa fa-plus"></i></a>'
            },
            { data: 'id', name: 'id' },
            { data: 'machine', name: 'machine' },
            { data: 'capacity', name: 'capacity' },
            { data: 'process', name: 'process' }
        ],
        order: [[2, 'asc']]
    });
};

function initTableMachine_detail(id, period, machine) {
    flag = 1;
    process = null;
    plan_date = null;
    shift = null;

    $(id).DataTable({
        processing: true,
        serverSide: true,
        destroy:true,
        //ajax:data.details_url,
        ajax:'get_dtPlanningDetailPerMachine/'+process+'/'+period+'/'+machine+'/'+plan_date+'/'+shift+'/'+flag,
        columns: [
            {
                "className":      'details-control-perDay',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": '<a class="text-secondary"><i class="fa fa-plus"></i></a>'
            },
            { data: 'plan_date', name: 'plan_date' },
            { data: 'machine', name: 'machine' },
            { data: 'load_min', name: 'load_min' },
            { data: 'capacity_min', name: 'capacity_min' },
            { data: 'load_pctg', name: 'load_pctg' }
        ],
        order: [[1, 'asc']]
    });
};

function initTableMachine_detail_detail(id, tableDate, machine) {
    flag = 2;
    process = null;
    period = null;
    plan_date = tableDate;
    shift = null;

    $(id).DataTable({
        processing: true,
        serverSide: true,
        destroy:true,
        ajax:'get_dtPlanningDetailPerMachine/'+process+'/'+period+'/'+machine+'/'+plan_date+'/'+shift+'/'+flag,
        columns: [
            {
                "className":      'details-control-perShift',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": '<a class="text-secondary"><i class="fa fa-plus"></i></a>'
            },
            { data: 'shift', name: 'shift' },
            { data: 'plan_date', name: 'plan_date' },
            { data: 'machine', name: 'machine' },
            { data: 'load_min', name: 'load_min' },
            { data: 'capacity_min', name: 'capacity_min' },
            { data: 'load_pctg', name: 'load_pctg' }
        ],
        order: [[1, 'asc']]
    });
};

function initTableMachine_detail_detail_detail(id, tableShift, plan_date, machine) {
    flag = 3;
    process = null;
    period = null;
    shift = tableShift;

    $(id).DataTable({
        processing: true,
        serverSide: true,
        destroy:true,
        //ajax:data.details_url,
        ajax:'get_dtPlanningDetailPerMachine/'+process+'/'+period+'/'+machine+'/'+plan_date+'/'+shift+'/'+flag,
        columns: [
            { data: 'prod_code', name: 'prod_code' },
            { data: 'item_code', name: 'item_code' },
            { data: 'part_number', name: 'part_number' },
            { data: 'descript', name: 'descript' },
            { data: 'model', name: 'model' },
            { data: 'seq', name: 'seq' },
            { data: 'cycle_time', name: 'cycle_time' },
            { data: 'qty_plan', name: 'qty_plan' },
            { data: 'load_min', name: 'load_min' },
            { data: 'capacity_min', name: 'capacity_min' },
            { data: 'load_pctg', name: 'load_pctg' }
        ],
        order: [[0, 'asc']]
    });
};

function initTableMachineByOp_master(id, process){
    flag = 0;
    period = null;
    machine = null;
    plan_date = null;

    $(id).DataTable({
        responsive:true,
        processing: true,
        serverSide: true,
        destroy:true,
        ajax:'get_dtPlanningDetailPerMachineByOp/'+process+'/'+period+'/'+machine+'/'+plan_date+'/'+flag,
        columns: [
            {
                "className":      'details-control',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": '<a class="text-secondary"><i class="fa fa-plus"></i></a>'
            },
            { data: 'id', name: 'id' },
            { data: 'machine', name: 'machine' },
            { data: 'capacity', name: 'capacity' },
            { data: 'process', name: 'process' }
        ],
        order: [[2, 'asc']]
    });
};

function initTableMachineByOp_detail(id, period, machine) {
    flag = 1;
    process = null;
    plan_date = null;

    $(id).DataTable({
        processing: true,
        serverSide: true,
        destroy:true,
        //ajax:data.details_url,
        ajax:'get_dtPlanningDetailPerMachineByOp/'+process+'/'+period+'/'+machine+'/'+plan_date+'/'+flag,
        columns: [
            {
                "className":      'details-control-perDay',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": '<a class="text-secondary"><i class="fa fa-plus"></i></a>'
            },
            { data: 'plan_date', name: 'plan_date' },
            { data: 'machine', name: 'machine' },
            { data: 'load_min', name: 'load_min' },
            { data: 'capacity_min', name: 'capacity_min' },
            { data: 'load_pctg', name: 'load_pctg' }
        ],
        order: [[1, 'asc']]
    });
};

function initTableMachineByOp_detail_detail(id, plan_date, machine, period) {
    flag = 2;
    process = null;

    $(id).DataTable({
        processing: true,
        serverSide: true,
        destroy:true,
        //ajax:data.details_url,
        ajax:'get_dtPlanningDetailPerMachineByOp/'+process+'/'+period+'/'+machine+'/'+plan_date+'/'+flag,
        columns: [
            { data: 'prod_code', name: 'prod_code' },
            { data: 'item_code', name: 'item_code' },
            { data: 'part_number', name: 'part_number' },
            { data: 'descript', name: 'descript' },
            { data: 'model', name: 'model' },
            { data: 'seq', name: 'seq' },
            { data: 'cycle_time', name: 'cycle_time' },
            { data: 'qty_plan', name: 'qty_plan' },
            { data: 'load_min', name: 'load_min' },
            { data: 'capacity_min', name: 'capacity_min' },
            { data: 'load_pctg', name: 'load_pctg' }
        ],
        order: [[0, 'asc']]
    });
};

// Datatable in Planning Details by Date
function initTableDate_master(id, period){
    flag = 0;
    process = null;
    machine = null;
    plan_date = null;
    shift = null;

    $(id).DataTable({
        responsive:true,
        processing: true,
        serverSide: true,
        destroy:true,
        ajax:'get_dtPlanningDetailPerDate/'+process+'/'+period+'/'+machine+'/'+plan_date+'/'+shift+'/'+flag,
        columns: [
            {
                "className":      'details-control',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": '<a class="text-secondary"><i class="fa fa-plus"></i></a>'
            },
            { data: 'plan_date', name: 'plan_date' },
            { data: 'plan_day', name: 'plan_day' },
            { data: 'capacity_s1', name: 'capacity_s1' },
            { data: 'capacity_s2', name: 'capacity_s2' },
            { data: 'capacity_total', name: 'capacity_total' }
        ],
        order: [[1, 'asc']]
    });
};

//++++++++++++++++++++++++++++++++++++
// 3. Master Data - Master Item
//++++++++++++++++++++++++++++++++++++
function populate_dtItem(id){
    $(id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        destroy:true,
        ajax:'get_dtItem',
        columns:[
            {data: 'itemcode', name: 'itemcode'},
            {data: 'part_no', name: 'part_no'},
            {data: 'descript', name: 'descript'},
            {data: 'descript1', name: 'descript1'},
            {data: 'custcode', name: 'custcode'},
            {data: 'groups', name: 'groups'},
            {data: 'types', name: 'types'},
            {data: 'state', name: 'state'},
            {data: 'inventory', name: 'inventory'},
            {data: 'formula', name: 'formula'},
            {data: 'unit', name: 'unit'},
            {data: 'factor', name: 'factor'},
            {data: 'fac_unit', name: 'fac_unit'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });
}
