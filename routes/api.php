<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CreditTransactionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemTransactionController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\SessionController;

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
        Route::post('/upload_profile_picture',[AccountController::class,'upload_profile_picture']);
        Route::put('/account',[AccountController::class,'store']);
        Route::delete('/account/{id}',[AccountController::class,'destroy']);
        Route::put('/modify_expiry_dates',[AccountController::class,'modify_expiry_dates']);
        // CREDIT
        Route::get('/credit_transactions/{account_id}',[CreditTransactionController::class,'show']);
        Route::post('/credit_transaction',[CreditTransactionController::class,'store']);

        // ITEM
        Route::get('/items',[ItemController::class,'index']);
        Route::post('/item',[ItemController::class,'store']);
        Route::put('/item',[ItemController::class,'store']);
        Route::get('/get_yearly_sales',[ItemTransactionController::class,'get_yearly_sales']);

        // ITEM TRANSACTION
        Route::get('/item_transactions/{account_id}',[ItemTransactionController::class,'show']);
        Route::post('/item_transaction',[ItemTransactionController::class,'store']);
        Route::get('/get_all_item_transactions',[ItemTransactionController::class,'get_all_item_transactions']);
        
        // BODY MEASUREMENT
        Route::get('/measurements/{account_id}',[MeasurementController::class,'show']);
        Route::post('/measurement',[MeasurementController::class,'store']);

        // ATTENDANCE
        Route::get('/attendances',[AttendanceController::class,'index']);
        Route::post('/attendance/{card_no}',[AttendanceController::class,'store']);
        Route::get('/get_yearly_attendances',[AttendanceController::class,'get_yearly_attendances']);
        Route::get('/top_gymmers',[AccountController::class,'get_top_gymmers']);

        // SUMMARY
        Route::get('/get_attendance_summary',[SummaryController::class,'get_attendance_summary']); 
        Route::get('/get_sales_summary',[SummaryController::class,'get_sales_summary']);

        // SESSION
        Route::get('/sessions',[SessionController::class,'index']);
        Route::post('/session',[SessionController::class,'store']);
        
});
// AUTHENTICATION
Route::post('/auth/signin',[AuthController::class,'signin']);


// add 1 year to all
Route::get('/add',[AccountController::class,'addYear']);