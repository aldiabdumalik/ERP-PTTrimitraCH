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

Route::get('/engineering/database_parts', [
    'uses' => 'TMS\Engineering\DBPartController@index', 
    'as' => 'tms.db_parts'
]);
Route::post('/engineering/database_parts/dt_projects', [
    'uses' => 'TMS\Engineering\DBPartController@dtProjects', 
    'as' => 'tms.db_parts.projects.dt'
]);
Route::get('/engineering/database_parts/projects/{id}', [
    'uses' => 'TMS\Engineering\DBPartController@detail', 
    'as' => 'tms.db_parts.projects.detail'
]);
Route::post('/engineering/database_parts/projects/save', [
    'uses' => 'TMS\Engineering\DBPartController@store', 
    'as' => 'tms.db_parts.projects.store'
]);
Route::put('/engineering/database_parts/projects/{id}/update', [
    'uses' => 'TMS\Engineering\DBPartController@update', 
    'as' => 'tms.db_parts.projects.update'
]);
Route::post('/engineering/database_parts/projects/tools', [
    'uses' => 'TMS\Engineering\DBPartController@tools', 
    'as' => 'tms.db_parts.projects.tools'
]);
Route::delete('/engineering/database_parts/projects/{id}/delete', [
    'uses' => 'TMS\Engineering\DBPartController@destroy', 
    'as' => 'tms.db_parts.projects.destroy'
]);
Route::put('/engineering/database_parts/projects/{id}/actived', [
    'uses' => 'TMS\Engineering\DBPartController@toActive', 
    'as' => 'tms.db_parts.projects.to_active'
]);
Route::post('/engineering/database_parts/projects/{id}/posted', [
    'uses' => 'TMS\Engineering\DBPartController@postedRevision', 
    'as' => 'tms.db_parts.projects.posted'
]);
Route::get('/engineering/database_parts/projects/{id}/rev_logs', [
    'uses' => 'TMS\Engineering\DBPartController@revLogs', 
    'as' => 'tms.db_parts.projects.rev_logs'
]);
Route::post('/engineering/database_parts/projects/approved/this', [
    'uses' => 'TMS\Engineering\DBPartController@approvedPost', 
    'as' => 'tms.db_parts.projects.approved'
]);
Route::post('/engineering/database_parts/projects/published/this', [
    'uses' => 'TMS\Engineering\DBPartController@published', 
    'as' => 'tms.db_parts.projects.published'
]);

Route::get('/engineering/database_parts/parts/{type}/view', [
    'uses' => 'TMS\Engineering\PartsController@index', 
    'as' => 'tms.db_parts.parts.index'
]);
Route::get('/engineering/database_parts/parts/{id}/detail', [
    'uses' => 'TMS\Engineering\PartsController@detail', 
    'as' => 'tms.db_parts.parts.detail'
]);
Route::post('/engineering/database_parts/parts/dt', [
    'uses' => 'TMS\Engineering\PartsController@dt', 
    'as' => 'tms.db_parts.parts.dt'
]);
Route::post('/engineering/database_parts/parts/tools', [
    'uses' => 'TMS\Engineering\PartsController@tools', 
    'as' => 'tms.db_parts.parts.tools'
]);
Route::post('/engineering/database_parts/parts/save', [
    'uses' => 'TMS\Engineering\PartsController@store', 
    'as' => 'tms.db_parts.parts.store'
]);
Route::put('/engineering/database_parts/parts/{id}/update', [
    'uses' => 'TMS\Engineering\PartsController@update', 
    'as' => 'tms.db_parts.parts.update'
]);
Route::post('/engineering/database_parts/parts/upload_temp', [
    'uses' => 'TMS\Engineering\PartsController@uploadTemp', 
    'as' => 'tms.db_parts.parts.upload_temp'
]);
Route::delete('/engineering/database_parts/parts/{id}/delete', [
    'uses' => 'TMS\Engineering\PartsController@destroy', 
    'as' => 'tms.db_parts.parts.destroy'
]);
Route::put('/engineering/database_parts/parts/{id}/to_active', [
    'uses' => 'TMS\Engineering\PartsController@toActive', 
    'as' => 'tms.db_parts.parts.to_active'
]);
Route::post('/engineering/database_parts/parts/table_trash', [
    'uses' => 'TMS\Engineering\PartsController@tableTrash', 
    'as' => 'tms.db_parts.parts.table_trash'
]);
Route::get('/engineering/database_parts/parts/{part_id}/production_process', [
    'uses' => 'TMS\Engineering\PartsController@productionProcess', 
    'as' => 'tms.db_parts.parts.prodpro'
]);
Route::get('/engineering/database_parts/parts/production_process/{id_part}/dt', [
    'uses' => 'TMS\Engineering\PartsController@dtProductionProcess', 
    'as' => 'tms.db_parts.parts.prodpro.dt'
]);
Route::post('/engineering/database_parts/parts/production_process/save', [
    'uses' => 'TMS\Engineering\PartsController@storeProductionProcess', 
    'as' => 'tms.db_parts.parts.prodpro.store'
]);
Route::put('/engineering/database_parts/parts/production_process/{id_part}/update', [
    'uses' => 'TMS\Engineering\PartsController@updateProductionProcess', 
    'as' => 'tms.db_parts.parts.prodpro.update'
]);
Route::get('/engineering/database_parts/report/print/{type}', [
    'uses' => 'TMS\Engineering\DBPartReportController@report', 
    'as' => 'tms.db_parts.report.print'
]);


