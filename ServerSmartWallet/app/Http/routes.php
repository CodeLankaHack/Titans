<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//route for creating users
Route::get('users/username/{username}/password/{password}/email/{email}/repassword/{repassword}/purse_id/{purse_id}','UsersController@create');

//route for login
Route::get('users/username/{username}/password/{password}','UsersController@login');

//route for creating logs
Route::get('logs/lan/{lan}/lat/{lat}/event/{event}/purse_id/{purse_id}','LogController@store');

//route for retrieve logs
Route::get('logs/startdate/{startdate}/enddate/{enddate}','LogController@get');

//route for retrieve last log
Route::get('logs/last','LogController@getLast');

//route for retrieve all logs
Route::get('logs/all','LogController@getAll');

//route for creating moneylogs
Route::get('moneylogs/lan/{lan}/lat/{lat}/money/{money}/purse_id/{purse_id}','MoneyLogController@store');

//route for retrieve moneylogs
Route::get('moneylogs/startdate/{startdate}/enddate/{enddate}','MoneyLogController@get');

//route for retrieve moneylogs for today
Route::get('moneylogs/today','MoneyLogController@getToday');

//route for retrieve all moneylogs
Route::get('moneylogs/all','MoneyLogController@getAll');