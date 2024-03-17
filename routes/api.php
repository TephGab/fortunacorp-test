<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CashinController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CashoutController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\AgentLoanController;
use App\Http\Controllers\Api\SendMoneyController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\ExpenseTypeController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserActivitController;
use App\Http\Controllers\Api\AgentDepositController;
use App\Http\Controllers\Api\EnvoyDepositController;
use App\Http\Controllers\Api\ReplenishmentController;
use App\Http\Controllers\Api\AccountActivitController;
use App\Http\Controllers\Api\AgentLoanRepayController;
use App\Http\Controllers\Api\EnvoyTransfertController;
use App\Http\Controllers\Api\ProviderPaymentController;
use App\Http\Controllers\Api\UserBankAccountController;
use App\Http\Controllers\Api\AccountTransfertController;
use App\Http\Controllers\Api\RoleAndPermissionController;
use App\Http\Controllers\Api\SystemBankAccountController;
use App\Http\Controllers\Api\UserActiveSessionController;
use App\Http\Controllers\Api\ReplenishmentRequirementController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('logout-other-sessions', [UserController::class,'logoutOtherSessions']);

Route::apiResource('transactions', TransactionController::class);
Route::get('transactions/{q?}', [TransactionController::class, 'index']);
Route::get('completedtransactions', [TransactionController::class, 'getCompletedTransactions']);
Route::post('updatetransactionstatus', [TransactionController::class, 'updateTransactionStatus']);
Route::post('updatetransactionstatuscompleted', [TransactionController::class, 'updateTransactionStatusCompleted']);
Route::post('accountstransaction', [TransactionController::class, 'getAccountsTransac']);
Route::post('transactionsort', [TransactionController::class, 'transactionSort']);
Route::post('reviewdisapprovedtransaction', [TransactionController::class, 'reviewDisapprovedTransaction']);
Route::post('transactions/search', [TransactionController::class, 'transactionSearch']);
// Route::get('search_transactions/{q?}', [TransactionController::class, 'transactionSearch']);

Route::get('departmentforuser', [DepartmentController::class, 'getDepartmentForUser']);

Route::apiResource('dailyrate', RateController::class);
Route::post('dailyrateupdate', [RateController::class, 'dailyRateUpdate']);

Route::apiResource('accounts', AccountController::class);
Route::post('accountsort', [AccountController::class, 'accountSort']);
Route::post('accountsearchbynumber', [AccountController::class, 'searchBynumber']);
Route::post('accountsearchbyname', [AccountController::class, 'searchByName']);
// Route::get('account/search/{q?}', [AccountController::class, 'search']);
Route::post('accounttotalmonthlytransactions', [AccountController::class, 'getAccountTotalMonthlyTransactions']);

Route::apiResource('accountstransferts', AccountTransfertController::class);
Route::post('getaccountstransferts', [AccountTransfertController::class, 'getAccountsTransferts']);

Route::apiResource('providers', ProviderController::class);

Route::apiResource('providerpayments', ProviderPaymentController::class);

Route::get('providerstopay', [ProviderPaymentController::class, 'getProvidersToPay']);

Route::apiResource('cashins', CashinController::class);
Route::post('accountscashin', [CashinController::class, 'getAccountsCashin']);
Route::post('checkprovider', [CashinController::class, 'checkIfProviderExist']);
Route::post('cashinsort', [CashinController::class, 'cashinSort']);

Route::apiResource('users', UserController::class);
Route::get('searchref', [UserController::class, 'searchReference']);
Route::post('getref', [UserController::class, 'getReference']);
Route::post('useraccount', [UserController::class, 'getUserAccount']);
Route::put('updatepassword/{id}', [UserController::class, 'updatePassword']);
Route::post('resetpassword', [UserController::class, 'resetPassword']);
Route::get('envoys', [UserController::class, 'getEnvoys']);
Route::get('agents', [UserController::class, 'getAgents']);
Route::get('operators', [UserController::class, 'getOperators']);
Route::post('agent', [UserController::class, 'getAgent']);

