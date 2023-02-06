<?php

namespace App\Http\Requests\Lesson;

use App\Models\Classroom;
use Illuminate\Foundation\Http\FormRequest;

class CreateLessonRequest extends FormRequest
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
            'classroom_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $classroom = Classroom::find($value);
                    if (!$classroom) {
                        $fail('Lớp học không tồn tại');
                    }
                },
            ],
            'class_location' => 'nullable',
            'lesson_number' => 'required|integer|min:1|max:6',
            'date' => 'required|date',
            'type' => 'required|boolean',
            'teacher_email' => 'required|email',
            'tutor_email' => 'nullable|email',
            'content' => 'nullable|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'classroom_id.required' => 'Lớp học không được để trống',

            'date.required' => 'Ngày tháng học không được để trống',
            'date.date' => 'Ngày tháng học không đúng định dạng',

            'lesson_number.required' => 'Ca học không được để trống',
            'lesson_number.integer' => 'Ca học phải là một số',
            'lesson_number.min' => 'Chỉ có ca học từ 1-6',
            'lesson_number.max' => 'Chỉ có ca học từ 1-6',

            'type.required' => 'Type không được để trống',
            'type.boolean' => 'Type không đúng định dạng',

            'teacher_email.email' => 'Email giảng viên không đúng định dạng',
            'teacher_email.required' => 'Email giảng viên không được để trống',
            'tutor_email.email' => 'Email tutor không đúng định dạng',

            'content.max' => 'Nội dung không được quá 2000 ký tự',
        ];
    }
}
