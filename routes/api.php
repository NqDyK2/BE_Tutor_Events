<?php

use App\Http\Controllers\Api\MajorController;
use App\Http\Controllers\Api\SemesterController;
use App\Models\Major;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ClassStudentController;
use App\Http\Controllers\Api\IssueController;
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

Route::get('auth/user', function (Request $request) {
    return response()->json([
        'data' => $request->user(),
    ], 200);
});

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

Route::name('semester')->prefix('semester')->group(function () {
    Route::get('get-all',[SemesterController::class, 'index']);
    Route::middleware('existSemester')->group(function (){
        Route::get('show/{id}',[SemesterController::class, 'show']);
        Route::middleware('admin')->group(function (){
            Route::put('update/{id}',[SemesterController::class, 'update']);
            // Route::delete('destroy/{id}',[SemestertController::class, 'destroy']);
        });
    });
    Route::middleware('admin')->group(function (){
        Route::post('store',[SemesterController::class, 'store']);
    });
});



Route::prefix('classroom')->group(function () {
    Route::get('get-all', [ClassroomController::class, 'index']);
    Route::middleware('checkRoleTeacherOrAdmin')->group((function () {
        Route::post('store', [ClassroomController::class, 'store']);
        Route::middleware('existClassroom')->group((function () {
            Route::get('show/{id}', [ClassroomController::class, 'show']);
            Route::put('update/{id}', [ClassroomController::class, 'update']);
            Route::delete('destroy/{id}', [ClassroomController::class, 'destroy']);
        }));
    }));
});
Route::prefix('class-student')->group(function () {
    Route::get('get-all', [ClassStudentController::class, 'index']);
    Route::post('store', [ClassStudentController::class, 'store']);
    Route::middleware('existClassStudent')->group((function () {
        Route::delete('destroy/{id}', [ClassStudentController::class, 'destroy']);
    }));
});

Route::name('issue')->prefix('issue')->group(function () {
    Route::get('get-all',[IssueController::class, 'index']);
    Route::post('store',[IssueController::class, 'store']); 
    Route::middleware('existIssue')->group(function (){
        Route::get('show/{id}',[IssueController::class, 'show']);
        Route::put('update/{id}',[IssueController::class, 'update']);
        // Route::delete('destroy/{id}',[IssueController::class, 'destroy']);
    });
});
