<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Mahasiswa\App\Http\Controllers\MahasiswaController;

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

Route::post('v1/auth/login',[MahasiswaController::class,'login']);
Route::middleware(['auth:sanctum', 'ability:mahasiswa'])->prefix('v1')->name('api.')->group(function () {
    Route::resource('mahasiswa',MahasiswaController::class);
});
