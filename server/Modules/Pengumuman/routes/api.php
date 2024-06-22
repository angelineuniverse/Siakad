<?php

use Illuminate\Support\Facades\Route;
use Modules\Pengumuman\Http\Controllers\PengumumanController;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::resource('pengumuman', PengumumanController::class);
    Route::post('pengumuman/{id}/update',[PengumumanController::class,'update']);
    Route::get('pengumuman/user/list',[PengumumanController::class,'pengumumanUser']);
});
