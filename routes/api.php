<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
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
Route::post('/user',[AuthController::class,'store']);
Route::group(['middleware'=>['auth:sanctum']],function(){
        Route::get('/auth/attempt',[AuthController::class,'attempt']);
        Route::post('/auth/signout',[AuthController::class,'signout']);

        // ACCOUNT
        Route::get('/accounts',[AccountController::class,'index']);
        Route::post('/account',[AccountController::class,'store']);
        Route::put('/account',[AccountController::class,'store']);
        Route::delete('/account/{id}',[AccountController::class,'destroy']);
});
// AUTHENTICATION
Route::post('/auth/signin',[AuthController::class,'signin']);
