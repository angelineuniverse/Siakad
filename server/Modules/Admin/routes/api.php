<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::post('v1/admin:login',[AdminController::class,'login']);
Route::post('v1/admin:register',[AdminController::class,'store']);
Route::middleware(['auth:sanctum', 'ability:admin'])->prefix('v1')->group(function(){
    Route::resource('admin', AdminController::class);
    Route::post('admin/{id}/update',[AdminController::class,'update']);
    Route::get('admin:aktivasi',[AdminController::class,'activatedAccount']);
});