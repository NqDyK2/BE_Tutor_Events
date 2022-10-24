<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubjectRequest extends FormRequest
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
            'name' => 'required|string|unique:subjects,name',
            'code' => 'required|string|unique:subjects,code',
            'major_id' => 'required|integer|exists:majors,id',
            'slug' => 'required|string|unique:subjects,slug', 
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Không được để trống trường tên môn học',
            'name.string' => 'Tên môn học phải là chuỗi ký tự',
            'name.unique' => 'Tên môn học này đã tồn tại',

            'code.required' => 'Không được để trống trường mã môn học',
            'code.string' => 'Mã môn học phải là chuỗi ký tự',
            'code.unique' => 'Mã môn học này đã tồn tại',

            'major_id.required' => 'không được để trống trường chuyên ngành',
            'major_id.integer' => 'ID chuyên ngành phải là số',
            'major_id.exists' => 'Chuyên ngành này không tồn tại',

            'slug.required' => 'Không được để trống trường Slug môn học',
            'slug.string' => 'Slug môn học phải là chuỗi ký tự',
            'slug.unique' => 'Slug môn học này đã tồn tại',
        ];
    }
}
