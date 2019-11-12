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

/**
 * Routes to obtain access_token and manage token refresh
 */
Route::group(array('prefix' => '/v1'), function () {
    Route::post('/login', 'Api\Oauth\LoginController@login');
    Route::post('/login/refresh', 'Api\Oauth\LoginController@refresh');
    Route::post('forgot_password', 'Api\Oauth\ForgotPasswordController@forgotPassword');
});
/**
 * System routes
 */
Route::namespace('Api')->prefix('v1')->middleware('auth:api', 'throttle:60,1')->group(function () {

    Route::resource('users', 'UserController', ['except' => ['create', 'edit']]);
    Route::post('users/profile_pic', 'UserController@profilePic')->where(['file_name' => '.*']);

    Route::resource('employees', 'EmployeeController', ['except' => ['create', 'edit']]);
    Route::resource('roles', 'RoleController', ['except' => ['create', 'edit']]);
    Route::resource('permissions', 'PermissionController', ['except' => ['create', 'edit']]);
    Route::resource('borrowers', 'BorrowerController', ['except' => ['create', 'edit']]);
    Route::resource('branches', 'BranchController', ['except' => ['create', 'edit']]);
    Route::resource('loans', 'LoanController', ['except' => ['create', 'edit']]);
    Route::resource('guarantors', 'GuarantorController', ['except' => ['create', 'edit']]);

    Route::resource('members', 'MemberController', ['except' => ['create', 'edit']]);
    Route::post('members/profile_pic', 'MemberController@profilePic')->where(['file_name' => '.*']);

    Route::resource('loan_types', 'LoanTypeController', ['except' => ['create', 'edit']]);
    Route::resource('loan_applications', 'LoanApplicationController', ['except' => ['create', 'edit']]);
    Route::post('loan_applications/application_form', 'LoanApplicationController@applicationForm')->where(['file_name' => '.*']);
    Route::resource('loan_statuses', 'LoanStatusController', ['except' => ['create', 'edit']]);
    Route::resource('loan_application_statuses', 'LoanApplicationStatusController', ['except' => ['create', 'edit']]);
    Route::resource('borrower_statuses', 'BorrowerStatusController', ['except' => ['create', 'edit']]);
    Route::resource('payments', 'PaymentController', ['only' => ['index', 'store', 'show']]);
    Route::resource('payment_methods', 'PaymentMethodController', ['except' => ['create', 'edit']]);

    Route::resource('general_settings', 'GeneralSettingController', ['except' => ['create', 'edit']]);
    Route::post('general_settings/upload_logo', 'GeneralSettingController@uploadLogo');
    Route::post('general_settings/fetch_logo', 'GeneralSettingController@fetchLogo')->where(['file_name' => '.*']);

    Route::resource('assets', 'AssetController', ['except' => ['create', 'edit']]);
    Route::resource('asset_photos', 'AssetPhotoController', ['except' => ['create', 'edit']]);
    Route::resource('witness_types', 'WitnessTypeController', ['except' => ['create', 'edit']]);
    Route::resource('accounts', 'AccountController', ['only' => ['index', 'show']]);
    Route::resource('account_statuses', 'AccountStatusController', ['except' => ['create', 'edit']]);
    Route::resource('account_types', 'AccountTypeController', ['only' => ['index', 'show']]);
    Route::resource('email_settings', 'EmailSettingController', ['except' => ['create', 'edit']]);
    Route::resource('sms_settings', 'SmsSettingController', ['except' => ['create', 'edit']]);
    Route::resource('interest_types', 'InterestTypeController', ['except' => ['create', 'edit']]);
    Route::resource('expenses', 'ExpenseController', ['except' => ['create', 'edit']]);
    Route::resource('expense_categories', 'ExpenseCategoryController', ['except' => ['create', 'edit']]);
    Route::resource('fiscal_periods', 'FiscalPeriodController', ['except' => ['create', 'edit']]);
    Route::resource('journals', 'JournalController', ['except' => ['create', 'edit']]);
    Route::resource('transactions', 'TransactionController', ['except' => ['create', 'edit']]);
    Route::resource('transaction_types', 'TransactionTypeController', ['except' => ['create', 'edit']]);
    Route::resource('account_classes', 'AccountClassController', ['except' => ['create', 'edit']]);
    Route::resource('payment_frequencies', 'PaymentFrequencyController', ['except' => ['create', 'edit']]);
    Route::resource('summaries', 'SummaryController', ['only' => ['index']]);
    Route::resource('reports', 'ReportController', ['only' => ['index', 'show']]);
    Route::resource('finance_statements', 'FinanceStatementController', ['only' => ['index', 'store']]);
    Route::resource('capitals', 'CapitalController', ['except' => ['create', 'edit']]);
    Route::resource('email_templates', 'EmailTemplateController', ['only' => ['index', 'store']]);
    Route::resource('sms_templates', 'SmsTemplateController', ['only' => ['index', 'store']]);

    Route::resource('user_profile', 'UserProfileController', ['only' => ['index', 'update']]);

    Route::post('user_profile/upload_photo', 'UserProfileController@uploadPhoto');
    Route::post('user_profile/fetch_photo', 'UserProfileController@fetchPhoto')->where(['file_name' => '.*']);

    Route::post('user_profile/forgot_password', 'UserProfileController@forgotPassword');

    Route::get('/logout', 'Oauth\LoginController@logout');

    Route::resource('penalty_frequencies', 'PenaltyFrequencyController', ['except' => ['create', 'edit']]);
    Route::resource('penalty_types', 'PenaltyTypeController', ['except' => ['create', 'edit']]);

});

