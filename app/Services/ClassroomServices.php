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

    public function show($id){
        return $classroom = Classroom::find($id);
    }

    public function update($data, $id){
        $classroom = Classroom::find($id);
        return $classroom->update($data);
    }

    public function destroy($id)
    {
        $classroom = Classroom::find($id);
        $classroom->delete();
        return $classroom->trashed();
    }
}