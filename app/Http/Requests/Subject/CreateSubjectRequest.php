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
            // 'data' => 'required|array',
            'data.*.name' => 'required|min:3|max:100',
            'data.*.code' => 'required|min:3|max:100|unique:subjects,code',
            'data.*.major_id' => 'required|integer|exists:majors,id',
        ];
    }

    public function messages()
    {
        return [
            'data.*.name.required' => 'Tên môn học không được để trống',
            'data.*.name.min' => 'Tên môn học phải lớn hơn 3 ký tự',
            'data.*.name.max' => 'Tên môn học phải nhỏ hơn 100 ký tự',

            'data.*.code.required' => 'Mã môn học không được để trống',
            'data.*.code.min' => 'Tên môn học phải lớn hơn 3 ký tự',
            'data.*.code.max' => 'Tên môn học phải nhỏ hơn 100 ký tự',
            'data.*.code.unique' => 'Mã môn học đã tồn tại',

            'data.*.major_id.required' => 'không được để trống trường chuyên ngành',
            'data.*.major_id.integer' => 'ID chuyên ngành phải là số',
            'data.*.major_id.exists' => 'Chuyên ngành không tồn tại',
        ];
    }
}
