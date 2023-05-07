<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('phone-auth', [App\Http\Controllers\PhoneAuthController::class, 'index']);
Route::post('/save-token', [App\Http\Controllers\HomeController::class, 'saveToken'])->name('save-token');
Route::post('/update-token', [App\Http\Controllers\HomeController::class, 'UpdateRecord'])->name('update-token');
