<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
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
            'default_teacher_email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'default_teacher_email.required' => 'Email giảng viên không được để trống',
            'default_teacher_email.email' => 'Email sai định dạng',
        ];
    }
}
