<?php
use Illuminate\Support\Facades\Route;
/*
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |   TMS - Master - Database Parts
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |
*/
Route::get('/master/database_parts', [
    'uses' => 'TMS\Master\DatabasePartsController@index', 
    'as' => 'tms.master.db_part'
]);
Route::post('/master/database_parts/header_tools', [
    'uses' => 'TMS\Master\DatabasePartsController@headerTools', 
    'as' => 'tms.master.db_part.header_tools'
]);