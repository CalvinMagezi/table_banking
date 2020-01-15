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

Route::get('/', function () {
    return view('welcome');
});


/*Route::get('/install', 'SetUpController@checkRequirements');
Route::get('/install/permissions', 'SetUpController@checkPermissions');
Route::post('/install/database', 'SetUpController@databaseSetup');
Route::post('/install/user', 'SetUpController@userSetup');*/

#Installation and setup
Route::group(array('prefix'=>'install'),function()
{
    Route::get('/', 'InstallationController@index');
    Route::get('/database', 'InstallationController@getDatabase');
    Route::post('/database', 'InstallationController@postDatabase');
    Route::get('/user', 'InstallationController@getUser');
    Route::post('/user', 'InstallationController@postUser');




    Route::get('/', 'SetUpController@checkRequirements');
    Route::get('/permissions', 'SetUpController@checkPermissions');
    Route::post('/database', 'SetUpController@databaseSetup');
    Route::post('/user', 'SetUpController@userSetup');
});