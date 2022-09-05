<?php
namespace App\Services;
use App\Models\Subject;

Class SubjectServices
{
    public function index(){
        return Subject::paginate(Subject::DEFAULT_PAGINATE);
    }

    public function store($data){
        return Subject::create($data);
    }

    public function show($subject){
        return $subject;
    }

    public function update($data, $id){
        $subject = Subject::find($id);
        return $subject->update($data);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);
        $subject->delete();
        return $subject->trashed();
    }
}