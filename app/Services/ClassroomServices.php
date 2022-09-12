<?php
namespace App\Services;
use App\Models\Classroom;
use App\Models\Lession;

Class ClassroomServices
{
    public function index(){
        return Classroom::paginate(Classroom::DEFAULT_PAGINATE);
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
        $lession = Lession::where('classroom_id',$id)->where('start_time','<',now())->first();
        if ($lession) {
            return true;
        }
        return false;
    }
}