<?php

namespace App\Jobs\InsertExcel;

use App\Models\ClassStudent;
use App\Models\User;
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

    private $user;
    private $classrooms;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $classrooms)
    {
        $this->user = $user;
        $this->classrooms = $classrooms;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::updateOrCreate([
            'email' => $this->user['student_email'],
            'code' => $this->user['student_code'],
        ], [
            'name' => $this->user['student_name'],
            'phone_number' => $this->user['student_phone'],
        ]);

        ClassStudent::updateOrCreate([
            'student_email' => $this->user['student_email'],
            'classroom_id' => $this->classrooms[Str::slug($this->user['subject'])],
        ], [
            "reason" => $this->user['reason'],
        ]);
    }
}
