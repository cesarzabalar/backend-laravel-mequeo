<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', 'Auth\AuthController@signUp')
        ->name('auth.signup');

    Route::post('login', 'Auth\AuthController@login')
        ->name('auth.login');
});

//Route::post('/register', 'Auth\AuthController@register');

/*
|--------------------------------------------------------------------------
| Cash Register Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'cash', 'middleware' => ['auth:api']], function () {
    Route::get('/status', 'CashRegister\CashRegisterController@getStatusCashRegister')
        ->name('cash.status');

    Route::get('/empty', 'CashRegister\CashRegisterController@setEmptyCashRegister')
        ->name('cash.empty');

    Route::post('/add', 'CashRegister\CashRegisterController@createBaseCashRegister')
        ->name('cash.add');
});

/*
|--------------------------------------------------------------------------
| Logs Register Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'logs', 'middleware' => ['auth:api']], function () {
    Route::get('/list', 'CashRegister\LogController@getLogs')
        ->name('logs.list');

    Route::get('/listByDate/{date}', 'CashRegister\LogController@getStatusByDate')
        ->name('logs.listByDate');
});

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'payment', 'middleware' => ['auth:api']], function () {
    Route::post('/create', 'CashRegister\PaymentController@createPayment')
        ->name('payment.create');
});
