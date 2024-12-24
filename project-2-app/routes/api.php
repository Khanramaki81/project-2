<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\auth\EmailController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\logoutController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\SmsController;
//use App\Http\Controllers\auth\VerifySmsController;
use App\Http\Controllers\DocumentController;
use App\Http\Middleware\CheckRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::get('/',[DocumentController::class,'index']);

Route::prefix('auth')->group(function (){
    #region User register & login
    Route::post('/register', [RegisterController::class,'register']);
    Route::post('/login', [LoginController::class,'login']);
    #endregion

    #region Login with SMS
    Route::post('/sendSMS', [SmsController::class,'sendSMS']);
    Route::post('/verifySMS', [SmsController::class, 'verifyCode']);
//    Route::post('/generateSMS', [SmsController::class, 'generateOtp']);
    #endregion

    #region Login with email
    Route::post('/sendEmail', [EmailController::class, 'sendEmail']);
    Route::post('/verifyEmail', [EmailController::class, 'verifyCode']);
//    Route::post('/generateEmail', [EmailController::class, 'generateOtp']);
    #endregion

    #region User logout
    Route::post('/logout', [LogoutController::class,'logout'])->middleware(['auth:sanctum', 'verify_csrf_token']);
    #endregion
})->middleware(CheckRequest::class);

//Route::post('/logout', [LogoutController::class,'logout'])->middleware(CheckRequest::class);;
