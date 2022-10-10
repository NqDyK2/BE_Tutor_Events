<?php
namespace App\Services;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Lesson;

Class ClassroomServices
{
    public function classroomsInSemester($id)
    {
        return Classroom::where('semester_id',$id)->get();
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