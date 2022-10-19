<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class CreateClassroomRequest extends FormRequest
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
            'name' => 'required|unique:classrooms,name',
            'subject_id' => 'required|integer|exists:subjects,id',
            'semester_id' => 'required|integer|exists:semesters,id',
            'default_offline_class_location' => 'nullable|string|min:3|max:200',
            'default_online_class_location' => 'nullable|string|url',
            'default_tutor_email' => 'nullable|email',   
            'default_teacher_email' => 'nullable|email', 
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa điền trường tên lớp',
            'name.unique' => 'Tên lớp này đã tồn tại',

            'subject_id.required' => 'Bạn chưa chọn môn học',
            'subject_id.integer' => 'ID môn học phải là số',
            'subject_id.exists' => 'Môn học này không tồn tại',

            'semester_id.required' => 'Bạn chưa chọn kì học',
            'semester_id.integer' => 'ID kì học phải là số',
            'semester_id.exists' => 'Kì học này không tồn tại',

            'default_offline_class_location.min' => 'Địa chỉ offline quá ngắn',
            'default_offline_class_location.max' => 'Địa chỉ offline quá dài',

            'default_online_class_location.url' => 'Địa chỉ online phải là đường dẫn url',

            'default_tutor_email.email' => 'Email của tutor sai định dạng',
            
            'default_teacher_email.email' => 'Email của giáo viên sai định dạng',
        ];
    }
}
