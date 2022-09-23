<?php

namespace App\Jobs\InsertExcel;

use App\Services\ExcelServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class InsertUserFromExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $excelServices;
    private $data;
    private $classrooms;
    private $teachers;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ExcelServices $excelServices, $data)
    {
        $this->excelServices = $excelServices;
        $this->data = $data['data'];
        $this->classrooms = $data['classrooms'];
        $this->teachers = $data['teachers'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_id = $this->excelServices->requireUserImport($this->data);

        $this->excelServices->requireUserClassroom([[
            'user_email' => $this->data['student_email'],
            "classroom_id" => $this->classrooms[Str::slug($this->data['subject'])],
        ], [
            "school_teacher_id" => $this->teachers[Str::slug($this->data['school_teacher_code'])],
            "reason" => $this->data['reason'],
            "school_classroom" => $this->data['school_classroom'],
        ]]);
    }
}
