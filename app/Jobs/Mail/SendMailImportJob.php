<?php

namespace App\Jobs\Mail;

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

    /**
     *  Send mail to student when they have been added to classroom.
     *
     * @param String $mailTo
     *
     * @param Array $dataImport
     *
     * @return void
     */
    private $dataImport;
    public function __construct($dataImport)
    {
        $this->dataImport = $dataImport;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $student = User::where('email', $this->dataImport['student_email'])->first();
            $subject = Subject::where('code', strtoupper($this->dataImport['subject']))->first();
            $mailData = [
                'student' => $student->toArray(),
                'subject' => $subject->toArray(),
            ];

            MailServices::sendEmail(
                $this->dataImport['student_email'],
                'Bạn vừa được thêm vào lớp Tutor',
                $mailData,
                'mail.import_excel'
            );
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
