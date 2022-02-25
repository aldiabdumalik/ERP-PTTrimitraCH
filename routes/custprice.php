<?php
use Illuminate\Support\Facades\Route;
Route::get('/master/customer_price', [
    'uses' => 'TMS\Master\CustPriceController@index', 
    'as' => 'tms.master.cust_price'
]);
Route::post('/master/customer_price/table-index', [
    'uses' => 'TMS\Master\CustPriceController@custPriceTable', 
    'as' => 'tms.master.cust_price.index'
]);
Route::post('/master/customer_price/header', [
    'uses' => 'TMS\Master\CustPriceController@headerTools', 
    'as' => 'tms.master.cust_price.header'
]);
Route::get('/master/customer_price/detail/{cust}/{date}', [
    'uses' => 'TMS\Master\CustPriceController@custPriceDetail', 
    'as' => 'tms.master.cust_price.detail'
]);
Route::get('/master/customer_price/items', [
    'uses' => 'TMS\Master\CustPriceController@getitems', 
    'as' => 'tms.master.cust_price.getitems'
]);
Route::post('/master/customer_price/save', [
    'uses' => 'TMS\Master\CustPriceController@save', 
    'as' => 'tms.master.cust_price.save'
]);
Route::put('/master/customer_price/update/{cust}/{active}', [
    'uses' => 'TMS\Master\CustPriceController@update', 
    'as' => 'tms.master.cust_price.update'
]);
Route::post('/master/customer_price/voided', [
    'uses' => 'TMS\Master\CustPriceController@voided', 
    'as' => 'tms.master.cust_price.voided'
]);
Route::post('/master/customer_price/unvoided', [
    'uses' => 'TMS\Master\CustPriceController@unvoided', 
    'as' => 'tms.master.cust_price.unvoided'
]);
Route::post('/master/customer_price/posted', [
    'uses' => 'TMS\Master\CustPriceController@posted', 
    'as' => 'tms.master.cust_price.posted'
]);
Route::post('/master/customer_price/unposted', [
    'uses' => 'TMS\Master\CustPriceController@unposted', 
    'as' => 'tms.master.cust_price.unposted'
]);
Route::get('/master/customer_price/{code}/print', [
    'uses' => 'TMS\Master\CustPriceController@print', 
    'as' => 'tms.master.cust_price.print'
]);
Route::get('/master/customer_price/trigger', [
    'uses' => 'TMS\Master\CustPriceController@trigger', 
    'as' => 'tms.master.cust_price.trigger'
]);