Route::get('users/search/{q?}', [UserController::class, 'search']);

Route::apiResource('departments', DepartmentController::class);
Route::get('departmentsnopaginate', [DepartmentController::class, 'getDepartmentsNoPaginate']);

Route::post('createpermission', [RoleAndPermissionController::class, 'createPermission']);

Route::post('removepermissionfromuser', [UserController::class, 'removePermissionFromUser']);

Route::post('currentpermissionsonuseredit', [UserController::class, 'geCurrentPermissionsOnUserEdit']);
Route::post('availablepermissionsonuseredit', [UserController::class, 'getAvailablePermissionsOnUserEdit']);

Route::post('rolepermissions', [RoleAndPermissionController::class, 'getPermissionsForRole']);
Route::post('availablepermissionsonroleedit', [RoleAndPermissionController::class, 'getAvailablePermissionsOnRoleEdit']);
Route::post('addpermissiontorole', [RoleAndPermissionController::class, 'addPermissionToRole']);
Route::post('removepermissionfromrole', [RoleAndPermissionController::class, 'removePermissionFromRole']);
Route::post('editrole', [RoleAndPermissionController::class, 'editRole']);
Route::post('newsuperadminpermission', [RoleAndPermissionController::class, 'newSuperAdminPermission']);
Route::post('newpermissions', [RoleAndPermissionController::class, 'addNewPermissions']);
Route::apiResource('roleandpermission', RoleAndPermissionController::class);
Route::get('getauthuser', [UserController::class, 'getAuthUser']);
Route::get('authuserrole', [UserController::class, 'getAuthUserRole']);
Route::get('getroles', [UserController::class, 'getRoles']);
Route::get('getpermissions', [UserController::class, 'getPermissions']);
Route::get('userbankaccounts', [UserBankAccountController::class, 'getUserBankAccount']);
Route::get('systembankaccounts', [SystemBankAccountController::class, 'getSystemBankAccount']);
Route::apiResource('cashouts', CashoutController::class);
Route::post('checkpendingcashout', [CashoutController::class, 'checkPendingCashout']);
Route::post('getcashoutdetails', [CashoutController::class, 'getCashoutDetails']);
Route::post('confirmcashout', [CashoutController::class, 'confirmCashout']);
Route::post('completecashout', [CashoutController::class, 'completeCashout']);
Route::post('usercashout', [CashoutController::class, 'userCashout']);
Route::get('totalcashoutscount', [CashoutController::class, 'getTotalCashoutsCount']);
Route::post('cashoutsearchbyname', [CashoutController::class, 'searchbyname']);

Route::apiResource('agentdeposits', AgentDepositController::class);
Route::post('updateagentdeposit', [AgentDepositController::class, 'updateDeposit']);

Route::post('checkpendingdeposit', [AgentDepositController::class, 'checkPendingDeposit']);
Route::post('getenvoydeposit', [AgentDepositController::class, 'getEnvoyDeposit']);
Route::post('getagentdepositdetails', [AgentDepositController::class, 'getAgentDepositDetails']);
Route::post('confirmagentdeposit', [AgentDepositController::class, 'confirmAgentDeposit']);
Route::get('totalagentdepositscount', [AgentDepositController::class, 'getAgentDepositsCount']);
Route::post('agentdepositsearchbyname', [AgentDepositController::class, 'searchByName']);

Route::apiResource('userbankaccount', UserBankAccountController::class);
Route::apiResource('systembankaccount', SystemBankAccountController::class);

Route::get('notifications', [TransactionController::class, 'notification']);
Route::post('gettransactioninfo', [TransactionController::class, 'getTransactionInfo']);
Route::post('checkclient', [TransactionController::class, 'checkIfClientExist']);
// Route::post('dailyrateupdate', [TransactionController::class, 'dailyRateUpdate']);

