<?php

namespace App\Http\Requests\Feedback;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
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
            'classroom_quality' => 'required|min:1|max:3|integer',
            'teacher_quality' => 'required|min:1|max:3|integer',
            'tutor_quality' => 'required|min:1|max:3|integer',
            'understand' => 'required|min:0|max:100|integer',
            'message' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'classroom_quality.required' => 'Đánh giá buổi học không được để trống',
            'classroom_quality.integer' => 'Đánh giá buổi học sai định dạng',
            'classroom_quality.min' => 'Đánh giá buổi học sai định dạng',
            'classroom_quality.max' => 'Đánh giá buổi học sai định dạng',
            
            'teacher_quality.required' => 'Đánh giá giảng viên không được để trống',
            'teacher_quality.integer' => 'Đánh giá giảng viên sai định dạng',
            'teacher_quality.min' => 'Đánh giá giảng viên sai định dạng',
            'teacher_quality.max' => 'Đánh giá giảng viên sai định dạng',
            
            'tutor_quality.required' => 'Đánh giá trợ giảng không được để trống',
            'tutor_quality.integer' => 'Đánh giá trợ giảng sai định dạng',
            'tutor_quality.min' => 'Đánh giá trợ giảng sai định dạng',
            'tutor_quality.max' => 'Đánh giá trợ giảng sai định dạng',
            
            'understand.required' => 'Mức độ hiểu bài không được để trống',
            'understand.integer' => 'Mức độ hiểu bài sai định dạng',
            'understand.min' => 'Mức độ hiểu bài sai định dạng',
            'understand.max' => 'Mức độ hiểu bài sai định dạng',
            
            'message.max' => 'Message không được quá 255 ký tự',
        ];
    }
}
