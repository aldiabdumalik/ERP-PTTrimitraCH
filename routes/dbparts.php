<?php
use Illuminate\Support\Facades\Route;

/*
| +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
|   TMS - Database Parts
| +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
|
|   1. Master Procees - - - - - - - - - - - - - - :: PAGE ::
|       1.1. View - - - - - - - - - - - - - :: PAGE ::
|       1.2. Get Datatables - - - - - - - - :: POST  :: JSON ::
|       1.3. Get Detail - - - - - - - - - - :: GET  :: JSON ::
|       1.4. Store - - - - - - - - - - - - -:: POST  :: JSON ::
|       1.5. Update - - - - - - - - - - - - :: PUT  :: JSON ::
|       1.6. Destroy - - - - - - - - - - - -:: DELETE  :: JSON ::
|   2. Transfer Order - - - - - - - - - - - :: PAGE ::
|       2.1. Get Datatables - - - - - - - - :: GET  :: JSON ::
|       2.2. Get Header - - - - - - - - - - :: GET  :: JSON ::
|
| +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
Route::get('/database_parts/master_process', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@index', 
    'as' => 'tms.db_parts.master.process'
]);
Route::post('/database_parts/master_process/table_index', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@tableIndex', 
    'as' => 'tms.db_parts.master.process.tbl_index'
]);
Route::get('/database_parts/master_process/{id}/detail/', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@detail', 
    'as' => 'tms.db_parts.master.process.detail'
]);
Route::post('/database_parts/master_process/store', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@store', 
    'as' => 'tms.db_parts.master.process.store'
]);
Route::put('/database_parts/master_process/{id}/update', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@update', 
    'as' => 'tms.db_parts.master.process.update'
]);
Route::delete('/database_parts/master_process/{id}/destroy', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@destroy', 
    'as' => 'tms.db_parts.master.process.destroy'
]);