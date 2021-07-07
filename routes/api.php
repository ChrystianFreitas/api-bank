<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BankAccountController;
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

Route::get('users', [UserController::class, 'index']);

Route::prefix('user')->group(function() {
    Route::post('login', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('checkToken', [UserController::class, 'checkToken']);
});

Route::prefix('bank_account')->group(function() {
    Route::post('balance', [BankAccountController::class, 'getBalance']);
    Route::post('withdraw', [BankAccountController::class, 'withdraw']);
    Route::post('depositOwnAccount', [BankAccountController::class, 'depositOwnAccount']);
    Route::post('deposit', [BankAccountController::class, 'deposit']);

});
