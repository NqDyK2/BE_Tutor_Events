<?php

namespace App\Jobs\InsertExcel;

use App\Models\ClassStudent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class InsertAllStudentAndResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $classrooms;

    /**
     * Thêm sinh viên vào lớp học từ file tất cả học sinh.
     * Chỉ thêm user vào các lớp đang có trong $classrooms
     * User đã có trong lớp học sẽ chỉ thực hiện update
     * Cập nhật các kết quả cuối kỳ
     * Các user được tạo mới từ api này sẽ không có tag warning
     *
     * @param Array $user [
     *      "subject",
     *      "student_code"
     *      "final_score"
     *      "final_result"
     * ]
     * 
     * @param Array $classrooms [
     *      "{subject_code}" => {classroom_id},
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
        $finalStatusList = [
            ClassStudent::FINAL_RESULT_PASSED,
            ClassStudent::FINAL_RESULT_NOT_PASSED,
            ClassStudent::FINAL_RESULT_BANNED,
        ];

        try {
            $dataUpdate = [
                $this->user['student_code'],
            ];

            if (in_array($this->user['final_result'], $finalStatusList)) {
                $dataUpdate['final_result'] = (int)$this->user['final_result'];
                if ($this->user['final_result'] > ClassStudent::FINAL_RESULT_BANNED){
                    $dataUpdate['final_score'] = (float)$this->user['final_score'];
                }
            }

            User::updateOrCreate([
                'email' => $this->user['student_code'] . '@fpt.edu.vn',
            ], [
                'code' => $this->user['student_code'],
                'name' => $this->user['student_code'],
            ]);

            ClassStudent::updateOrCreate([
                'student_email' => $this->user['student_code'] . '@fpt.edu.vn',
                'classroom_id' => $this->classrooms[Str::slug($this->user['subject'])],
            ], $dataUpdate);

        } catch (\Throwable $th) {
        }
    }
}