// Account constants
Route::group(['prefix' => ''], function() {
    // System accounts for each branch
    define('SERVICE_FEE_ACCOUNT_CODE','403');
    define('SERVICE_FEE_ACCOUNT_NAME','SERVICE FEE ACCOUNT');

    define('INTEREST_ACCOUNT_CODE','402');
    define('INTEREST_ACCOUNT_NAME','INTEREST ACCOUNT');

    define('PENALTY_ACCOUNT_CODE','401');
    define('PENALTY_ACCOUNT_NAME','PENALTY ACCOUNT');

    define('CAPITAL_ACCOUNT_CODE','101');
    define('CAPITAL_ACCOUNT_NAME','CAPITAL ACCOUNT');

    define('BANK_ACCOUNT_CODE','303');
    define('BANK_ACCOUNT_NAME','BANK ACCOUNT');

    define('MPESA_ACCOUNT_CODE','302');
    define('MPESA_ACCOUNT_NAME','MPESA ACCOUNT');

    define('CASH_ACCOUNT_CODE','301');
    define('CASH_ACCOUNT_NAME','CASH ACCOUNT');

    // Account Classes
    define('ASSET','ASSET');
    define('LIABILITY','LIABILITY');
    define('INCOME','INCOME');
    define('EXPENDITURE','EXPENDITURE');

    // Account Types
    define('EXPENSE','EXPENSE');
    define('EXPENSE_CODE','600');

    define('LOAN_RECEIVABLE','LOAN RECEIVABLE');
    define('LOAN_RECEIVABLE_CODE','500');

    define('LENDING_ACTIVITY','LENDING ACTIVITY');
    define('LENDING_ACTIVITY_CODE','400');

    define('CURRENT_ASSET','CURRENT ASSET');
    define('CURRENT_ASSET_CODE','300');

    define('FIXED_ASSET','FIXED ASSET');
    define('FIXED_ASSET_CODE','200');

    define('CAPITAL','CAPITAL');
    define('CAPITAL_CODE','100');

    //Member accounts - Individual loan receivable account
    define('MEMBER_ACCOUNT_CODE','555');

    // Expense categories
    define('EXPENSE_CATEGORY_CODE','601');

});
