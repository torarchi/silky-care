<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Verification\SmsCodeController;
use App\Http\Controllers\Verification\VerifySmsCodeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('send-sms-code', [SmsCodeController::class, 'send']);
    Route::post('verify-sms-code', [VerifySmsCodeController::class, 'verify']);
});