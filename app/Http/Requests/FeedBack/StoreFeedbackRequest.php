<?php

namespace App\Http\Requests\FeedBack;

use App\Models\Lesson;
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
            'lesson_quality' => 'required|min:1|max:3|integer',
            'teacher_quality' => 'required|min:1|max:3|integer',
            'support_quality' => 'required|min:1|max:3|integer',
            'understand_lesson' => 'required|min:0|max:100|integer',
            'message' => 'string|string|min:0|max:255',
            'note',
            'lesson_id' => 'required|integer|exists:lessons,id'
            // 'lesson_id' => [
            //     'required',
            //     'integer',
            //     function ($attribute, $value, $fail) {
            //         $lesson = Lesson::find($value);
            //         if (!$lesson) {
            //             $fail('Buổi học không tồn tại');
            //         }
            //     }
            // ],
        ];
    }
    public function messages()
    {
        return [
            'leson_quality.required' => 'Đánh giá buổi học không được để trống',
            'leson_quality.integer' => 'Đánh giá buổi học phải là số',
            'teacher_quality.required' => 'Đánh giá giảng viên không được để trống',
            'teacher_quality.integer' => 'Đánh giá giảng viên phải là số',
            'support_quality.required' => 'Đánh giá giảng viên hỗ trợ không được để trống',
            'support_quality.integer' => 'Đánh giá giảng viên hỗ trợ phải là số',
            'understand_lesson.required' => 'Đánh giá mức độ hiểu bài không được để trống',
            'understand_lesson.required' => 'Đánh giá mức độ hiểu bài phải là số',
            'lesson_id.required' => 'Không được để trống id buổi học',
            'lesson_id.required' => 'ID buổi học phải là số',
            'lesson_id.exists' => 'Lớp học này không tồn tại',
        ];
    }
}
