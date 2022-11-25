<?php

namespace App\Http\Requests\ClassStudent;

use App\Models\ClassStudent;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClassStudentRequest extends FormRequest
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
            'student_email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $isExists = ClassStudent::where('classroom_id', $this->classroom_id)
                    ->where('student_email', $value)
                    ->exists();

                    if (!$isExists) {
                        $fail('Sinh viên không nằm trong lớp này');
                    }
                },
            ],
            'is_warning' => 'boolean',
            'reason' => 'string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'student_email.required' => 'Email sinh viên không được để trống',
            'student_email.email' => 'Email sai định dạng',

            'is_warning.boolean' => 'Tag không đúng định dạng',

            'reason.max' => 'Lý do không được quá 255 ký tự'
        ];
    }
}
