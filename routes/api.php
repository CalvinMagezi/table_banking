<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::namespace('Api')->prefix('v1')->group(function () {

    Route::resource('users', 'UserController', ['except' => ['create', 'edit']]);
    Route::resource('clients', 'ClientController', ['except' => ['create', 'edit']]);
    Route::resource('loans', 'LoanController', ['except' => ['create', 'edit']]);
    Route::resource('loan_types', 'LoanTypeController', ['except' => ['create', 'edit']]);

});



/*
//Route::group(array('prefix' => '/v1', 'middleware'=>'auth:api'), function () {
Route::group(array('prefix' => '/v1'), function () {

    //Route::post('/logout', 'Api\Auth\LoginController@logout');

    Route::resource('users', 'Api\UserController', ['except' => ['create', 'edit']]);

    Route::get('/me', 'Api\UserController@me');


});*/
