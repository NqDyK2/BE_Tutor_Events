<?php

namespace App\Jobs\InsertExcel;

use App\Models\Lesson;
use App\Models\Major;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
     * Create a new job instance.
     *
     * @return void
     */
    private $dataImport;
    private $mailService;
    public function __construct($dataImport, $mailService)
    {
        $this->dataImport = $dataImport;
        $this->mailService = $mailService;
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
            $subject = Subject::where('code', strtoupper($this->dataImport['subject']))->first()->toArray();

            $mailData = [
                'student' => $student->toArray(),
                'subject' => $subject->toArray(),
            ];

            $this->mailService->sendEmail(
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
