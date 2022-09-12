<?php
namespace App\Services;
use App\Models\ClassStudent;

Class ClassStudentServices
{
    public function index(){
        return ClassStudent::paginate(ClassStudent::DEFAULT_PAGINATE);
    }

    public function store($data){
        return ClassStudent::create($data);
    }

    public function show($id){
        return ClassStudent::find($id);
    }

    public function destroy($id)
    {
        $classroom = ClassStudent::find($id);
        return $classroom->delete();
    }
}