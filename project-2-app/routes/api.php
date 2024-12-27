<?php

use App\Http\Controllers\admin\roles\RoleController;
use App\Http\Controllers\admin\roles\ShowPermissionController;
use App\Http\Controllers\admin\roles\UserRoleController;
use App\Http\Controllers\admin\users\UserManagementController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\auth\EmailController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\logoutController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\SmsController;
use App\Http\Controllers\DocumentController;
use App\Http\Middleware\CheckRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

#region Documentation API
Route::get('/',[DocumentController::class,'index']);
#endregion

Route::prefix('auth')->group(function (){

    #region User register & login
    Route::post('/register', [RegisterController::class,'register']);
    Route::post('/login', [LoginController::class,'login']);
    #endregion

    #region Login with SMS
    Route::post('/sendSMS', [SmsController::class,'sendSMS']);
    Route::post('/verifySMS', [SmsController::class, 'verifyCode']);
    #endregion

    #region Login with email
    Route::post('/sendEmail', [EmailController::class, 'sendEmail']);
    Route::post('/verifyEmail', [EmailController::class, 'verifyCode']);
    #endregion

    #region User logout
    Route::post('/logout', [LogoutController::class,'logout'])->middleware(['auth:sanctum', 'verify_csrf_token']);
    #endregion

})->middleware(CheckRequest::class);


Route::prefix('admin/roles')->group(function (){

    #region
    Route::get('/permissions',[ShowPermissionController::class,'show']);
    #region

    #region user role
    Route::delete('/assignRoleToUser',[UserRoleController::class,'destroy']);
    Route::post('/assignRoleToUser',[UserRoleController::class,'create']);
    Route::get('/{id}',[UserRoleController::class,'show']);
    #endregion

    #region Role
    Route::get('/',[RoleController::class,'show']);
    Route::post('/',[RoleController::class,'create']);
    Route::delete('/{id}',[RoleController::class,'destroy']);
    Route::patch('/{id}',[RoleController::class,'update']);
    #endregion

})->middleware(CheckRequest::class);

Route::prefix('admin/users')->group(function (){
    Route::get('/{user_id}',[UserManagementController::class,'show']);
    Route::post('/',[UserManagementController::class,'create']);
    Route::delete('/{user_id}',[UserManagementController::class,'destroy']);
    Route::patch('/{user_id}',[UserManagementController::class,'update']);
})->middleware(CheckRequest::class);

//Route::get('/{user_id}',[UserRoleController::class,'show']);
