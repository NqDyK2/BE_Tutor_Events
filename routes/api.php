<?php

use App\Http\Controllers\Api\MajorController;
use App\Models\Major;
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
    Route::name('major')->prefix('major')->group(function () {
        Route::get('get-all', [MajorController::class, 'index'])->name('index');
        Route::get('show/{id}', [MajorController::class, 'show'])->name('show');
        Route::middleware('admin')->group((function () {
            Route::post('store', [MajorController::class, 'store'])->name('store');
            Route::put('update/{id}', [MajorController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [MajorController::class, 'destroy'])->name('destroy');
        }));
    });
    
});
