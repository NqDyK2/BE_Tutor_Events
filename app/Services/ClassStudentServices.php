<?php
namespace App\Services;

use App\Models\Classroom;
use App\Models\ClassStudent;

Class ClassStudentServices
{
    private $mailService;
    public function __construct(MailServices $mailService) {
        $this->mailService = $mailService;
    }

    public function classStudentsInClassroom($id)
    {
        return ClassStudent::select([
            'users.name',
            'class_students.student_email',
            'users.code',
            'users.phone_number',
            'class_students.reason',
        ])
        ->join('users', 'users.email', '=', 'class_students.student_email')
        ->where('class_students.classroom_id', $id)
        ->get();
        return ClassStudent::where('classroom_id',$id)->get();
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