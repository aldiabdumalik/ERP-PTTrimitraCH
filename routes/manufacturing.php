<?php
    use Illuminate\Support\Facades\Route;
    /*
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |   TMS - MANUFACTURING - THP
    | +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    |
    */
    Route::get('/manufacturing/thp_entry', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@index', 
        'as' => 'tms.manufacturing.thp_entry'
    ]);
    Route::post('/manufacturing/thp_entry/dataTable_index', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@getThpTable', 
        'as' => 'tms.manufacturing.thp_entry.dataTable_index'
    ]);
    Route::get('/manufacturing/thp_entry/dataTable_edit/{id}', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@editThpTable', 
        'as' => 'tms.manufacturing.thp_entry.dataTable_edit'
    ]);
    Route::post('/manufacturing/thp_entry/dataTable_production', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@getProductionTable', 
        'as' => 'tms.manufacturing.thp_entry.dataTable_production'
    ]);
    Route::post('/manufacturing/thp_entry/post/thpentrycreate', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@createTHP', 
        'as' => 'tms.manufacturing.thp_entry.thpentry_create'
    ]);
    Route::get('/manufacturing/thp_entry/dataTable_log', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@getLogThp', 
        'as' => 'tms.manufacturing.thp_entry.dataTable_log'
    ]);
    Route::post('/manufacturing/thp_entry/post/thpentryclose', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@closeThpEntry', 
        'as' => 'tms.manufacturing.thp_entry.closeThpEntry'
    ]);
    Route::get('/manufacturing/thp_entry/thpentryprint', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@printThpEntry', 
        'as' => 'tms.manufacturing.thp_entry.printThpEntry'
    ]);
    Route::get('/manufacturing/thp_entry/thpentryprintxls', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@dailyExcel', 
        'as' => 'tms.manufacturing.thp_entry.daily_xls'
    ]);
    Route::post('/manufacturing/thp_entry/get/shiftgroupmachine', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@getShiftGroupMachine', 
        'as' => 'tms.manufacturing.thp_entry.getShiftGroupMachine'
    ]);
    Route::post('/manufacturing/thp_entry/post/thpimport', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@importToDB', 
        'as' => 'tms.manufacturing.thp_entry.importToDB'
    ]);
    Route::post('/manufacturing/thp_entry/post/thpsetting', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@settingThpEntry', 
        'as' => 'tms.manufacturing.thp_entry.settingThpEntry'
    ]);

    Route::get('/manufacturing/thp_entry/checking/{prodcode}/{date}', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@check', 
        'as' => 'tms.manufacturing.thp_entry.check'
    ]);
    Route::post('/manufacturing/thp_entry/save', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@save', 
        'as' => 'tms.manufacturing.thp_entry.save'
    ]);
    Route::put('/manufacturing/thp_entry/{prodcode}/update', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@update', 
        'as' => 'tms.manufacturing.thp_entry.update'
    ]);
    Route::get('/manufacturing/thp_entry/refresh/{date}', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@refresh_lhp', 
        'as' => 'tms.manufactuting.thp_entry.refresh'
    ]);
    Route::put('/manufacturing/thp_entry/thp/apnormal/{number}', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@note_apnormal', 
        'as' => 'tms.manufactuting.thp_entry.apnormal'
    ]);
    Route::get('/manufacturing/thp_entry/get_notif', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@getNotification', 
        'as' => 'tms.manufactuting.thp_entry.get_notif'
    ]);
    Route::get('/manufacturing/thp_entry/count_notif', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@count_notif', 
        'as' => 'tms.manufactuting.thp_entry.count_notif'
    ]);
    Route::post('/manufacturing/thp_entry/delete_notif', [
        'uses' => 'TMS\Manufacturing\ThpEntryController@deleteNotification', 
        'as' => 'tms.manufactuting.thp_entry.delete_notif'
    ]);