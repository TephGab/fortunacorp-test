<?php

use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

Auth::routes(['register' => false]);

// Route::post('/broadcasting/auth', function () {
//     return Auth::user();
//  });

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/transaction/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/transactionsteps/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/transactiondetails/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/cashins/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/cashout/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/cashout/create/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/providers/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/providerpayments/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/accounts/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/accounttransferts/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/users/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/roleandpermission/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/agentsdeposits/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/agentsdeposits/modify/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/envoydeposits/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/departments/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/replenishment/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/expenses/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/userativities/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/envoytransferts/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/agentloan/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/agentloanrepay/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/sendmoney/create/{agentid}/{amount}/{reqreplenishmentid}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/replenishment/create/{agentid}/{amount}/{reqreplenishmentid}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/sms', [App\Http\Controllers\Api\SmsController::class, 'sendSms']);

// Route::post('/broadcasting/auth', function () {
//     return Auth::user();
//  });

Route::get('/profittorecharge/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/profittorecharge/create/{slug}', [App\Http\Controllers\HomeController::class, 'index']);

Route::any('{slug}', [App\Http\Controllers\HomeController::class, 'index']);