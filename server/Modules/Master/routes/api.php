<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Master\App\Http\Controllers\JurusanController;

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

Route::resource('jurusan', JurusanController::class);
// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
// });
