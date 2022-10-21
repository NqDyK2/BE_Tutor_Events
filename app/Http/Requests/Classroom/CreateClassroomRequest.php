<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class CreateClassroomRequest extends FormRequest
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
            'subject_id' => 'required|exists:subjects,id',
            'semester_id' => 'required|exists:semesters,id',
            'default_teacher_email' => 'email',
        ];
    }

    public function messages()
    {
        return [
            'subject_id.required' => 'Môn học không được để trống',
            'subject_id.exists' => 'Môn học không tồn tại',

            'semester_id.required' => 'Kỳ học không được để trống',
            'semester_id.exists' => 'Kì học không tồn tại',

            'default_teacher_email.email' => 'Email sai định dạng',
        ];
    }
}
