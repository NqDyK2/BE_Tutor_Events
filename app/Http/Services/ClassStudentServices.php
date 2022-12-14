<?php
namespace App\Http\Services;

use App\Models\Classroom;
use App\Models\ClassStudent;
use Illuminate\Support\Facades\DB;

class ClassStudentServices
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
            'class_students.is_warning',
        ])
        ->leftJoin('users', 'users.email', '=', 'class_students.student_email')
        ->where('class_students.classroom_id', $classroom_id)
        ->orderBy('is_warning', 'DESC')
        ->orderBy('email', 'ASC')
        ->get();

        return ClassStudent::where('classroom_id', $classroom_id)->get();
    }

    public function update($data)
    {
        $ClassStudent = ClassStudent::where('classroom_id', $data['classroom_id'])
        ->where('student_email', $data['student_email']);

        return $ClassStudent->update($data);
    }
}
