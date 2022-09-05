<?php

use App\Http\Controllers\Api\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::name('subject')->prefix('subject')->group(function () {
        Route::get('get-all', [SubjectController::class, 'index'])->name('index');
        Route::post('store', [SubjectController::class, 'store'])->name('store');
        Route::get('show/{id}', [SubjectController::class, 'show'])->name('show');
        Route::put('update/{id}', [SubjectController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [SubjectController::class, 'destroy'])->name('destroy');
    });
});