<?php

namespace App\Http\Requests\Mail;

use Illuminate\Foundation\Http\FormRequest;

class SendMailInviteSemesterRequest extends FormRequest
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
            'semester_id' => 'required|exists:semesters,id',
        ];
    }

    public function messages()
    {
        return [
            'semester_id.required' => 'Kỳ học không được để trống',
            'semester_id.exists' => 'Kỳ học không tồn tại',
        ];
    }
}
