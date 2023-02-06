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
        $this->code = strtoupper($this->code);

        return [
            'name' => 'required|min:3|max:100|unique:subjects,name',
            'code' => 'required|min:3|max:100|unique:subjects,code',
            'major_id' => 'required|integer|exists:majors,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên môn học không được để trống',
            'name.min' => 'Tên môn học phải lớn hơn 3 ký tự',
            'name.max' => 'Tên môn học phải nhỏ hơn 100 ký tự',
            'name.unique' => 'Tên môn học đã tồn tại',

            'code.required' => 'Mã môn học không được để trống',
            'name.min' => 'Tên môn học phải lớn hơn 3 ký tự',
            'name.max' => 'Tên môn học phải nhỏ hơn 100 ký tự',
            'code.unique' => 'Mã môn học đã tồn tại',

            'major_id.required' => 'không được để trống trường chuyên ngành',
            'major_id.integer' => 'ID chuyên ngành phải là số',
            'major_id.exists' => 'Chuyên ngành không tồn tại',
        ];
    }
}
