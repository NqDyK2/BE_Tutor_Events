<?php
namespace App\Services;

use App\Models\Classroom;
use App\Models\ClassStudent;
use Illuminate\Support\Facades\DB;

Class ClassStudentServices
{
    private $mailService;
    public function __construct(MailServices $mailService) {
        $this->mailService = $mailService;
    }

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

    public function store($data){
        $classroom = Classroom::find($data['classroom_id']);
        $content = [
            'classroom' => $classroom,
            'teacher' => $classroom->user
        ];
        $this->mailService->sendEmail(
            $data['user_email'],
            $content,
            'Thông báo siêu khẩn cấp',
            'mail.contact'
        );
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
