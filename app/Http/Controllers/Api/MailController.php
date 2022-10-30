<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\SendMailInviteRequest;
use App\Models\InvitedMail;
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

        $content = 1;
        $this->mailService->sendEmail(
            $request->student_email,
            $content,
            'Mời mày vào lớp',
            'mail.invite'
        );

        InvitedMail::create($request->only([
            'student_email',
            'lesson_id'
        ]));

        return response([
            "message" => "Đã gửi mail"
        ], 200);
    }
}
