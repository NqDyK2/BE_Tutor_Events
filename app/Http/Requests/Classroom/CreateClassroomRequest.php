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
            'user_id' => 'required|integer|exists:users,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'semester_id' => 'required|integer|exists:semesters,id',
            'default_offline_class_location' => 'nullable|string|min:3|max:200',
            'default_online_class_location' => 'nullable|string|url',
            'default_tutor_email' => 'nullable|email',   
        ];
    }
}
