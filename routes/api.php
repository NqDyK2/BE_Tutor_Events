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

// Route::prefix('user')->group(function () {
//     Route::get('get', 'UserController@get');
//     Route::get('get-one/{user}', 'UserController@show');
//     Route::post('store', 'UserController@store');
//     Route::put('update/{user}', 'UserController@update');
//     Route::delete('delete/{id}', 'UserController@delete');
// });

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
