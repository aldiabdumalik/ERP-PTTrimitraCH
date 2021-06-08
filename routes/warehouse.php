<?php
use Illuminate\Support\Facades\Route;

Route::get('/warehouse/claim_entry', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@index', 
    'as' => 'tms.warehouse.claim_entry'
]);
Route::get('/warehouse/claim_entry/index_table', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntry', 
    'as' => 'tms.warehouse.claim_entry.index_table'
]);
Route::post('/warehouse/claim_entry/header_tools', [
    'uses' => 'TMS\Warehouse\ClaimEntryController@claimEntryHeader', 
    'as' => 'tms.warehouse.claim_entry.header_tools'
]);