<?php

use App\Models\Semester;

if (!function_exists('getInprogressSemester')) {
    function getInprogressSemester()
    {
        return Semester::where('semesters.start_time', '<=', now())
            ->where('semesters.end_time', '>=', now())
            ->with('classrooms')
            ->first();
    }
}
