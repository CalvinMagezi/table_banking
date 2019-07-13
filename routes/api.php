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

/**
 * Routes to obtain access_token and manage token refresh
 */
Route::group(array('prefix' => '/v1'), function () {

    Route::post('/login', 'Api\Oauth\LoginController@login');
    Route::post('/login/refresh', 'Api\Oauth\LoginController@refresh');

});

//Route::namespace('Api')->prefix('v1')->middleware('auth:api')->group(function () {
Route::namespace('Api')->prefix('v1')->group(function () {

    Route::resource('users', 'UserController', ['except' => ['create', 'edit']]);
    Route::get('me', 'UserController@me');
    Route::resource('employees', 'EmployeeController', ['except' => ['create', 'edit']]);
    Route::resource('roles', 'RoleController', ['except' => ['create', 'edit']]);
    Route::resource('permissions', 'PermissionController', ['except' => ['create', 'edit']]);
    Route::resource('borrowers', 'BorrowerController', ['except' => ['create', 'edit']]);
    Route::resource('branches', 'BranchController', ['except' => ['create', 'edit']]);
    Route::resource('loans', 'LoanController', ['except' => ['create', 'edit']]);
    Route::resource('guarantors', 'GuarantorController', ['except' => ['create', 'edit']]);
    Route::resource('members', 'MemberController', ['except' => ['create', 'edit']]);
    Route::resource('loan_types', 'LoanTypeController', ['except' => ['create', 'edit']]);
    Route::resource('loan_applications', 'LoanApplicationController', ['except' => ['create', 'edit']]);
    Route::resource('loan_statuses', 'LoanStatusController', ['except' => ['create', 'edit']]);
    Route::resource('loan_application_statuses', 'LoanApplicationStatusController', ['except' => ['create', 'edit']]);
    Route::resource('borrower_statuses', 'BorrowerStatusController', ['except' => ['create', 'edit']]);
    Route::resource('payments', 'PaymentController', ['except' => ['create', 'edit']]);
    Route::resource('payment_methods', 'PaymentMethodController', ['except' => ['create', 'edit']]);
    Route::resource('general_settings', 'GeneralSettingController', ['except' => ['create', 'edit']]);
    Route::resource('assets', 'AssetController', ['except' => ['create', 'edit']]);
    Route::resource('asset_photos', 'AssetPhotoController', ['except' => ['create', 'edit']]);
    Route::resource('witness_types', 'WitnessTypeController', ['except' => ['create', 'edit']]);
    Route::get('/logout', 'Oauth\LoginController@logout');

});



/*
//Route::group(array('prefix' => '/v1', 'middleware'=>'auth:api'), function () {
Route::group(array('prefix' => '/v1'), function () {

    //Route::post('/logout', 'Api\Auth\LoginController@logout');

    Route::resource('users', 'Api\UserController', ['except' => ['create', 'edit']]);

    Route::get('/me', 'Api\UserController@me');


});*/
