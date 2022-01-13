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
    |   TMS - WAREHOUSE - DO PENDING ENTRY
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |
*/
Route::get('/warehouse/do_temporary_entry', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@index', 
    'as' => 'tms.warehouse.do_temp.index'
]);
Route::post('/warehouse/do_temporary_entry/index_table', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@index_table', 
    'as' => 'tms.warehouse.do_temp.tbl_index'
]);
Route::get('/warehouse/do_temporary_entry/detail/{do_no}/cek/{is_check}', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@detail', 
    'as' => 'tms.warehouse.do_temp.detail'
]);
Route::get('/warehouse/do_temporary_entry/edit/{do_no}', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@edit', 
    'as' => 'tms.warehouse.do_temp.edit'
]);
Route::post('/warehouse/do_temporary_entry/store', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@store', 
    'as' => 'tms.warehouse.do_temp.store'
]);
Route::put('/warehouse/do_temporary_entry/{do_no}/update', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@update', 
    'as' => 'tms.warehouse.do_temp.update'
]);
Route::post('/warehouse/do_temporary_entry/header_tools', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@header_tools', 
    'as' => 'tms.warehouse.do_temp.header_tools'
]);
Route::put('/warehouse/do_temporary_entry/{do_no}/posted', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@posted', 
    'as' => 'tms.warehouse.do_temp.posted'
]);
Route::put('/warehouse/do_temporary_entry/{do_no}/unposted', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@unposted', 
    'as' => 'tms.warehouse.do_temp.unposted'
]);
Route::put('/warehouse/do_temporary_entry/{do_no}/voided', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@voided', 
    'as' => 'tms.warehouse.do_temp.voided'
]);
Route::put('/warehouse/do_temporary_entry/{do_no}/unvoided', [
    'uses' => 'TMS\Warehouse\DoPendingEntryController@unvoided', 
    'as' => 'tms.warehouse.do_temp.unvoided'
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
Route::get('/warehouse/customer_invoice/{inv_no}/detail', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@inv_detail', 
    'as' => 'tms.warehouse.cust_invoice.detail'
]);
Route::post('/warehouse/customer_invoice/tbl_index', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@inv_table', 
    'as' => 'tms.warehouse.cust_invoice.tbl'
]);
Route::post('/warehouse/customer_invoice/header', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@header', 
    'as' => 'tms.warehouse.cust_invoice.header'
]);
Route::post('/warehouse/customer_invoice/do', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@delivery_order', 
    'as' => 'tms.warehouse.cust_invoice.do'
]);
Route::post('/warehouse/customer_invoice/save', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@save', 
    'as' => 'tms.warehouse.cust_invoice.save'
]);
Route::put('/warehouse/customer_invoice/update/{inv_no}', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@update', 
    'as' => 'tms.warehouse.cust_invoice.update'
]);
Route::post('/warehouse/customer_invoice/posted', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@posted', 
    'as' => 'tms.warehouse.cust_invoice.posted'
]);
Route::post('/warehouse/customer_invoice/unposted', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@unposted', 
    'as' => 'tms.warehouse.cust_invoice.unposted'
]);
Route::post('/warehouse/customer_invoice/voided', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@voided', 
    'as' => 'tms.warehouse.cust_invoice.voided'
]);
Route::post('/warehouse/customer_invoice/unvoided', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@unvoided', 
    'as' => 'tms.warehouse.cust_invoice.unvoided'
]);
Route::get('/warehouse/customer_invoice/report', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@report', 
    'as' => 'tms.warehouse.cust_invoice.report'
]);
Route::put('/warehouse/customer_invoice/update/{inv_no}/note', [
    'uses' => 'TMS\Warehouse\CustInvoiceController@updateNote', 
    'as' => 'tms.warehouse.cust_invoice.update.note'
]);
/*
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |   TMS - WAREHOUSE - Customer Price
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |
*/
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
Route::put('/warehouse/customer_price/update/{cust}/{active}', [
    'uses' => 'TMS\Warehouse\CustPriceController@update', 
    'as' => 'tms.warehouse.cust_price.update'
]);
Route::post('/warehouse/customer_price/voided', [
    'uses' => 'TMS\Warehouse\CustPriceController@voided', 
    'as' => 'tms.warehouse.cust_price.voided'
]);
Route::post('/warehouse/customer_price/unvoided', [
    'uses' => 'TMS\Warehouse\CustPriceController@unvoided', 
    'as' => 'tms.warehouse.cust_price.unvoided'
]);
Route::post('/warehouse/customer_price/posted', [
    'uses' => 'TMS\Warehouse\CustPriceController@posted', 
    'as' => 'tms.warehouse.cust_price.posted'
]);
Route::post('/warehouse/customer_price/unposted', [
    'uses' => 'TMS\Warehouse\CustPriceController@unposted', 
    'as' => 'tms.warehouse.cust_price.unposted'
]);
Route::get('/warehouse/customer_price/{code}/print', [
    'uses' => 'TMS\Warehouse\CustPriceController@print', 
    'as' => 'tms.warehouse.cust_price.print'
]);
Route::get('/warehouse/customer_price/trigger', [
    'uses' => 'TMS\Warehouse\CustPriceController@trigger', 
    'as' => 'tms.warehouse.cust_price.trigger'
]);