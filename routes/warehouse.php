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