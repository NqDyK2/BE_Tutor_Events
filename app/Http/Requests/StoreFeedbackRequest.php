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
            'lesson_quality' => 'required',
            'teacher_quality' => 'required',
            'support_quality' => 'required',
            'understand_lesson' => 'required',
            'message',
            'note',
            'user_id' => ['required',
                            'integer',
                            function ($attribute, $value, $fail) {
                                $user = User::find($value);
                                if (!$user) {
                                    $fail('Học sinh không tồn tại');
                                }
                            }
            ],
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
            'leson_quality' => 'Không được để trống',
            'teacher_quality' => 'Không được để trống',
            'teacher_quality' => 'Không được để trống',
            'understand_lesson' => 'Không được để trống',
            'user_id' => 'Không được để trống',
            'user_id' => 'Không được để trống',
        ];
    }
}
