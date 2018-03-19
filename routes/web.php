<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//add new 
Route::get('/simulation','FillController@lister');



//new interface
Route::any('/add/printer','AddController@printer');
Route::any('/add/factorie','AddController@factorie');
Route::any('/add/country','AddController@country');

Route::any('/','MainController@continent');
Route::any('/{continent}','MainController@country');
Route::any('/country/{country}','MainController@index');

Route::any('/country/{country}/printer','PrinterController@index');
Route::any('/country/{country}/printer/info','PrinterController@info');
Route::any('/country/{country}/printer/factorie','PrinterController@factorie');

Route::any('/country/{country}/user','UserController@index');
Route::any('/country/{country}/user/info','UserController@info');

Route::any('/country/{country}/export','ExportController@index');

//end new interface

//localization
Route::get('/lang/{lang}', 'LocalController@index');


//test parser
Route::any('/parse/all-updates','ParseController@allUpdates');
