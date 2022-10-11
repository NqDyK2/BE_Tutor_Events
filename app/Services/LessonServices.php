<?php
namespace App\Services;

use App\Models\ClassStudent;
use App\Models\Lesson;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonServices
{
    public function lessonsInClassroom($classroom_id){
        $lesson = Lesson::select(
            'lessons.id',
            'lessons.classroom_id',
            'lessons.type',
            'lessons.start_time',
            'lessons.end_time',
            'subjects.name',
            'subjects.code',
            DB::raw('users.code as teacher'),
            DB::raw('lessons.tutor_email as tutor'),
            DB::raw('lessons.class_location_online'),
            DB::raw('lessons.class_location_offline'),
        )
        ->leftJoin('classrooms','classrooms.id','lessons.classroom_id')
        ->leftJoin('subjects','subjects.id','classrooms.subject_id')
        ->leftJoin('users','users.id','classrooms.user_id')
        ->where('classroom_id', $classroom_id)
        ->orderBy('lessons.start_time','ASC','lessons.end_time','ASC')->get();
        return $lesson;
    }

    public function store($data)
    {
        return Lesson::create($data);
    }

    public function update($data, $lesson)
    {
        return $lesson->update($data);
    }
    public function destroy($lesson)
    {
        $lesson->delete();
        return $lesson->trashed();
    }

    public function lessonsInUser(){
        $timePresent = now();
        $semester = Semester::where('start_time', '<=', $timePresent)->where('end_time', '>=', $timePresent)->first();
        $classStudent = ClassStudent::select(
            'lessons.content',
            'lessons.document_path',
            'classrooms.name as name_classroom',
            'subjects.name as name_subject',
            'subjects.code as code_subject',
            'lessons.type',
            'lessons.teacher_email',
            'lessons.tutor_email',
            'lessons.class_location_offline',
            'lessons.class_location_online',
            'lessons.start_time',
            'lessons.end_time',
        )
        ->leftJoin('classrooms','classrooms.id','class_students.classroom_id')
        ->leftJoin('lessons','classrooms.id','lessons.classroom_id')
        ->leftJoin('subjects','subjects.id','classrooms.subject_id')
        ->where('class_students.student_email', Auth::user()->email)
        ->whereBetween('lessons.start_time', [$semester->start_time, $semester->end_time])
        ->whereBetween('lessons.end_time', [$semester->start_time, $semester->end_time])
        ->whereNotNull('lessons.id')
        ->orderBy('lessons.start_time', 'ASC', 'lessons.end_time', 'ASC')
        ->get();
        return $classStudent;
    }
}
