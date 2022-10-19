<?php

namespace App\Jobs\InsertExcel;

use App\Models\ClassStudent;
use App\Services\ExcelServices;
use Illuminate\Bus\Queueable;
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->excelServices->requireUserImport($this->data);

        ClassStudent::updateOrCreate([
            'student_email' => $this->data['student_email'],
            "classroom_id" => $this->classrooms[Str::slug($this->data['subject'])],
        ], [
            "reason" => $this->data['reason'],
        ]);
    }
}
