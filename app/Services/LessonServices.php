<?php
namespace App\Services;

use App\Models\ClassStudent;
use App\Models\Lesson;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
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

    // public function studentSchedule(){
    //     $semester = Semester::where('start_time', '<=', now())->where('end_time', '>=', now())->first();

    //     if (!$semester) return [];

    //     $classStudent = ClassStudent::select(
    //         'subjects.name as subject_name',
    //         'subjects.code as subject_code',
    //         'lessons.start_time',
    //         'lessons.end_time',
    //         'lessons.type',
    //         'lessons.class_location_offline',
    //         'lessons.class_location_online',
    //         'lessons.teacher_email',
    //         'lessons.tutor_email',
    //         'lessons.content',
    //         'lessons.document_path',
    //     )
    //     ->join('classrooms','classrooms.id','class_students.classroom_id')
    //     ->join('subjects','subjects.id','classrooms.subject_id')
    //     ->leftJoin('lessons','classrooms.id','lessons.classroom_id')
    //     ->where('class_students.student_email', Auth::user()->email)
    //     ->where('class_students.is_joined', true)
    //     ->where('lessons.start_time', '>=', $semester->start_time)
    //     ->where('lessons.end_time', '<=', $semester->end_time)
    //     ->whereNotNull('lessons.id')
    //     ->orderBy('lessons.start_time', 'ASC', 'lessons.end_time', 'ASC')
    //     ->get();
    //     return $classStudent;
    // }

    public function studentSchedule(){
        return ClassStudent::select(
            'subjects.name as subject_name',
            'subjects.code as subject_code',
            'lessons.start_time',
            'lessons.end_time',
            'lessons.type',
            'lessons.class_location_offline',
            'lessons.class_location_online',
            'lessons.teacher_email',
            'lessons.tutor_email',
            'lessons.content',
            'lessons.document_path',
        )
        ->join('classrooms','classrooms.id','class_students.classroom_id')
        ->join('subjects','subjects.id','classrooms.subject_id')
        ->leftJoin('lessons','classrooms.id','lessons.classroom_id')
        ->where('class_students.student_email', Auth::user()->email)
        ->where('class_students.is_joined', true)
        ->where('lessons.start_time', '>=', date('Y-m-d'))
        ->orderBy('lessons.start_time', 'ASC', 'lessons.end_time', 'ASC')
        ->get();
    }
}
