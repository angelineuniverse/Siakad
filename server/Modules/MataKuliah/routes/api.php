<?php

use Illuminate\Support\Facades\Route;
use Modules\MataKuliah\Http\Controllers\MataKuliahController;

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

Route::middleware(['auth:sanctum', 'ability:admin,mahasiswa'])->prefix('v1')->group(function(){
    Route::resource('matakuliah', MataKuliahController::class);
    Route::post('matakuliah/{id}/update',[MataKuliahController::class,'update']);
    Route::get('matakuliah/{mahasiswaId}/krs',[MataKuliahController::class,'matakuliah_form_krs']);
    Route::get('matakuliah/ipk/final',[MataKuliahController::class,'ipk']);
});