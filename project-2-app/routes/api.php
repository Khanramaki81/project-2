<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\auth\EmailController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\logoutController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\SmsController;
use App\Http\Controllers\auth\VerifySmsController;
use App\Http\Middleware\CheckRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::get('/', function () {
//    return view('auth.register');
//});

Route::post('/register', [RegisterController::class,'register'])->middleware(CheckRequest::class);
Route::post('/login', [LoginController::class,'login']);


Route::post('/sendSMS', [SmsController::class,'sendSMS']);
Route::post('/verifySMS', [SmsController::class, 'verifyCode']);
Route::post('/generateSMS', [SmsController::class, 'generateOtp']);

Route::post('/sendEmail', [EmailController::class, 'sendEmail']);
Route::post('/verifyEmail', [EmailController::class, 'verifyCode']);
Route::post('/generateEmail', [EmailController::class, 'generateOtp']);


Route::group(['middleware'=>'auth:sanctum'], function(){
    Route::post('/logout', [LogoutController::class,'logout']);
});
//Route::post('/logout', [LogoutController::class,'logout'])->middleware(CheckRequest::class);;
