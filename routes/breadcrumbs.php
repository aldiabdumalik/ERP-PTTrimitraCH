<?php

// 1. Admin Modules

//      1.1. Dashboard
Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push('Dashboard', '#');
    $trail->push('Dashboard', route('admin.dashboard'));
});

//      1.2. Users
Breadcrumbs::for('admin.users', function ($trail) {
    $trail->push('Users', '#');
    $trail->push('Users', route('admin.users'));
});

Breadcrumbs::for('admin.users.edit', function($trail, $item) {
    $trail->parent('admin.users');
    $trail->push('Edit ('.$item['user']->FullName.')', route('admin.users.edit', ['id' => $item['user']->id]));
});

//      1.3. Roles
Breadcrumbs::for('admin.roles', function ($trail) {
    $trail->push('Roles', '#');
    $trail->push('Roles', route('admin.roles'));
});

Breadcrumbs::for('admin.roles.permission', function ($trail, $item) {
    $trail->parent('admin.roles');
    $trail->push('Permission ('.$item['role']->name.')', route('admin.roles.permission', ['id' => $item['role']->id]));
});

//      1.4. Modules
Breadcrumbs::for('admin.modules', function ($trail) {
    $trail->push('Modules', '#');
    $trail->push('Modules', route('admin.modules'));
});

Breadcrumbs::for('admin.modules.add', function ($trail) {
    $trail->parent('admin.modules');
    $trail->push('Add', route('admin.modules.add'));
});

Breadcrumbs::for('admin.modules.edit', function ($trail, $item) {
    $trail->parent('admin.modules');
    $trail->push('Edit ('.$item['module']->name.')', route('admin.modules.edit', ['id' => $item['module']->id]));
});

Breadcrumbs::for('admin.modules.item', function ($trail, $item) {
    $trail->parent('admin.modules');
    $trail->push('Module Item ('.$item['module']->name.')', route('admin.modules.item', ['id' => $item['module']->id]));
});

Breadcrumbs::for('admin.modules.item.permission', function ($trail, $item) {
    $trail->parent('admin.modules.item', $item);
    $trail->push($item['moduleItem']->title, route('admin.modules.item.permission', ['id' => $item['module']->id, 'item_id' => $item['moduleItem']->id]));
});


