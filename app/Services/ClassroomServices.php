<?php
namespace App\Services;
use App\Models\Classroom;

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
}