<?php

namespace App\Http\Requests\ClassStudent;

use Illuminate\Foundation\Http\FormRequest;

class ClassStudentRequest extends FormRequest
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
            'classroom_id' => 'required|integer',
            'user_email' => 'required|email',
        ];
    }
}
