<?php

use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Semester;

if (!function_exists('getClassroomStatistical')) {
    function getClassroomStatistical($classroomId)
    {
        $joinedStudents = [];
        $teachers = [];
        $teachersCount = [];

        $classroom = Classroom::where('id', $classroomId)
            ->with(
                'lessons',
                function ($q) {
                    return $q->where('attended', 1)
                        ->with('attendances', function ($q) {
                            $q->where('status', true);
                        });
                }
            )
            ->withCount([
                'classStudents as total_students_count',
                'classStudents as warning_students_count' => function ($q) {
                    return $q->where('is_warning', true);
                },
                'classStudents as passed_students_count' => function ($q) {
                    return $q->where('final_result', 1);
                },
                'classStudents as not_passed_students_count' => function ($q) {
                    return $q->where('final_result', 0);
                },
                'classStudents as banned_students_count' => function ($q) {
                    return $q->where('final_result', -1);
                },
                'classStudents as passed_joinned_students_count' => function ($q) {
                    return $q->where('is_warning', true)->where('final_result', 1);
                }
            ])
            ->firstOrFail();

        foreach ($classroom->lessons as $lesson) {
            $attendedStudents = $lesson->attendances->pluck('student_email')->toArray();
            $joinedStudents = array_merge($joinedStudents, $attendedStudents);
            $teachers[] = $lesson->teacher_email;
        }

        $joinedStudents = array_unique($joinedStudents);

        $classroom->joined_students = ClassStudent::where('classroom_id', $classroomId)
            ->whereIn('student_email', $joinedStudents)
            ->get();

        $classroom->passed_joinned_students_count = $classroom->joined_students->where('final_result', 1)->count();
        $classroom->joined_students_count = count($classroom->joined_students);
        $classroom->statted_lesons_count = count($classroom->lessons);

        foreach (array_count_values($teachers) as $key => $value) {
            $workingMinutes = 0;

            foreach ($classroom->lessons->where('teacher_email', $key) as $lesson) {
                $workingMinutes += strtotime($lesson->end_time) - strtotime($lesson->start_time);
            }

            $teachersCount[] = (object) [
                'email' => $key,
                'lessons_count' => $value,
                'working_minutes' => $workingMinutes / 60
            ];
        }

        $classroom->teachers = collect($teachersCount);

        return $classroom;
    }
}

if (!function_exists('getInprogressSemester')) {
    function getInprogressSemester()
    {
        return Semester::where('semesters.start_time', '<=', now())
            ->where('semesters.end_time', '>=', now())
            ->with('classrooms')
            ->first();
    }
}
