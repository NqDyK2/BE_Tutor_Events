<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Semester;

class StatisticalServices
{
    public function getSemesterStatisticalById($semesterId = null)
    {
        $classroomsStatistical = [];

        $semester = Semester::where('id', $semesterId)
            ->with('classrooms')
            ->first();

        if (!$semester) {
            $semester = Semester::where('semesters.start_time', '<=', now())
                ->where('semesters.end_time', '>=', now())
                ->with('classrooms')
                ->first();
        }
        if (!$semester) {
            $semester = Semester::orderBy('end_time', 'DESC')
                ->with('classrooms')
                ->first();
        }
        if (!$semester) {
            return response([
                'data' => []
            ], 200);
        }

        foreach ($semester->classrooms as $classroom) {
            $classroomsStatistical[] = getClassroomStatistical($classroom->id);
        }

        $semester->classrooms_statistical = $classroomsStatistical;

        return response([
            'data' => $semester
        ], 200);
    }
}
