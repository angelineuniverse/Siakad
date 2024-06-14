<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;
use Modules\Finance\Http\Controllers\FinancePeriodeController;

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
    Route::resource('finance-periode', FinancePeriodeController::class);
    Route::post('finance-periode/{id}/update', [FinancePeriodeController::class,'update']);
    Route::get('finance-periode/{id}/matakuliah', [FinancePeriodeController::class,'matakuliahList']);
    Route::get('finance-periode/{id}/matakuliah/{mahasiswaId}', [FinancePeriodeController::class,'selectedmatakuliahList']);
    Route::post('finance/{id}/update', [FinanceController::class,'update']);
    Route::resource('finance', FinanceController::class);
});
