<?php

namespace App\Http\Requests\Mail;

use App\Models\Lesson;
use Illuminate\Foundation\Http\FormRequest;

class SendMailInviteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "student_email" => "email",
            "lesson_id" => [
                function($attribute, $value, $fail)
                {
                    $check = Lesson::join('class_students','class_students.classroom_id','lessons.classroom_id')
                    ->where('lessons.id',$value)
                    ->where('class_students.student_email',$this->student_email)
                    ->exists();

                    if (!$check) {
                        $fail("Học sinh không có trong lớp đã chọn");
                    }
                }
            ]
        ];
    }
}
