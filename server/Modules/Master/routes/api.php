<?php

use Illuminate\Support\Facades\Route;
use Modules\Master\Http\Controllers\FakultasController;
use Modules\Master\Http\Controllers\JurusanController;
use Modules\Master\Http\Controllers\MasterController;
use Modules\Master\Http\Controllers\MenuController;
use Modules\Master\Http\Controllers\RoleController;

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

Route::middleware(['auth:sanctum', 'ability:admin'])->prefix('v1')->group(function () {
    Route::prefix('menu')->group(function(){
        Route::get('', [MenuController::class,'index']);
        Route::post('', [MenuController::class,'store']);
        Route::get('create', [MenuController::class,'create']);
        Route::get('{id}', [MenuController::class,'show']);
        Route::post('{id}', [MenuController::class,'update']);
        Route::delete('{id}', [MenuController::class,'destroy']);
        Route::get('{id}/edit', [MenuController::class,'edit']);
    });
    Route::get('nilai', [MasterController::class,'nilai']);
    Route::resource('role',RoleController::class);
    Route::resource('fakultas',FakultasController::class);
    Route::resource('jurusan',JurusanController::class);
});
