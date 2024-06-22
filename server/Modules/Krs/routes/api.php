<?php

use Illuminate\Support\Facades\Route;
use Modules\Krs\Http\Controllers\KrsController;
use Modules\Krs\Http\Controllers\KrsPeriodeController;
use Modules\Krs\Http\Controllers\TranskipController;

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
    Route::resource('krs-periode', KrsPeriodeController::class);
    Route::post('krs-periode/{id}/update', [KrsPeriodeController::class,'update']);
    Route::get('krs-periode/{id}/matakuliah', [KrsPeriodeController::class,'matakuliahList']);
    Route::get('krs-periode/matakuliah/last', [KrsPeriodeController::class,'matakuliahListLatest']);
    Route::get('krs-periode/{id}/matakuliah/{mahasiswaId}', [KrsPeriodeController::class,'selectedmatakuliahList']);
    Route::get('transkip/semester/form', [TranskipController::class,'formSemesterActive']);
    Route::post('krs/{id}/update', [KrsController::class,'update']);
    Route::post('krs/{id}/nilai', [KrsController::class,'updateNilai']);
    Route::get('krs/{id}/validasi', [KrsController::class,'validasiKrsMahasiswa']);
    Route::resource('krs', KrsController::class);
    Route::resource('transkip', TranskipController::class);
});
