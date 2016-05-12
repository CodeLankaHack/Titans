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
