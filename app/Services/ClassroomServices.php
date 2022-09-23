<?php
namespace App\Services;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Lesson;

Class ClassroomServices
{
    public function index(){
        return Classroom::paginate(DEFAULT_PAGINATE);
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

    public function getClassroom($data)
    {
        return Classroom::where('semester_id',$data)->get();
    }

    public function students($id)

    {
        $students = ClassStudent::where('classroom_id',$id)->get();
        return $students;
    }
}