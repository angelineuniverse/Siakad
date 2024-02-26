<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Master\App\Http\Controllers\JurusanController;
use Modules\User\App\Http\Controllers\UserController;

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
Route::post('v1/auth/register',[UserController::class,'register']);
Route::post('v1/auth/login',[UserController::class,'login']);
Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::resource('user', UserController::class);
    // url API ( call disini )
});
