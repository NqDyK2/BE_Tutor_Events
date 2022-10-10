<?php
namespace App\Services;
use App\Models\Classroom;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Class ClassroomServices
{
    public function classroomsInSemester($id)
    {
        $classroom = Classroom::select(
            'classrooms.name',
            'subjects.code',
            'classrooms.default_teacher_email',
            'classrooms.default_tutor_email',
            DB::raw('COUNT(lessons.id) as total_lesson'),
            DB::raw('COUNT(class_students.id) as total_student'),
        )
        ->leftJoin('subjects','classrooms.subject_id','subjects.id')
        ->leftJoin('lessons','lessons.classroom_id','classrooms.id')
        ->leftJoin('class_students','class_students.classroom_id','classrooms.id')
        ->where('semester_id',$id)
        ->groupBy('classrooms.name','subjects.code','classrooms.default_teacher_email','classrooms.default_tutor_email')
        ->get();
        return $classroom;
    }
    
    public function store($data){
        $data['default_teacher_email'] = Auth::user()->email;
        return Classroom::create($data);
    }

    public function update($data, $classroom){
        return $classroom->update($data);
    }

    public function destroy($classroom)
    {
        $classroom->delete();
        return $classroom->trashed();
    }

    public function isStarted($id){
        $lesson = Lesson::where('classroom_id',$id)->where('start_time','<',now())->first();
        if ($lesson) {
            return true;
        }
        return false;
    }
}