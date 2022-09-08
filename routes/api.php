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

Route::prefix('major')->group(function () {
    Route::get('get-all', 'MajorController@index');
    Route::get('show/{id}', 'MajorController@show');
    Route::middleware('admin')->group((function () {
        Route::post('store', 'MajorController@store');
        Route::put('update/{id}', 'MajorController@update');
        Route::delete('destroy/{id}', 'MajorController@destroy');
    }));
});

Route::prefix('user')->group(function () {
    Route::get('get', 'UserController@get');
    Route::middleware('existUser')->group((function () {
        Route::get('show/{id}', 'UserController@show');
        Route::put('update/{id}', 'UserController@update');
    }));
});
