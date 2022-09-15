<?php

use App\Http\Controllers\Api\AuthController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', [AuthController::class, 'index'])->name('login');

Route::name('auth.')->prefix('api/auth')->group(function () {
    Route::get('get-url', [AuthController::class, 'getUrl'])->name('getUrl');
    Route::get('checkpoint', [AuthController::class, 'checkpoint'])->name('checkpoint');
});