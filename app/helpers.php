<?php

use App\Models\Lesson;

if (!function_exists('getAttendedStudentsInClassroom')) {
    function getAttendedStudentsInClassroom($classroomId)
    {
        $lessons = Lesson::where('classroom_id', $classroomId)
        ->with('attendances')
        ->get();

        return ;
    }
}