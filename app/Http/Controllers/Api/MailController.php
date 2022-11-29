<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\SendMailInviteRequest;
use App\Jobs\Mail\SendMailImportJob;
use App\Jobs\Mail\SendMailInviteLesson;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\InvitedMail;
use App\Models\Lesson;
use Illuminate\Http\Request;

class MailController extends Controller
{
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
        SendMailInviteLesson::dispatch(
            $request->student_email,
            [
                'lesson' => $lesson->toArray(),
                'subject' => $subject->toArray(),
            ]
        );

        InvitedMail::create($request->input());

        return response([
            "message" => "Đã gửi mail tới " . $request->student_email
        ], 200);
    }

    public function sendMailInviteAll(Request $request)
    {
        $semester = $request->get('semester');

        $students = ClassStudent::whereHas('classroom', function ($q) use ($semester) {
            $q->where('semester_id', $semester->id);
        })
            ->where('is_warning', true)
            ->where('is_sent_mail', false)
            ->get()
            ->each(function ($classStudent) {
                SendMailImportJob::dispatch($classStudent);
                $classStudent->is_sent_mail = 1;
                $classStudent->save();
            });

            return response([
                "message" => "Đã gửi mail tới " .count($students). " sinh viên"
            ], 200);
    }
}
