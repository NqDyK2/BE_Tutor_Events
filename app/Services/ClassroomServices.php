<?php
namespace App\Services;
use App\Models\Classroom;
use App\Models\Lesson;
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