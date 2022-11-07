<?php

namespace App\Jobs\InsertExcel;

use App\Models\Major;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendMailInsertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $x;
    private $mailService;
    public function __construct($x, $mailService)
    {
        $this->x = $x;
        $this->mailService = $mailService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject = Subject::where('code', $this->x['subject'])->first();
        $major = Major::where('id', $subject->major_id)->first();
        $classrooms = $subject->classrooms;
        foreach ($classrooms as $key => $classroom) {
            $semester_id = $classroom->semester_id;
        }
        $semester = Semester::where('id', $semester_id)->first();
        $content = [
            'teacher' => Auth::user()->name,
            'name_subject' => $subject->name,
            'code_subject' => $subject->code,
            'name_semester' => $semester->name,
            'name_major' => $major->name,
            'start_time_semester' => date('d-m-Y',strtotime($semester->start_time)),
            'end_time_semester' => date('d-m-Y',strtotime($semester->end_time)),
        ];
        $this->mailService->sendEmail(
            $this->x['student_email'],
            $content,
            'Thông báo về lớp học',
            'mail.import_excel'
        );
    }
}