/*  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
|       TMS Modules
|   ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
|
|       2.1. - - - - - - Dashboard
|       2.2. - - - - - - Master
|           2.2.1. - - - - - Master Item
|       2.3. - - - - - - Manufacturing
|           2.3.1. - - - - - Production Plan
|               2.3.1.1. - - - - - Summary Loading Capacity per Month
|               2.3.1.2. - - - - - Summary Loading Capacity per Date
|               2.3.1.3. - - - - - Summary Loading Capacity per Machine
|               2.3.1.4. - - - - - Details Loading Capacity per Date
|               2.3.1.5. - - - - - Details Loading Capacity per Machine
|           2.3.2. - - - - - Raw Material
|               2.3.2.1. - - - - - Dashboard
|               2.3.2.2. - - - - - Setup Supplier Distribution / Mapping
|               2.3.2.3. - - - - - Setup Lot Material
|               2.3.2.4. - - - - - Forecast Note
|               2.3.2.5. - - - - - Setup Supplier Report
|               2.3.2.6. - - - - - Reference BoM
|       2.4. - - - - - - Warehouse
|           2.4.1. - - - - - Products
|               2.4.1. - - - - - View Product
|           2.4.2. - - - - - Transfer Order
|
|   ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

//  2.1. Dashboard
Breadcrumbs::for('tms_Dashboard', function ($trail) {
    $trail->push('Dashboard', '#');
    $trail->push('Dashboard', route('tms_Dashboard'));
});

//  2.2. Master Item
Breadcrumbs::for('tms_MasterItem_Index', function ($trail) {
    $trail->push('Master', '#');
    $trail->push('Master Item', route('tms_MasterItem_Index'));
});

//  2.3.    Manufacturing

//  2.3.1.  Production Plan
Breadcrumbs::for('tms.manufacturing.production-plan.index', function ($trail) {
    $trail->push('Manufacturing', '#');
    $trail->push('Production Plan', route('tms.manufacturing.production-plan.index'));
});
//  2.3.1.1.  Summary Loading Capacity per Month
Breadcrumbs::for('tms.manufacturing.production-plan.summaryLoadingCapacityPerMonth', function ($trail) {
    $trail->parent('tms.manufacturing.production-plan.index');
    $trail->push('Summary Loading Capacity Per Month', route('tms.manufacturing.production-plan.summaryLoadingCapacityPerMonth'));
});
//  2.3.1.2.  Summary Loading Capacity per Date
Breadcrumbs::for('tms.manufacturing.production-plan.summaryLoadingCapacityPerDate', function ($trail) {
    $trail->parent('tms.manufacturing.production-plan.index');
    $trail->push('Summary Loading Capacity Per Date', route('tms.manufacturing.production-plan.summaryLoadingCapacityPerDate'));
});
//  2.3.1.3.  Summary Loading Capacity per Machine
Breadcrumbs::for('tms.manufacturing.production-plan.summaryLoadingCapacityPerMachine', function ($trail) {
    $trail->parent('tms.manufacturing.production-plan.index');
    $trail->push('Summary Loading Capacity Per Machine', route('tms.manufacturing.production-plan.summaryLoadingCapacityPerMachine'));
});
//  2.3.1.4. Details Loading Capacity per Date
Breadcrumbs::for('tms.manufacturing.production-plan.detailLoadingCapacityPerDate', function ($trail) {
    $trail->parent('tms.manufacturing.production-plan.index');
    $trail->push('Detail Loading Capacity per Date', route('tms.manufacturing.production-plan.detailLoadingCapacityPerDate'));
});
//  2.3.1.5. Details Loading Capacity per Machine
Breadcrumbs::for('tms.manufacturing.production-plan.detailLoadingCapacityPerMachine', function ($trail) {
    $trail->parent('tms.manufacturing.production-plan.index');
    $trail->push('Detail Loading Capacity per Machine', route('tms.manufacturing.production-plan.detailLoadingCapacityPerMachine'));
});

//  2.3.2.1. Dashboard Raw Material
Breadcrumbs::for('tms.manufacturing.raw-material.index', function ($trail) {
    $trail->push('Manufacturing', '#');
    $trail->push('Raw Material', route('tms.manufacturing.raw-material.index'));
});
//  2.3.2.2. Setup Supplier Distribution Raw Material
Breadcrumbs::for('tms.manufacturing.raw-material.setup-supplier-distribution', function ($trail) {
    $trail->parent('tms.manufacturing.raw-material.index');
    $trail->push('Supplier Distribution', route('tms.manufacturing.raw-material.setup-supplier-distribution'));
});
//  2.3.2.3. Setup Lot Material
Breadcrumbs::for('tms.manufacturing.raw-material.setup-lot', function ($trail) {
    $trail->parent('tms.manufacturing.raw-material.index');
    $trail->push('Lot Material', route('tms.manufacturing.raw-material.setup-lot'));
});
// 2.3.2.4. - - - - - Forecast Note
Breadcrumbs::for('tms.manufacturing.raw-material.forecast-note', function ($trail) {
    $trail->parent('tms.manufacturing.raw-material.index');
    $trail->push('Forecast Note', route('tms.manufacturing.raw-material.forecast-note'));
});
// 2.3.2.5. - - - - - Setup Supplier Report
Breadcrumbs::for('tms.manufacturing.raw-material.setup-supplier-report', function ($trail) {
    $trail->parent('tms.manufacturing.raw-material.index');
    $trail->push('Supplier Report', route('tms.manufacturing.raw-material.setup-supplier-report'));
});
// 2.3.2.6. - - - - - Reference BoM
Breadcrumbs::for('tms.manufacturing.raw-material.reference-bom', function ($trail) {
    $trail->parent('tms.manufacturing.raw-material.index');
    $trail->push('BoM Tree', route('tms.manufacturing.raw-material.reference-bom'));
});

// 2.3.2.7. - - - - - THP Entry
Breadcrumbs::for('tms.manufacturing.thp_entry', function ($trail) {
    $trail->push('Manufacturing', '#');
    $trail->push('THP Entry', route('tms.manufacturing.thp_entry'));
});

//  2.4.    Warehouse
Breadcrumbs::for('tms.warehouse.claim_entry', function ($trail) {
    $trail->push('Warehouse', '#');
    $trail->push('Claim Entry', route('tms.warehouse.claim_entry'));
});
Breadcrumbs::for('tms.warehouse.do_entry.index', function ($trail) {
    $trail->push('Warehouse', '#');
    $trail->push('DO Entry', route('tms.warehouse.do_entry.index'));
});
Breadcrumbs::for('tms.warehouse.rr_entry', function ($trail) {
    $trail->push('Warehouse', '#');
    $trail->push('RR Entry', route('tms.warehouse.rr_entry'));
});
Breadcrumbs::for('tms.warehouse.customer_invoice', function ($trail) {
    $trail->push('Warehouse', '#');
    $trail->push('Customer Invoice', route('tms.warehouse.customer_invoice'));
});
//  2.4.1. Products
Breadcrumbs::for('tms.warehouse.products', function ($trail) {
    $trail->push('Warehouse', '#');
    $trail->push('Products', route('tms.warehouse.products'));
});
// 2.4.1.1. View Products
Breadcrumbs::for('tms.warehouse.products.view', function ($trail, $item) {
    $trail->parent('tms.warehouse.products');
    $trail->push($item['product']->ITEMCODE, route('tms.warehouse.products.view', ['id' => $item['product']->id]));
});

//  2.4.2.  Transfer Order
Breadcrumbs::for('tms.warehouse.transfer-order', function ($trail) {
    $trail->push('Warehouse', '#');
    $trail->push('Transfer Order', route('tms.warehouse.transfer-order'));
});
    // 2.4.3
Breadcrumbs::for('tms.warehouse.mto-entry', function ($trail) {
    $trail->push('Warehouse', '#');
    $trail->push('MTO-Entry', route('tms.warehouse.mto-entry'));
});