Route::post('gettotaltransaction', [TransactionController::class, 'getTotalTransaction']);
Route::post('gettotalusertransaction', [TransactionController::class, 'getTotalUserTransaction']);
Route::post('getmonthlytransactionchart', [TransactionController::class, 'getMonthlyTransactionChart']);
Route::post('getmonthlyusertransactionchart', [TransactionController::class, 'getMonthlyUserTransactionChart']);

Route::post('generatepdf', [TransactionController::class, 'generatePdf']);
Route::post('generateuserpdf', [UserController::class, 'generatePdf']);

Route::apiResource('replenishments', ReplenishmentController::class);
Route::post('confirmreplenishment', [ReplenishmentController::class, 'confirmReplenishment']);

Route::apiResource('requiredreplenishments', ReplenishmentRequirementController::class);

Route::post('allowanddisallowagentdebt', [UserController::class, 'allowAndDisallowAgentDebt']);

Route::post('sortuserbyrole', [UserController::class, 'sortUserByRole']);

Route::post('sortbybrdr', [UserController::class, 'sortByBrdr']);

Route::post('transfertprofitstorecharge', [UserController::class, 'transfertProfitsToRecharge']);

Route::get('accountsum', [AccountController::class, 'accountSum']);

Route::apiResource('expenses', ExpenseController::class);

Route::apiResource('expensetypes', ExpenseTypeController::class);

Route::post('transactionsmonth', [TransactionController::class, 'setMonthAndYear']);

Route::apiResource('envoydeposits', EnvoyDepositController::class);

Route::apiResource('envoytransferts', EnvoyTransfertController::class);

Route::post('envoytransfertsconfirm', [EnvoyTransfertController::class, 'confirmEnvoyTransfert']);

Route::post('confirmenvoydeposit', [EnvoyDepositController::class, 'confirmEnvoyDeposit']);

Route::apiResource('sendmoney', SendMoneyController::class);

Route::post('confirmsendmoney', [SendMoneyController::class, 'confirmSendMoney']);

Route::post('cancelagentdeposit', [AgentDepositController::class, 'cancelAgentDeposit']);

Route::post('selecteddate', [TransactionController::class, 'selectedDate']);

Route::post('accountativities', [AccountActivitController::class, 'getAccountActivity']);

Route::post('accountglobalactivities', [AccountActivitController::class, 'getGlobalAccountActivity']);

Route::post('userativities', [UserActivitController::class, 'getUserActivities']);

Route::get('profittorecharge', [UserController::class, 'getProfitToRecharge']);

Route::post('canceltransaction', [TransactionController::class, 'transactionCancel']);

Route::post('settransactionview', [TransactionController::class, 'setTransactionView']);

Route::post('transactiondetails', [TransactionController::class, 'getTransactionDetails']);

Route::post('getoperatoraccounts', [AccountController::class, 'getOperatorAccounts']);

Route::post('updateoperatoraccounts', [AccountController::class, 'updateOperatorAccounts']);

Route::resource('agentloan', AgentLoanController::class);

Route::get('agentinfos', [UserController::class, 'getEnvoyAgentInfos']);

Route::post('selectedagentinfos', [UserController::class, 'getSelectedEnvoyAgentInfos']);

Route::post('getagentlist', [UserController::class, 'getAgentList']);

Route::post('getselectedagentlist', [UserController::class, 'getSelectedAgentList']);

Route::post('updateenvoyagent', [UserController::class, 'updateEnvoyAgent']);

Route::get('envoylist', [UserController::class, 'getEnvoyList']);

Route::resource('agentloanrepay', AgentLoanRepayController::class);

Route::post('confirmagentloanrepay', [AgentLoanRepayController::class, 'confirmAgentLoanRepay']);

Route::post('agentloanconfirmation', [AgentLoanController::class, 'confirmAgentLoan']);

Route::post('usersorts', [UserController::class, 'getUserSorts']);