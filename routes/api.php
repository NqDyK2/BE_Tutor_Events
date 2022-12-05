<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SemesterController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ClassStudentController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventUserController;
use App\Http\Controllers\Api\ExcelController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\MajorController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\StatisticalController;
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

Route::get('auth/user', [AuthController::class, 'getAuthDetail']);

// API FOR MANAGE
Route::prefix('major')->group(function () {
    Route::get('get-all', [MajorController::class, 'index']);
    Route::middleware('admin')->group(function () {
        Route::post('store', [MajorController::class, 'store'])->middleware('admin');
        Route::middleware('existMajor')->group(function () {
            Route::put('{major_id}/update', [MajorController::class, 'update']);
            Route::delete('{major_id}/delete', [MajorController::class, 'destroy']);
        });
    });
});

Route::name('subject')->prefix('subject')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::post('store', [SubjectController::class, 'store']);
        Route::middleware('existSubject')->group(function () {
            Route::put('{subject_id}/update', [SubjectController::class, 'update'])->name('updateSubject');
            Route::delete('{subject_id}/delete', [SubjectController::class, 'destroy'])->name('deleteSubject');
        });
    });
});

Route::prefix('semester')->group(function () {
    Route::get('get-all', [SemesterController::class, 'index']);
    Route::post('store', [SemesterController::class, 'store'])->middleware('admin');
    Route::middleware(['existSemester', 'admin'])->group(function () {
        Route::put('{semester_id}/update', [SemesterController::class, 'update']);
        Route::delete('{semester_id}/delete', [SemesterController::class, 'destroy']);
        Route::post('{semester_id}/import', [ExcelController::class, 'import']);
    });

    Route::get('{semester_id}/classrooms', [ClassroomController::class, 'classroomsInSemester'])->middleware(['existSemester']);
});

Route::prefix('classroom')->middleware('checkRoleTeacherOrAdmin')->group(function () {
    Route::post('store', [ClassroomController::class, 'store']);
    Route::middleware('existClassroom')->group(function () {
        Route::put('{classroom_id}/update', [ClassroomController::class, 'update'])->middleware('admin');
        Route::delete('{classroom_id}/delete', [ClassroomController::class, 'destroy']);

        Route::get('{classroom_id}/lessons', [LessonController::class, 'lessonsInClassroom']);
        Route::get('{classroom_id}/students', [ClassStudentController::class, 'studentsInClassroom']);
        Route::put('{classroom_id}/update-student', [ClassStudentController::class, 'update']);
    });
});

Route::prefix('lesson')->middleware('checkRoleTeacherOrAdmin')->group(function () {
    Route::post('store', [LessonController::class, 'store']);
    Route::middleware('existLesson')->group(function () {
        Route::put('{lesson_id}/update', [LessonController::class, 'update']);
        Route::delete('{lesson_id}/delete', [LessonController::class, 'destroy']);
    });
});

// API FOR ATTENDANCE
Route::prefix('attendance')->group(function () {
    Route::get('classrooms', [AttendanceController::class, 'getListClass']);
    Route::get('classroom/{classroom_id}/lessons', [LessonController::class, 'lessonsInClassroom']);

    Route::middleware('existLesson')->group(function () {
        Route::get('lesson/{lesson_id}', [AttendanceController::class, 'attendanceDetail']);
        Route::put('lesson/{lesson_id}', [AttendanceController::class, 'update']);
    });
});

// API FOR STUDENT
Route::prefix('student')->group(function () {
    Route::get('schedule', [ScheduleController::class, 'studentSchedule']);
    Route::get('history/{semester_id?}', [ScheduleController::class, 'studentScheduleHistory']);
    Route::get('missing-classes', [ScheduleController::class, 'missingClasses']);
    Route::post('feedback/{classroom_id}', [ClassroomController::class, 'storeFeedback'])->middleware('existClassroom');
    Route::put('join-class/{classroom_id}', [ScheduleController::class, 'joinClass'])->middleware('existClassroom');
});

// API FOR TEACHER
Route::prefix('teacher-tutor')->group(function () {
    Route::get('schedule', [ScheduleController::class, 'teacherTutorSchedule']);
});

// API MAIL
Route::prefix('mail')->group(function () {
    Route::post('invite-class', [MailController::class, 'sendMailInvite']);
    Route::post('invite-all', [MailController::class, 'sendMailInviteAll']);
});

// API FOR STUDENT
Route::prefix('statistics')->group(function () {
    Route::get('{semester_id?}', [StatisticalController::class, 'index']);
});

//API MANAGE EVENT
Route::prefix('event')->group(function () {
    Route::get('get-all', [EventController::class, 'index']);
    Route::middleware('existEvent')->group(function () {
        Route::post('{event_id}/feedback', [EventUserController::class, 'storeFeedback']);
        Route::post('join', [EventUserController::class, 'create']);
        Route::delete('cancel', [EventUserController::class, 'destroy']);
    });
    Route::middleware('admin')->group(function () {
        Route::post('store', [EventController::class, 'store']);
        Route::middleware('existEvent')->group(function () {
            Route::post('{event_id}/update', [EventController::class, 'update']);
            Route::delete('{event_id}/delete', [EventController::class, 'destroy']);
        });
    });
});