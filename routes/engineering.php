<?php
use Illuminate\Support\Facades\Route;

Route::get('/engineering/master_process', [
    'uses' => 'TMS\Engineering\MasterProcessController@index', 
    'as' => 'tms.db_parts.master.process'
]);
Route::post('/engineering/master_process/table_index', [
    'uses' => 'TMS\Engineering\MasterProcessController@tableIndex', 
    'as' => 'tms.db_parts.master.process.tbl_index'
]);
Route::get('/engineering/master_process/{id}/detail/', [
    'uses' => 'TMS\Engineering\MasterProcessController@detail', 
    'as' => 'tms.db_parts.master.process.detail'
]);
Route::post('/engineering/master_process/store', [
    'uses' => 'TMS\Engineering\MasterProcessController@store', 
    'as' => 'tms.db_parts.master.process.store'
]);
Route::put('/engineering/master_process/{id}/update', [
    'uses' => 'TMS\Engineering\MasterProcessController@update', 
    'as' => 'tms.db_parts.master.process.update'
]);
Route::delete('/engineering/master_process/{id}/destroy', [
    'uses' => 'TMS\Engineering\MasterProcessController@destroy', 
    'as' => 'tms.db_parts.master.process.destroy'
]);
Route::post('/engineering/master_process/logs', [
    'uses' => 'TMS\Engineering\MasterProcessController@logs', 
    'as' => 'tms.db_parts.master.process.logs'
]);
Route::post('/engineering/master_process/trash', [
    'uses' => 'TMS\Engineering\MasterProcessController@trash',
    'as' => 'tms.db_parts.master.process.trash'
]);
Route::put('/engineering/master_process/{id}/actived', [
    'uses' => 'TMS\Engineering\MasterProcessController@trashToActive', 
    'as' => 'tms.db_parts.master.process.trash_to_active'
]);

Route::get('/engineering/master_detail_process', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@index', 
    'as' => 'tms.db_parts.master.detail_process'
]);
Route::post('/engineering/master_detail_process/table_index', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@tableIndex', 
    'as' => 'tms.db_parts.master.detail_process.tbl_index'
]);
Route::post('/engineering/master_detail_process/table_process', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@tableProcess', 
    'as' => 'tms.db_parts.master.detail_process.tbl_process'
]);
Route::get('/engineering/master_detail_process/{id}/detail/', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@detail', 
    'as' => 'tms.db_parts.master.detail_process.detail'
]);
Route::post('/engineering/master_detail_process/store', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@store', 
    'as' => 'tms.db_parts.master.detail_process.store'
]);
Route::put('/engineering/master_detail_process/{id}/update', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@update', 
    'as' => 'tms.db_parts.master.detail_process.update'
]);
Route::delete('/engineering/master_detail_process/{id}/destroy', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@destroy', 
    'as' => 'tms.db_parts.master.detail_process.destroy'
]);
Route::post('/engineering/master_detail_process/logs', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@logs', 
    'as' => 'tms.db_parts.master.detail_process.logs'
]);
Route::post('/engineering/master_detail_process/trash', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@trash',
    'as' => 'tms.db_parts.master.detail_process.trash'
]);
Route::put('/engineering/master_detail_process/{id}/actived', [
    'uses' => 'TMS\Engineering\MasterDetailProcessController@trashToActive', 
    'as' => 'tms.db_parts.master.detail_process.trash_to_active'
]);

Route::get('/engineering/input_parts', [
    'uses' => 'TMS\Engineering\InputPartsController@index', 
    'as' => 'tms.db_parts.input_parts'
]);
Route::get('/engineering/input_parts/{id}/detail', [
    'uses' => 'TMS\Engineering\InputPartsController@detail', 
    'as' => 'tms.db_parts.input_parts.detail'
]);
Route::post('/engineering/input_parts/table_index', [
    'uses' => 'TMS\Engineering\InputPartsController@tableIndex', 
    'as' => 'tms.db_parts.input_parts.tbl_index'
]);
Route::post('/engineering/input_parts/upload_temp', [
    'uses' => 'TMS\Engineering\InputPartsController@uploadTemp', 
    'as' => 'tms.db_parts.input_parts.upload_temp'
]);
Route::post('/engineering/input_parts/header_tools', [
    'uses' => 'TMS\Engineering\InputPartsController@headerTools', 
    'as' => 'tms.db_parts.input_parts.header_tools'
]);
Route::post('/engineering/input_parts/store', [
    'uses' => 'TMS\Engineering\InputPartsController@store', 
    'as' => 'tms.db_parts.input_parts.store'
]);
Route::put('/engineering/input_parts/{id}/update', [
    'uses' => 'TMS\Engineering\InputPartsController@update', 
    'as' => 'tms.db_parts.input_parts.update'
]);
Route::delete('/engineering/input_parts/{id}/delete', [
    'uses' => 'TMS\Engineering\InputPartsController@destroy', 
    'as' => 'tms.db_parts.input_parts.destroy'
]);
Route::post('/engineering/input_parts/table_trash', [
    'uses' => 'TMS\Engineering\InputPartsController@tableTrash', 
    'as' => 'tms.db_parts.input_parts.trash'
]);
Route::put('/engineering/input_parts/{id}/trash_toactive', [
    'uses' => 'TMS\Engineering\InputPartsController@trashToActive', 
    'as' => 'tms.db_parts.input_parts.trash_to_active'
]);

Route::get('/engineering/production_code', [
    'uses' => 'TMS\Engineering\ProductionCodeController@index', 
    'as' => 'tms.db_parts.production_code'
]);
Route::get('/engineering/production_code/{id}/detail', [
    'uses' => 'TMS\Engineering\ProductionCodeController@detail', 
    'as' => 'tms.db_parts.production_code.detail'
]);
Route::post('/engineering/production_code/table_index', [
    'uses' => 'TMS\Engineering\ProductionCodeController@tableIndex', 
    'as' => 'tms.db_parts.production_code.tbl_index'
]);
Route::post('/engineering/production_code/save', [
    'uses' => 'TMS\Engineering\ProductionCodeController@store', 
    'as' => 'tms.db_parts.production_code.store'
]);
Route::post('/engineering/production_code/header_tools', [
    'uses' => 'TMS\Engineering\ProductionCodeController@headerTools', 
    'as' => 'tms.db_parts.production_code.header_tools'
]);

Route::get('/engineering/report', [
    'uses' => 'TMS\Engineering\DBPartReportController@index', 
    'as' => 'tms.db_parts.report'
]);
Route::get('/engineering/report/part_type/{customer}', [
    'uses' => 'TMS\Engineering\DBPartReportController@partsType', 
    'as' => 'tms.db_parts.report.parts'
]);
Route::get('/engineering/report/print', [
    'uses' => 'TMS\Engineering\DBPartReportController@report', 
    'as' => 'tms.db_parts.report.print'
]);