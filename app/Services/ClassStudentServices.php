<?php
namespace App\Services;

use App\Models\Classroom;
use App\Models\ClassStudent;
use Illuminate\Support\Facades\DB;

Class ClassStudentServices
{
    public function classStudentsInClassroom($classroom_id)
    {
        return ClassStudent::select([
            'users.name',
            DB::raw('class_students.student_email as email'),
            'users.code',
            'users.phone_number',
            'class_students.reason',
            'class_students.final_result',
            'class_students.is_joined',
        ])
        ->leftJoin('users', 'users.email', '=', 'class_students.student_email')
        ->where('class_students.classroom_id', $classroom_id)
        ->get();

        return ClassStudent::where('classroom_id',$classroom_id)->get();
    }

    public function update($data)
    {
        $ClassStudent = ClassStudent::where('classroom_id', $data->classroom_id)
        ->where('student_email', $data->student_email);

        return $ClassStudent->update($data);
    }
}
