<?php
namespace App\Services;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Class ClassroomServices
{
    public function classroomsInSemester($semester_id)
    {
        return Classroom::select([
            'classrooms.name',
            DB::raw('subjects.name as subject_name'),
            DB::raw('subjects.code as subject_code'),
            DB::raw('semesters.name as semester_name'),
            DB::raw('classrooms.default_teacher_email as default_teacher_email'),
            DB::raw('classrooms.default_tutor_email as default_tutor_email'),
        ])
        ->join('subjects', 'subjects.id', '=', 'classrooms.subject_id')
        ->join('semesters', 'semesters.id', '=', 'classrooms.semester_id')
        ->where('semester_id',$semester_id)
        ->withCount('classStudents')
        ->withCount('lessons')
        ->orderBy('subjects.code', 'asc')
        ->get();
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

    // public function classroomsInUser($semester_id){
    //     $classrooms = ClassStudent::select(
    //         'classrooms.name as classroom_name',
    //         'subjects.name as subject_name',
    //         'subjects.code as subject_code',
    //         'semesters.name as semester_name',
    //         'classrooms.default_teacher_email as default_teacher_email',
    //         'classrooms.default_tutor_email as default_tutor_email',
    //         'semesters.start_time as semester_start_time',
    //         'semesters.end_time as semester_end_time',
    //     )
    //     ->leftJoin('classrooms','class_students.classroom_id','classrooms.id')
    //     ->leftJoin('subjects','classrooms.subject_id','subjects.id')
    //     ->leftJoin('semesters', 'semesters.id', 'classrooms.semester_id')
    //     ->where('class_students.student_email',Auth::user()->email)
    //     ->where('classrooms.semester_id',$semester_id)
    //     ->get();
    //     return $classrooms;
    // }
}