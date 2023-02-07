<?php

use App\Models\Semester;
use Carbon\Carbon;

if (!function_exists('getInprogressSemester')) {
    function getInprogressSemester()
    {
        return Semester::where('semesters.start_time', '<=', now())
            ->where('semesters.end_time', '>=', now())
            ->with('classrooms')
            ->first();
    }
}

if (!function_exists('convertLessonTime')) {
    function convertLessonTime($date, $lessonNumber)
    {
        switch ($lessonNumber) {
            case 1:
                $startTime = Carbon::create($date)->startOfDay()->addHours(7)->addMinutes(15);
                $endTime = Carbon::create($date)->startOfDay()->addHours(9)->addMinutes(15);
                break;
            case 2:
                $startTime = Carbon::create($date)->startOfDay()->addHours(9)->addMinutes(25);
                $endTime = Carbon::create($date)->startOfDay()->addHours(11)->addMinutes(25);
                break;
            case 3:
                $startTime = Carbon::create($date)->startOfDay()->addHours(12);
                $endTime = Carbon::create($date)->startOfDay()->addHours(14);
                break;
            case 4:
                $startTime = Carbon::create($date)->startOfDay()->addHours(14)->addMinutes(10);
                $endTime = Carbon::create($date)->startOfDay()->addHours(16)->addMinutes(10);
                break;
            case 5:
                $startTime = Carbon::create($date)->startOfDay()->addHours(16)->addMinutes(20);
                $endTime = Carbon::create($date)->startOfDay()->addHours(18)->addMinutes(20);
                break;
            case 6:
                $startTime = Carbon::create($date)->startOfDay()->addHours(18)->addMinutes(30);
                $endTime = Carbon::create($date)->startOfDay()->addHours(20)->addMinutes(30);
                break;
        }
        return [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
