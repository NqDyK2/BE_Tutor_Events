<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
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
        switch ($this->method())
        {
            case 'POST':
                $checkName = 'required|unique:classrooms,name';
                $checkLocationOff = 'required|unique:classrooms,default_offline_class_location';
                $checkLocationOnl = 'required|unique:classrooms,default_online_class_location';
                break;
            case 'PUT':
                $checkName = 'required|unique:classrooms,name,'.$this->id;
                $checkLocationOff = 'required|unique:classrooms,default_offline_class_location,'.$this->id;
                $checkLocationOnl = 'required|unique:classrooms,default_online_class_location,'.$this->id;
                break;
        }
        return [
            'name' => $checkName,
            'user_id' => 'required|integer|exists:users,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'semester_id' => 'required|integer|exists:semesters,id',
            'default_offline_class_location' =>  $checkLocationOff,
            'default_online_class_location' => $checkLocationOnl,
        ];
    }
}
