<?php

namespace App\Http\Requests\Statistical;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class getUserStatisticalRequest extends FormRequest
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
            'email' => 'required|email',
            'role' => [
                'required',
                Rule::in(['teacher', 'tutor'])
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            
            'role.required' => 'Role không được để trống',
            'role.in' => 'Role không đúng định dạng'
        ];
    }
}
