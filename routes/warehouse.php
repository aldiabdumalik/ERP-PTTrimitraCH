<?php
use Illuminate\Support\Facades\Route;
/*
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |   TMS - WAREHOUSE - CLAIM ENTRY
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |
*/
Route::get('/warehouse/claim_entry', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@index', 
    'as' => 'tms.warehouse.claim_entry'
]);
Route::get('/warehouse/claim_entry/index_table', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntry', 
    'as' => 'tms.warehouse.claim_entry.index_table'
]);
Route::post('/warehouse/claim_entry/read', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntry', 
    'as' => 'tms.warehouse.claim_entry.read'
]);
Route::post('/warehouse/claim_entry/update', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryUpdate', 
    'as' => 'tms.warehouse.claim_entry.update'
]);
Route::post('/warehouse/claim_entry/header_tools', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryHeader', 
    'as' => 'tms.warehouse.claim_entry.header_tools'
]);
Route::post('/warehouse/claim_entry/create', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryCreate', 
    'as' => 'tms.warehouse.claim_entry.create'
]);
Route::post('/warehouse/claim_entry/get_rg', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryRGRead', 
    'as' => 'tms.warehouse.claim_entry.get_rg'
]);
// Status change
Route::post('/warehouse/claim_entry/delivery_order/do', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryDO', 
    'as' => 'tms.warehouse.claim_entry.delivery_order.do'
]);
Route::post('/warehouse/claim_entry/delivery_order/undo', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryUnDO', 
    'as' => 'tms.warehouse.claim_entry.delivery_order.undo'
]);
Route::post('/warehouse/claim_entry/receive_good/rg', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryRG', 
    'as' => 'tms.warehouse.claim_entry.receive_good.rg'
]);
Route::post('/warehouse/claim_entry/receive_good/unrg', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryUnRG', 
    'as' => 'tms.warehouse.claim_entry.receive_good.unrg'
]);
Route::post('/warehouse/claim_entry/void/voided', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryVoid', 
    'as' => 'tms.warehouse.claim_entry.void.voided'
]);
Route::post('/warehouse/claim_entry/void/unvoided', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryUnVoid',
    'as' => 'tms.warehouse.claim_entry.void.unvoided'
]);
Route::post('/warehouse/claim_entry/close/unclosed', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryUnClose',
    'as' => 'tms.warehouse.claim_entry.close.unclosed'
]);
Route::get('/warehouse/claim_entry/report', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryReport',
    'as' => 'tms.warehouse.claim_entry.report'
]);
/*
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |   TMS - WAREHOUSE - DO ENTRY
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |
*/
Route::get('/warehouse/do_entry', [
    'uses' => 'TMS\Warehouse\DoEntryController@index', 
    'as' => 'tms.warehouse.do_entry.index'
]);
Route::get('/warehouse/do_entry/read', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryRead', 
    'as' => 'tms.warehouse.do_entry.read'
]);
Route::post('/warehouse/do_entry/create', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryCreate', 
    'as' => 'tms.warehouse.do_entry.create'
]);
Route::post('/warehouse/do_entry/update', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryUpdate', 
    'as' => 'tms.warehouse.do_entry.update'
]);
Route::post('/warehouse/do_entry/table_index', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntry', 
    'as' => 'tms.warehouse.do_entry.table_index'
]);
Route::post('/warehouse/do_entry/header_tools', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryHeader', 
    'as' => 'tms.warehouse.do_entry.header_tools'
]);
Route::get('/warehouse/do_entry/table_index_setting', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryTableSetting', 
    'as' => 'tms.warehouse.do_entry.table_index_setting'
]);
Route::post('/warehouse/do_entry/void', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryVoid', 
    'as' => 'tms.warehouse.do_entry.void'
]);
Route::post('/warehouse/do_entry/unvoid', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryUnvoid', 
    'as' => 'tms.warehouse.do_entry.unvoid'
]);
Route::post('/warehouse/do_entry/post', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryPost', 
    'as' => 'tms.warehouse.do_entry.post'
]);
Route::post('/warehouse/do_entry/unpost', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryUnpost', 
    'as' => 'tms.warehouse.do_entry.unpost'
]);
Route::post('/warehouse/do_entry/finish', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryFinish', 
    'as' => 'tms.warehouse.do_entry.finish'
]);
Route::post('/warehouse/do_entry/unfinish', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryUnfinish', 
    'as' => 'tms.warehouse.do_entry.unfinish'
]);
Route::get('/warehouse/do_entry/print', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryPrint', 
    'as' => 'tms.warehouse.do_entry.print'
]);
Route::post('/warehouse/do_entry/revise', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryRevise', 
    'as' => 'tms.warehouse.do_entry.revise'
]);
Route::post('/warehouse/do_entry/qtyng', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryNG', 
    'as' => 'tms.warehouse.do_entry.ng'
]);
Route::post('/warehouse/do_entry/delete_item', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryDeleteItem', 
    'as' => 'tms.warehouse.do_entry.delete_item'
]);
Route::post('/warehouse/do_entry/cancel_form', [
    'uses' => 'TMS\Warehouse\DoEntryController@DoEntryCancel', 
    'as' => 'tms.warehouse.do_entry.cancel_form'
]);
/*
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |   TMS - WAREHOUSE - RR ENTRY
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |
*/
Route::get('/warehouse/rr_entry', [
    'uses' => 'TMS\Warehouse\RrEntryController@index', 
    'as' => 'tms.warehouse.rr_entry'
]);
Route::post('/warehouse/rr_entry/header_tools', [
    'uses' => 'TMS\Warehouse\RrEntryController@RrEntryHeader', 
    'as' => 'tms.warehouse.rr_entry.headerTools'
]);
Route::post('/warehouse/rr_entry/save', [
    'uses' => 'TMS\Warehouse\RrEntryController@RrEntrySave', 
    'as' => 'tms.warehouse.rr_entry.save'
]);

/*
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |   TMS - WAREHOUSE - Customer Invoice
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |
*/
Route::get('/warehouse/customer_invoice', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@index', 
    'as' => 'tms.warehouse.cust_invoice'
]);

Route::get('/warehouse/customer_price', [
    'uses' => 'TMS\Warehouse\CustPriceController@index', 
    'as' => 'tms.warehouse.cust_price'
]);
Route::post('/warehouse/customer_price/table-index', [
    'uses' => 'TMS\Warehouse\CustPriceController@custPriceTable', 
    'as' => 'tms.warehouse.cust_price.index'
]);
Route::post('/warehouse/customer_price/header', [
    'uses' => 'TMS\Warehouse\CustPriceController@headerTools', 
    'as' => 'tms.warehouse.cust_price.header'
]);
Route::get('/warehouse/customer_price/detail/{cust}/{date}', [
    'uses' => 'TMS\Warehouse\CustPriceController@custPriceDetail', 
    'as' => 'tms.warehouse.cust_price.detail'
]);
Route::get('/warehouse/customer_price/items', [
    'uses' => 'TMS\Warehouse\CustPriceController@getitems', 
    'as' => 'tms.warehouse.cust_price.getitems'
]);
Route::post('/warehouse/customer_price/save', [
    'uses' => 'TMS\Warehouse\CustPriceController@save', 
    'as' => 'tms.warehouse.cust_price.save'
]);