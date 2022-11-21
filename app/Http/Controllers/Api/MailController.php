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
                "message" => "Không được gửi mail 2 lần"
            ], 400);
        }

        $lesson = Lesson::where('id', $request->lesson_id)->first();
        $subject = $lesson->classroom->subject;

        $mailData = [
            'lesson' => $lesson->toArray(),
            'subject' => $subject->toArray(),
        ];

        $this->mailService->sendEmail(
            $request->student_email,
            'Thông báo buổi học Tutor đang diễn ra',
            $mailData,
            'mail.invite',
        );

        InvitedMail::create($request->input());

        return response([
            "message" => "Đã gửi mail tới " . $request->student_email
        ], 200);
    }
}
