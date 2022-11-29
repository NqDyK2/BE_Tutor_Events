<?php

namespace App\Jobs\Mail;

use App\Models\ClassStudent;
use App\Models\Lesson;
use App\Models\Major;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use App\Services\MailServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SendMailImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $classStudent;

    /**
     *  Send mail to student when they have been added to classroom.
     *
     * @param String $mailTo
     *
     * @param ClassStudent $classStudent
     *
     * @return void
     */
    public function __construct($classStudent)
    {
        $this->classStudent = $classStudent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $student = $this->classStudent->user;
            $subject = $this->classStudent->classroom->subject;
            $mailData = [
                'student' => $student->toArray(),
                'subject' => $subject->toArray(),
            ];

            MailServices::sendEmail(
                $this->classStudent->student_email,
                'Bạn vừa được thêm vào lớp Tutor',
                $mailData,
                'mail.import_excel'
            );
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
