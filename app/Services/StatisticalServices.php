<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Semester;

class StatisticalServices
{
    public function getSemesterStatisticalById($semesterId)
    {
        $semester = Semester::find($semesterId);

        if (!$semester) {
            $semester = Semester::where('semesters.start_time', '<=', now())
                ->where('semesters.end_time', '>=', now())
                ->first();
        }
        if (!$semester) {
            $semester = Semester::orderBy('end_time', 'DESC')->first();
        }

        if (!$semester) {
            return response([
                'data' => []
            ], 200);
        }

        $classrooms = Classroom::where('semester_id', '=', $semester->id)
            ->withCount([
                'lessons',
                'lessons as attended_lessons_count' => function ($q) {
                    return $q->where('attended', true);
                }
            ])
            ->get();

        return response([
            'data' => $classrooms
        ], 200);
    }
}
