<?php

namespace App\Http\Requests;

use App\Models\Lesson;
use App\Models\User;
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
            'message',
            'note',
            'lesson_id' => ['required',
                                'integer',
                                function ($attribute, $value, $fail) {
                                    $lesson = Lesson::find($value);
                                    if (!$lesson) {
                                        $fail('Buổi học không tồn tại');
                                    }
                                }
            ],
        ];
    }
    public function messages()
    {
        return [
            'leson_quality.required' => 'Đánh giá buổi học không được để trống',
            'teacher_quality.required' => 'Đánh giá giảng viên hỗ trợ không được để trống',
            'teacher_quality.required' => 'Đánh giá giảng viên không được để trống',
            'understand_lesson.required' => 'Đánh giá mức độ hiểu bài không được để trống',
            'lesson_id.required' => 'Không được để trống',
        ];
    }
}
