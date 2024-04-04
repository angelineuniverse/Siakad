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

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('admin', AdminController::class)->names('admin');
// });
Route::post('admin:login',[AdminController::class,'login']);
Route::middleware(['auth:sanctum', 'ability:admin'])->group(function(){
    Route::resource('admin', AdminController::class);
    Route::get('admin:aktivasi',[AdminController::class,'activatedAccount']);
});