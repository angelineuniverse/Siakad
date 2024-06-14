<?php

use Illuminate\Support\Facades\Route;
use Modules\Mahasiswa\Http\Controllers\MahasiswaController;
use Modules\Mahasiswa\Http\Controllers\MahasiswaPeriodeController;

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

Route::middleware(['auth:sanctum','ability:admin'])->prefix('v1')->group(function () {
    Route::resource('mahasiswa-periode', MahasiswaPeriodeController::class);
    Route::post('mahasiswa-periode/{id}/update', [MahasiswaPeriodeController::class,'update']);
    Route::post('mahasiswa/{id}/update', [MahasiswaController::class,'update']);
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::get('mahasiswa/search/find', [MahasiswaController::class,'searching']);
});
Route::middleware(['auth:sanctum','ability:mahasiswa'])->prefix('v1')->group(function () {
    Route::post('mahasiswa/login',[MahasiswaController::class,'login']);
});