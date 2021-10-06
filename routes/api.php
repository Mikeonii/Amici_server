<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
});
// AUTHENTICATION
Route::post('/auth/signin',[AuthController::class,'signin']);
