<?php

use App\Models\Classroom;
use App\Models\Lesson;

if (!function_exists('getClassroomStatistical')) {
    function getClassroomStatistical($classroomId)
    {
        $joinedStudents = [];
        $teachers = [];
        $teachersCount = [];

        $classroom = Classroom::where('id', $classroomId)
        ->with(
            'lessons', function ($q) {
                return $q->where('attended', 1)
                ->with('attendances', function ($q) {
                    $q->where('status', true);
                });
            }
        )
        ->withCount([
            'classStudents as total_students',
            'classStudents as warning_students' => function ($q) {
                return $q->where('is_warning', true);
            },
            'classStudents as passed_students' => function ($q) {
                return $q->where('final_result', 1);
            },
            'classStudents as not_passed_students' => function ($q) {
                return $q->where('final_result', 0);
            },
            'classStudents as banned_students' => function ($q) {
                return $q->where('final_result', -1);
            },
            'classStudents as passed_warning_students' => function ($q) {
                return $q->where('is_warning', true)->where('final_result', 1);
            }
        ])
        ->firstOrFail();

        foreach ($classroom->lessons as $lesson) {
            $attendedStudents = $lesson->attendances->pluck('student_email')->toArray();
            $joinedStudents = array_merge($joinedStudents, $attendedStudents);
            $teachers[] = $lesson->teacher_email;
        }

        $classroom->joined_students = count(array_unique($joinedStudents));
        $classroom->statted_lesons = count($classroom->lessons);
        
        foreach (array_count_values($teachers) as $key => $value) {
            $teachersCount[] = [
                'email' => $key,
                'lessons_count' => $value
            ];
        }

        $classroom->teachers = $teachersCount;
        
        return $classroom;
    }
}