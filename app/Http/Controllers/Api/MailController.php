<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\SendMailInviteRequest;
use App\Models\InvitedMail;
use App\Models\Lesson;
use App\Services\MailServices;
use Illuminate\Http\Request;

class MailController extends Controller
{
    private $mailService;
    public function __construct(MailServices $mailService) {
        $this->mailService = $mailService;
    }

    public function sendMailInvite(SendMailInviteRequest $request)
    {
        $isSent = InvitedMail::where('student_email', $request->student_email)->where('lesson_id', $request->lesson_id)->exists();

        if ($isSent) {
            return response([
                "message" => "Đã gửi mail cho sinh viên này"
            ], 400);
        }

        $lesson = Lesson::where('id', $request->lesson_id)->first();
        $content = [
            'type' => $lesson->type,
            'content' => $lesson->content,
            'teacher_email' => $lesson->teacher_email,
            'tutor_email' => $lesson->tutor_email,
            'start_time' => date('H:i',strtotime($lesson->start_time)),
            'end_time' => date('H:i',strtotime($lesson->end_time)),
            'class_location' => $lesson->class_location,
        ];

        $this->mailService->sendEmail(
            $request->student_email,
            $content,
            'Thông báo buổi học ngày '. date('d-m-Y',strtotime($lesson->start_time)),
            'mail.invite'
        );

        InvitedMail::create($request->only([
            'student_email',
            'lesson_id'
        ]));

        return response([
            "message" => "Đã gửi mail tới " . $request->student_email
        ], 200);
    }
}
