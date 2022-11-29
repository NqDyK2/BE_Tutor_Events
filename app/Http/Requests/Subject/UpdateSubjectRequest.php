<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateSubjectRequest extends FormRequest
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
        $this->code = strtoupper(Str::slug($this->code));

        return [
            'name' => 'min:3|max:100|unique:subjects,name,' . $this->subject_id,
            'code' => 'min:3|max:100|unique:subjects,code,' . $this->subject_id,
            'major_id' => 'integer|exists:majors,id',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'Tên môn học phải lớn hơn 3 ký tự',
            'name.max' => 'Tên môn học phải nhỏ hơn 100 ký tự',
            'name.unique' => 'Tên môn học đã tồn tại',

            'name.min' => 'Tên môn học phải lớn hơn 3 ký tự',
            'name.max' => 'Tên môn học phải nhỏ hơn 100 ký tự',
            'code.unique' => 'Mã môn học đã tồn tại',

            'major_id.integer' => 'ID chuyên ngành phải là số',
            'major_id.exists' => 'Chuyên ngành không tồn tại',
        ];
    }
}
