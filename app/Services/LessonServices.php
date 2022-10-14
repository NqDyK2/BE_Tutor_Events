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
            DB::raw('subjects.name as subject_name'),
            DB::raw('subjects.code as subjects_code'),
            'lessons.start_time',
            'lessons.end_time',
            DB::raw('lessons.teacher_email as teacher_email'),
            DB::raw('lessons.tutor_email as tutor_email'),
            DB::raw('lessons.class_location_online'),
            DB::raw('lessons.class_location_offline'),
            'lessons.type',
            'lessons.classroom_id',
        )
        ->leftJoin('classrooms','classrooms.id','lessons.classroom_id')
        ->leftJoin('subjects','subjects.id','classrooms.subject_id')
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