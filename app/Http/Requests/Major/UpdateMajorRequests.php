<?php

namespace App\Http\Requests\Major;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMajorRequests extends FormRequest
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
            'name' => 'min:3|max:100|unique:majors,name,' . $this->major_id,
            'teacher_email' => 'nullable|email',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'Tên chuyên ngành phải lớn hơn 3 ký tự',
            'name.max' => 'Tên chuyên ngành phải nhỏ hơn 100 ký tự',
            'name.unique' => 'Chuyên ngành đã tồn tại',

            'teacher_email.email' => 'Email giáo viên không đúng định dạng',
        ];
    }
}
