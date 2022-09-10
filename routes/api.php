<?php

use App\Http\Controllers\Api\MajorController;
use App\Models\Major;
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

Route::prefix('major')->group(function () {
    Route::get('get-all', 'MajorController@index');
    Route::middleware('existMajor')->group(function (){
        Route::get('show/{id}', 'MajorController@show');
        Route::middleware('admin')->group(function () {
            Route::put('update/{id}', 'MajorController@update');
            // Route::delete('destroy/{id}', 'MajorController@destroy');
        });
    });
    Route::post('store', 'MajorController@store')->middleware('admin');
});

Route::prefix('user')->group(function () {
    Route::get('get', 'UserController@get');
    Route::middleware('existUser')->group((function () {
        Route::get('show/{id}', 'UserController@show');
        Route::put('update/{id}', 'UserController@update');
    }));
});


Route::name('subject')->prefix('subject')->group(function () {
    Route::get('get-all',[SubjectController::class, 'index']);
    Route::middleware('existSubject')->group(function (){
        Route::get('show/{id}',[SubjectController::class, 'show']);
        Route::middleware('admin')->group(function (){
            Route::put('update/{id}',[SubjectController::class, 'update']);
            // Route::delete('destroy/{id}',[SubjectController::class, 'destroy']);
        });
    });
    Route::middleware('admin')->group(function (){
        Route::post('store',[SubjectController::class, 'store']);
    });
});



