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

Route::middleware(['auth:sanctum','ability:admin,mahasiswa'])->prefix('v1')->group(function () {
    Route::resource('mahasiswa-periode', MahasiswaPeriodeController::class);
    Route::post('mahasiswa-periode/{id}/update', [MahasiswaPeriodeController::class,'update']);
    Route::post('mahasiswa/{id}/update', [MahasiswaController::class,'update']);
    Route::post('mahasiswa/update/profile', [MahasiswaController::class,'profileUpdate']);
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::get('mahasiswa/active/all', [MahasiswaController::class,'mahasiswaActive']);
    Route::get('mahasiswa/active/lulus', [MahasiswaController::class,'mahasiswaLulus']);
    Route::get('mahasiswa/active/list', [MahasiswaController::class,'mahasiswaActiveList']);
    Route::get('mahasiswa/terdaftar/chart', [MahasiswaController::class,'mahasiswaTerdaftarChart']);
    Route::get('mahasiswa/profile/form', [MahasiswaController::class,'mahasiswaProfile']);
    Route::get('mahasiswa/profile/detail', [MahasiswaController::class,'mahasiswaDetail']);
    Route::get('mahasiswa/search/find', [MahasiswaController::class,'searching']);
    Route::get('mahasiswa/auth/logout',[MahasiswaController::class,'logout']);
});
Route::post('v1/mahasiswa/login',[MahasiswaController::class,'login']);