// Route::get('/engineering/database_parts/item/{type}/view', [
//     'uses' => 'TMS\Engineering\InputPartsController@index', 
//     'as' => 'tms.db_parts.input_parts.index'
// ]);
// Route::get('/engineering/database_parts/item/{id}/detail', [
//     'uses' => 'TMS\Engineering\InputPartsController@detail', 
//     'as' => 'tms.db_parts.input_parts.detail'
// ]);
// Route::post('/engineering/database_parts/item/table_index', [
//     'uses' => 'TMS\Engineering\InputPartsController@tableIndex', 
//     'as' => 'tms.db_parts.input_parts.tbl_index'
// ]);
// Route::post('/engineering/database_parts/item/upload_temp', [
//     'uses' => 'TMS\Engineering\InputPartsController@uploadTemp', 
//     'as' => 'tms.db_parts.input_parts.upload_temp'
// ]);
// Route::post('/engineering/database_parts/item/header_tools', [
//     'uses' => 'TMS\Engineering\InputPartsController@headerTools', 
//     'as' => 'tms.db_parts.input_parts.header_tools'
// ]);
// Route::post('/engineering/database_parts/item/store', [
//     'uses' => 'TMS\Engineering\InputPartsController@store', 
//     'as' => 'tms.db_parts.input_parts.store'
// ]);
// Route::put('/engineering/database_parts/item/{id}/update', [
//     'uses' => 'TMS\Engineering\InputPartsController@update', 
//     'as' => 'tms.db_parts.input_parts.update'
// ]);
// Route::delete('/engineering/database_parts/item/{id}/delete', [
//     'uses' => 'TMS\Engineering\InputPartsController@destroy', 
//     'as' => 'tms.db_parts.input_parts.destroy'
// ]);
// Route::post('/engineering/database_parts/item/table_trash', [
//     'uses' => 'TMS\Engineering\InputPartsController@tableTrash', 
//     'as' => 'tms.db_parts.input_parts.trash'
// ]);
// Route::put('/engineering/database_parts/item/{id}/trash_toactive', [
//     'uses' => 'TMS\Engineering\InputPartsController@trashToActive', 
//     'as' => 'tms.db_parts.input_parts.trash_to_active'
// ]);
// Route::post('/engineering/database_parts/item/revision', [
//     'uses' => 'TMS\Engineering\InputPartsController@revision', 
//     'as' => 'tms.db_parts.input_parts.revision'
// ]);

// Route::get('/engineering/production_code', [
//     'uses' => 'TMS\Engineering\ProductionCodeController@index', 
//     'as' => 'tms.db_parts.production_code'
// ]);
// Route::get('/engineering/production_code/{id}/detail', [
//     'uses' => 'TMS\Engineering\ProductionCodeController@detail', 
//     'as' => 'tms.db_parts.production_code.detail'
// ]);
// Route::post('/engineering/production_code/table_index', [
//     'uses' => 'TMS\Engineering\ProductionCodeController@tableIndex', 
//     'as' => 'tms.db_parts.production_code.tbl_index'
// ]);
// Route::post('/engineering/production_code/save', [
//     'uses' => 'TMS\Engineering\ProductionCodeController@store', 
//     'as' => 'tms.db_parts.production_code.store'
// ]);
// Route::post('/engineering/production_code/header_tools', [
//     'uses' => 'TMS\Engineering\ProductionCodeController@headerTools', 
//     'as' => 'tms.db_parts.production_code.header_tools'
// ]);

// Route::get('/engineering/report', [
//     'uses' => 'TMS\Engineering\DBPartReportController@index', 
//     'as' => 'tms.db_parts.report'
// ]);
// Route::get('/engineering/report/part_type/{customer}', [
//     'uses' => 'TMS\Engineering\DBPartReportController@partsType', 
//     'as' => 'tms.db_parts.report.parts'
// ]);
// Route::get('/engineering/report/print', [
//     'uses' => 'TMS\Engineering\DBPartReportController@report', 
//     'as' => 'tms.db_parts.report.print'
// ]);