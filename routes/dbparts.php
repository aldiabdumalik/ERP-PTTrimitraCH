<?php
use Illuminate\Support\Facades\Route;

/*
| +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
|   TMS - Database Parts
| +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
|
|   1. Master Procees - - - - - - - - - - - :: PAGE ::
|       1.1. View - - - - - - - - - - - - - :: PAGE ::
|       1.2. Get Datatables - - - - - - - - :: POST  :: JSON ::
|       1.3. Get Detail - - - - - - - - - - :: GET  :: JSON ::
|       1.4. Store - - - - - - - - - - - - -:: POST  :: JSON ::
|       1.5. Update - - - - - - - - - - - - :: PUT  :: JSON ::
|       1.6. Destroy - - - - - - - - - - - -:: DELETE  :: JSON ::
|       1.7. Log - - - - - - - - - - - - - -:: POST  :: JSON ::
|       1.8. Trash - - - - - - - - - - - - -:: POST  :: JSON ::
|       1.9. Actived - - - - - - - - - - - -:: PUT  :: JSON ::
|   2. Master Detail Process - - - - - - - -:: PAGE ::
|       2.1. View - - - - - - - - - - - - - :: PAGE ::
|       2.2. Get Datatables - - - - - - - - :: POST  :: JSON ::
|       2.3. Get Detail - - - - - - - - - - :: GET  :: JSON ::
|       2.4. Store - - - - - - - - - - - - -:: POST  :: JSON ::
|       2.5. Update - - - - - - - - - - - - :: PUT  :: JSON ::
|       2.6. Destroy - - - - - - - - - - - -:: DELETE  :: JSON ::
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
Route::post('/database_parts/master_process/logs', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@logs', 
    'as' => 'tms.db_parts.master.process.logs'
]);
Route::post('/database_parts/master_process/trash', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@trash',
    'as' => 'tms.db_parts.master.process.trash'
]);
Route::put('/database_parts/master_process/{id}/actived', [
    'uses' => 'TMS\DB_Parts\MasterProcessController@trashToActive', 
    'as' => 'tms.db_parts.master.process.trash_to_active'
]);

Route::get('/database_parts/master_detail_process', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@index', 
    'as' => 'tms.db_parts.master.detail_process'
]);
Route::post('/database_parts/master_detail_process/table_index', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@tableIndex', 
    'as' => 'tms.db_parts.master.detail_process.tbl_index'
]);
Route::post('/database_parts/master_detail_process/table_process', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@tableProcess', 
    'as' => 'tms.db_parts.master.detail_process.tbl_process'
]);
Route::get('/database_parts/master_detail_process/{id}/detail/', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@detail', 
    'as' => 'tms.db_parts.master.detail_process.detail'
]);
Route::post('/database_parts/master_detail_process/store', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@store', 
    'as' => 'tms.db_parts.master.detail_process.store'
]);
Route::put('/database_parts/master_detail_process/{id}/update', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@update', 
    'as' => 'tms.db_parts.master.detail_process.update'
]);
Route::delete('/database_parts/master_detail_process/{id}/destroy', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@destroy', 
    'as' => 'tms.db_parts.master.detail_process.destroy'
]);
Route::post('/database_parts/master_detail_process/logs', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@logs', 
    'as' => 'tms.db_parts.master.detail_process.logs'
]);
Route::post('/database_parts/master_detail_process/trash', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@trash',
    'as' => 'tms.db_parts.master.detail_process.trash'
]);
Route::put('/database_parts/master_detail_process/{id}/actived', [
    'uses' => 'TMS\DB_Parts\MasterDetailProcessController@trashToActive', 
    'as' => 'tms.db_parts.master.detail_process.trash_to_active'
]);

Route::get('/database_parts/input_parts', [
    'uses' => 'TMS\DB_Parts\InputPartsController@index', 
    'as' => 'tms.db_parts.input_parts'
]);
Route::get('/database_parts/input_parts/{id}/detail', [
    'uses' => 'TMS\DB_Parts\InputPartsController@detail', 
    'as' => 'tms.db_parts.input_parts.detail'
]);
Route::post('/database_parts/input_parts/table_index', [
    'uses' => 'TMS\DB_Parts\InputPartsController@tableIndex', 
    'as' => 'tms.db_parts.input_parts.tbl_index'
]);
Route::post('/database_parts/input_parts/upload_temp', [
    'uses' => 'TMS\DB_Parts\InputPartsController@uploadTemp', 
    'as' => 'tms.db_parts.input_parts.upload_temp'
]);
Route::post('/database_parts/input_parts/header_tools', [
    'uses' => 'TMS\DB_Parts\InputPartsController@headerTools', 
    'as' => 'tms.db_parts.input_parts.header_tools'
]);
Route::post('/database_parts/input_parts/store', [
    'uses' => 'TMS\DB_Parts\InputPartsController@store', 
    'as' => 'tms.db_parts.input_parts.store'
]);
Route::put('/database_parts/input_parts/{id}/update', [
    'uses' => 'TMS\DB_Parts\InputPartsController@update', 
    'as' => 'tms.db_parts.input_parts.update'
]);
Route::delete('/database_parts/input_parts/{id}/delete', [
    'uses' => 'TMS\DB_Parts\InputPartsController@destroy', 
    'as' => 'tms.db_parts.input_parts.destroy'
]);
Route::post('/database_parts/input_parts/table_trash', [
    'uses' => 'TMS\DB_Parts\InputPartsController@tableTrash', 
    'as' => 'tms.db_parts.input_parts.trash'
]);
Route::put('/database_parts/input_parts/{id}/trash_toactive', [
    'uses' => 'TMS\DB_Parts\InputPartsController@trashToActive', 
    'as' => 'tms.db_parts.input_parts.trash_to_active'
]);

Route::get('/database_parts/production_code', [
    'uses' => 'TMS\DB_Parts\ProductionCodeController@index', 
    'as' => 'tms.db_parts.production_code'
]);
Route::get('/database_parts/production_code/{id}/detail', [
    'uses' => 'TMS\DB_Parts\ProductionCodeController@detail', 
    'as' => 'tms.db_parts.production_code.detail'
]);
Route::post('/database_parts/production_code/table_index', [
    'uses' => 'TMS\DB_Parts\ProductionCodeController@tableIndex', 
    'as' => 'tms.db_parts.production_code.tbl_index'
]);