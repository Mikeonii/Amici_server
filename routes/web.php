<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SummaryController;

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
    return view('welcome');
});
Route::get('/print_monthly_sales/{selectedMonth}/{selectedYear}',[SummaryController::class,'print_monthly_sales']);
Route::get('/print_daily_sales/{selectedDay}/{selectedMonth}/{selectedYear}',[SummaryController::class,'print_daily_sales']);
Route::get('/print_waiver_form/{account_id}',[AccountController::class,'print_waiver_form']);