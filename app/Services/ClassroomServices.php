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

    public function show($classroom){
        return $classroom;
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
        $lession = Lesson::where('classroom_id',$id)->where('start_time','<',now())->first();
        if ($lession) {
            return true;
        }
        return false;
    }

    public function students($id)

    {
        $students = ClassStudent::where('classroom_id',$id)->get();
        return $students;
    }
}