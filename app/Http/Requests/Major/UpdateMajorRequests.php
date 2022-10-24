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
            'name' => 'required|string|unique:majors,name,'.$this->id,
            'teacher_email' => 'required|email',
            'slug' => 'required|string|unique:majors,slug,'.$this->id
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'không được để trống trường tên',
            'name.string' => 'Tên trường phải là chuỗi',
            'name.unique' => 'Tên trường này đã tồn tại',

            'teacher_email.required' => 'không được để trống trường email giáo viên',
            'teacher_email.string' => 'email giáo viên không đúng định dạng',


            'slug.required' => 'không được để trống trường Slug',
            'slug.string' => 'Slug trường phải là chuỗi',
            'slug.unique' => 'Slug trường này đã tồn tại',
        ];
    }
}
