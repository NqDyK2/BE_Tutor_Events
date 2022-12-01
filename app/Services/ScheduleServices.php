<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Lesson;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class ScheduleServices
{
    public function studentSchedule()
    {
        return ClassStudent::select(
            'subjects.name as subject_name',
            'subjects.code as subject_code',
            'lessons.start_time',
            'lessons.end_time',
            'lessons.type',
            'lessons.class_location',
            'lessons.teacher_email',
            'lessons.tutor_email',
            'lessons.content',
        )
            ->join('classrooms', 'classrooms.id', 'class_students.classroom_id')
            ->join('subjects', 'subjects.id', 'classrooms.subject_id')
            ->leftJoin('lessons', 'classrooms.id', 'lessons.classroom_id')
            ->where('class_students.student_email', Auth::user()->email)
            ->where('class_students.is_joined', true)
            ->where('lessons.end_time', '>=', date('Y-m-d'))
            ->orderBy('lessons.end_time', 'ASC')
            ->get();
    }


    public function getStudentScheduleBySemesterId($semesterId)
    {
        $semester = Semester::find($semesterId);

        if (!$semester) {
            $semester = Semester::where('semesters.start_time', '<=', now())
                ->where('semesters.end_time', '>=', now())
                ->first();

            if (!$semester) {
                $semester = Semester::orderBy('end_time', 'DESC')->first();
            }
        }

        $result = [];
        $classrooms = Classroom::select(
            'classrooms.id',
            'subjects.name as subject_name',
            'subjects.code as subject_code',
        )
            ->join('subjects', 'subjects.id', 'classrooms.subject_id')
            ->join('class_students', 'class_students.classroom_id', 'classrooms.id')
            ->where('classrooms.semester_id', $semester->id)
            ->where('class_students.student_email', Auth::user()->email)
            ->get();

        foreach ($classrooms as $classroom) {
            $classroom = $classroom->toArray();

            $lessons = Lesson::select(
                'lessons.id',
                'lessons.start_time',
                'lessons.end_time',
                'lessons.teacher_email',
                'lessons.tutor_email',
                'lessons.class_location',
                'lessons.content',
            )
                ->withCount(['attendances' => function ($query) {
                    $query->where('student_email', Auth::user()->email)
                        ->where('status', Attendance::STATUS_PRESENT);
                }])
                ->where('classroom_id', $classroom['id'])
                ->get();

            $classroom['lessons'] = $lessons->toArray();

            $result[] = $classroom;
        }

        return response([
            "data" => $result,
            "semester" => $semester
        ]);
    }

    public function teacherTutorSchedule()
    {
        $user = Auth::user();
        return Lesson::select(
            'subjects.name as subject_name',
            'subjects.code as subject_code',
            'lessons.start_time',
            'lessons.end_time',
            'lessons.type',
            'lessons.class_location',
            'lessons.teacher_email',
            'lessons.tutor_email',
            'lessons.content',
        )
            ->join('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->join('subjects', 'subjects.id', 'classrooms.subject_id')
            ->where('lessons.teacher_email', $user->email)
            ->orWhere('lessons.tutor_email', $user->email)
            ->where('lessons.end_time', '>=', date('Y-m-d'))
            ->orderBy('lessons.end_time', 'ASC')
            ->get();
    }

    public function studentMissingClasses()
    {
        return Classroom::select(
            'classrooms.id',
            'subjects.name',
            'subjects.code',
        )
            ->whereHas('classStudents', function ($q) {
                return $q->where('student_email', Auth::user()->email)
                    ->where('is_joined', false);
            })
            ->join('subjects', 'subjects.id', 'classrooms.subject_id')
            ->get();
    }

    public function joinClass($classroomId)
    {
        $classroom = ClassStudent::where('classroom_id', $classroomId)
            ->where('student_email', Auth::user()->email)
            ->first();

        if (!$classroom) {
            return false;
        }
        $classroom->is_joined = true;
        $classroom->save();

        return true;
    }
}
