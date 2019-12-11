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
    Route::post('forgot_password', 'Api\Oauth\ForgotPasswordController');
   // Route::post('forgot_password', 'Api\Oauth\ForgotPasswordController@forgotPassword');
    Route::post('password/reset', 'Api\Oauth\ForgotPasswordController')->name('password.reset');

});
/**
 * System routes
 */
Route::namespace('Api')->prefix('v1')->middleware('auth:api', 'throttle:60,1')->group(function () {

    Route::resource('users', 'UserController', ['except' => ['create', 'edit']])
        ->middleware('scopes:settings-users');
        //->middleware('scopes:settings,user-add,user-delete,user-edit');
    Route::resource('roles', 'RoleController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-users']);
    Route::resource('permissions', 'PermissionController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-users']);

   // Route::post('users/profile_pic', 'UserController@profilePic')->where(['file_name' => '.*']);
    // ->middleware(['scope:view-user'])

    Route::resource('employees', 'EmployeeController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-users']);

    Route::resource('borrowers', 'BorrowerController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-borrowers']);
    Route::resource('borrower_statuses', 'BorrowerStatusController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-borrowers']);

    Route::resource('branches', 'BranchController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-branches']);

    Route::resource('members', 'MemberController', ['except' => ['create', 'edit']])
        ->middleware(['scope:member-add']);
    Route::post('members/profile_pic', 'MemberController@profilePic')->where(['file_name' => '.*'])
        ->middleware(['scope:member-add']);
    Route::post('members/membership_form', 'MemberController@membershipForm')->where(['file_name' => '.*'])
        ->middleware(['scope:member-add']);
    Route::post('members/membership_form_update', 'MemberController@updateMembershipForm')
        ->middleware(['scope:member-add']);
    Route::post('members/fetch_photo', 'MemberController@fetchPhoto')->where(['file_name' => '.*'])
        ->middleware(['scope:member-add']);
    Route::post('members/update_photo', 'MemberController@updatePhoto')
        ->middleware(['scope:member-add']);

    Route::resource('loans', 'LoanController', ['except' => ['create', 'edit']])
        ->middleware(['scope:loans-view']);

    Route::resource('loan_types', 'LoanTypeController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-loans']);
    Route::resource('loan_applications', 'LoanApplicationController', ['except' => ['create', 'edit']])
        ->middleware(['scope:loan-application-add']);
    Route::post('loan_applications/application_form', 'LoanApplicationController@applicationForm')->where(['file_name' => '.*'])
        ->middleware(['scope:loan-application-add']);
    Route::post('loan_applications/application_form_update', 'LoanApplicationController@updateApplicationForm')
        ->middleware(['scope:loan-application-add']);

    Route::resource('loan_statuses', 'LoanStatusController', ['except' => ['create', 'edit']]);
    Route::resource('loan_application_statuses', 'LoanApplicationStatusController', ['except' => ['create', 'edit']]);

    Route::resource('loan_penalties', 'LoanPenaltyController', ['only' => ['index', 'show']]);
    Route::post('loan_penalties/waive', 'LoanPenaltyController@waive')
        ->middleware(['scope:loans-view']);

    Route::resource('loan_interests', 'LoanInterestRepaymentController', ['only' => ['index', 'show']]);
    Route::resource('loan_principals', 'LoanPrincipalRepaymentController', ['only' => ['index', 'show']]);

    Route::resource('payments', 'PaymentController', ['only' => ['index', 'store', 'show']])
        ->middleware(['scope:payments-add']);
    Route::resource('payment_methods', 'PaymentMethodController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-payments']);

    Route::resource('general_settings', 'GeneralSettingController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-general']);
    Route::post('general_settings/upload_logo', 'GeneralSettingController@uploadLogo')
        ->middleware(['scope:settings-general']);
    Route::post('general_settings/fetch_logo', 'GeneralSettingController@fetchLogo')->where(['file_name' => '.*'])
        ->middleware(['scope:settings-general']);

    Route::resource('assets', 'AssetController', ['except' => ['create', 'edit']])
        ->middleware(['scope:member-add']);
    Route::resource('asset_photos', 'AssetPhotoController', ['except' => ['create', 'edit']])
        ->middleware(['scope:member-add']);

    Route::resource('witness_types', 'WitnessTypeController', ['except' => ['create', 'edit']]);
    Route::resource('accounts', 'AccountController', ['only' => ['index', 'show']]);
    Route::resource('account_statuses', 'AccountStatusController', ['except' => ['create', 'edit']]);
    Route::resource('account_types', 'AccountTypeController', ['only' => ['index', 'show']]);
    Route::resource('interest_types', 'InterestTypeController', ['except' => ['create', 'edit']]);
    Route::resource('fiscal_periods', 'FiscalPeriodController', ['except' => ['create', 'edit']]);
    Route::resource('journals', 'JournalController', ['except' => ['create', 'edit']]);
    Route::resource('transactions', 'TransactionController', ['except' => ['create', 'edit']]);
    Route::resource('transaction_types', 'TransactionTypeController', ['except' => ['create', 'edit']]);
    Route::resource('account_classes', 'AccountClassController', ['except' => ['create', 'edit']]);
    Route::resource('payment_frequencies', 'PaymentFrequencyController', ['except' => ['create', 'edit']]);
    Route::resource('summaries', 'SummaryController', ['only' => ['index']]);
    Route::resource('guarantors', 'GuarantorController', ['except' => ['create', 'edit']]);

    Route::resource('penalty_frequencies', 'PenaltyFrequencyController', ['except' => ['create', 'edit']]);
    Route::resource('penalty_types', 'PenaltyTypeController', ['except' => ['create', 'edit']]);

    Route::post('user_profile/forgot_password', 'UserProfileController@forgotPassword');

    Route::resource('email_settings', 'EmailSettingController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-communication']);
    Route::resource('sms_settings', 'SmsSettingController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-communication']);
    Route::resource('email_templates', 'EmailTemplateController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-communication']);
    Route::resource('sms_templates', 'SmsTemplateController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-communication']);

    Route::resource('communication_settings', 'CommunicationSettingController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-communication']);

    Route::resource('expenses', 'ExpenseController', ['except' => ['create', 'edit']])
        ->middleware(['scope:expense-add']);
    Route::resource('expense_categories', 'ExpenseCategoryController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-expenses']);

    Route::resource('reports', 'ReportController', ['only' => ['index', 'show']])
        ->middleware(['scope:view-reports']);
    Route::resource('finance_statements', 'FinanceStatementController', ['only' => ['index', 'store']])
        ->middleware(['scope:view-reports']);

    Route::resource('capitals', 'CapitalController', ['except' => ['create', 'edit']])
        ->middleware(['scope:settings-accounting']);

    Route::resource('user_profile', 'UserProfileController', ['only' => ['index', 'update']])
        ->middleware(['scope:my-profile']);
    Route::post('user_profile/upload_photo', 'UserProfileController@uploadPhoto')
        ->middleware(['scope:my-profile']);
    Route::post('user_profile/fetch_photo', 'UserProfileController@fetchPhoto')->where(['file_name' => '.*'])
        ->middleware(['scope:my-profile']);

    Route::resource('loan_histories', 'LoanHistoryController', ['only' => ['index']]);
    Route::get('/logout', 'Oauth\LoginController@logout');
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
