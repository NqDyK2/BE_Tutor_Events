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

        $semester->total_students_count = 0;
        $semester->warning_students_count = 0;
        $semester->passed_students_count = 0;
        $semester->not_passed_students_count = 0;
        $semester->banned_students_count = 0;
        $semester->passed_joinned_students_count = 0;
        $semester->joined_students_count = 0;
        $semester->statted_lesons_count = 0;
        $semester->teachers = collect([]);

        foreach ($semester->classrooms as $classroom) {
            $data = getClassroomStatistical($classroom->id);
            $classroomsStatistical[] = $data;

            $semester->total_students_count += $data->total_students_count;
            $semester->warning_students_count += $data->warning_students_count;
            $semester->joined_students_count += $data->joined_students_count;
            $semester->passed_students_count += $data->passed_students_count;
            $semester->not_passed_students_count += $data->not_passed_students_count;
            $semester->banned_students_count += $data->banned_students_count;
            $semester->passed_joinned_students_count += $data->passed_joinned_students_count;
            $semester->statted_lesons_count += $data->statted_lesons_count;
            
            foreach ($data->teachers as $teacher) {
                $teacherInsemester = $semester->teachers->where('email', $teacher->email)->first();

                if (!$teacherInsemester) {
                    $semester->teachers->push($teacher);
                } else {
                    $teacherInsemester->lessons_count += $teacher->lessons_count;
                    $teacherInsemester->working_minutes += $teacher->working_minutes;
                }
            }
        }

        $semester->teachers_count = $semester->teachers->count();
        $semester->classrooms_statistical = $classroomsStatistical;

        $semester = $semester->toArray();
        unset($semester['classrooms']);

        return response([
            'data' => $semester
        ], 200);
    }
}
