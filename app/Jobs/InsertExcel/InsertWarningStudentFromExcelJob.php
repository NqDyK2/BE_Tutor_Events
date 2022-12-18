<?php

namespace App\Jobs\InsertExcel;

use App\Models\ClassStudent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class InsertWarningStudentFromExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $classrooms;

    /**
     * Thêm user vào lớp học từ file 1/3 block.
     * Chỉ thêm user vào các lớp đang có trong $classrooms
     * User đã có trong lớp học sẽ chỉ thực hiện update
     * User được tạo mới từ api này sẽ có tag warning
     *
     * @param Array $user [
     *      "subject",
     *      "student_code",
     *      "student_email",
     *      "student_name",
     *      "student_phone",
     *      "reason",
     * ]
     *
     * @param Array $classrooms [
     *      "COM1234" => {classroom_id},
     * ]
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
        try {
            User::updateOrCreate([
                'email' => $this->user['student_email'],
            ], [
                'code' => $this->user['student_code'],
                'name' => $this->user['student_name'],
                'phone_number' => $this->user['student_phone'],
            ]);

            ClassStudent::updateOrCreate([
                'student_email' => $this->user['student_email'],
                'classroom_id' => $this->classrooms[Str::slug($this->user['subject'])],
            ], [
                "reason" => $this->user['reason'],
                "is_warning" => true,
            ]);
        } catch (\Throwable $th) {
        }
    }
}
