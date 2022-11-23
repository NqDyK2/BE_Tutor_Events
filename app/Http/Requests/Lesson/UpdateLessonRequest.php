<?php

namespace App\Http\Requests\Lesson;

use App\Models\Lesson;
use App\Models\Semester;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
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
            'nullable',
            'class_location' => [
                function ($attribute, $value, $fail) {
                    $isExistsAnother = Lesson::where('class_location', $value)
                        ->where('id', '!=', $this->lesson_id)
                        ->where(function ($q) {
                            return $q->where('start_time', '<', $this->start_time)
                                ->where('end_time', '>', $this->start_time)
                                ->orWhere('start_time', '<', $this->end_time)
                                ->where('end_time', '>', $this->end_time)
                                ->orWhere('start_time', '>', $this->start_time)
                                ->where('end_time', '<', $this->end_time);
                        })->first();
                    if ($isExistsAnother) {
                        $fail('Lớp học "' . $value . '" đã có lớp khác đăng ký từ ' . $isExistsAnother->start_time . ' đến ' .  $isExistsAnother->start_time);
                    }
                },
            ],
            'start_time' => 'nullable|date|after:now',
            'end_time' => [
                'nullable',
                'date',
                'after:start_time',
                function ($attribute, $value, $fail) {
                    $semester = Semester::join('classrooms', 'classrooms.semester_id', '=', 'semesters.id')
                        ->where('classrooms.id', $this->classroom_id)
                        ->where('semesters.start_time', '<=', $this->start_time)
                        ->where('semesters.end_time', '>=', $this->end_time)
                        ->first();
                    if (!$semester) {
                        $fail('Thời gian không nằm trong kỳ học');
                    }
                },
                function ($attribute, $value, $fail) {
                    $isExistsAnother = Lesson::where('classroom_id', $this->classroom_id)
                        ->where('id', '!=', $this->lesson_id)
                        ->where(function ($q) {
                            return $q->where('start_time', '<', $this->start_time)
                                ->where('end_time', '>', $this->start_time)
                                ->orWhere('start_time', '<', $this->end_time)
                                ->where('end_time', '>', $this->end_time)
                                ->orWhere('start_time', '>', $this->start_time)
                                ->where('end_time', '<', $this->end_time);
                        })->first();
                    if ($isExistsAnother) {
                        $fail('Thời gian không được trùng với buổi học khác ( ' .$isExistsAnother->start_time. ' to ' . $isExistsAnother->end_time .' )' );
                    }
                },
            ],
            'type' => 'required|boolean',
            'teacher_email' => 'required|email',
            'tutor_email' => 'nullable|email',
            'content' => 'nullable|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'start_time.date' => 'Thời gian bắt đầu không đúng định dạng',
            'start_time.after' => 'Thời gian bắt đầu phải lớn hơn thời gian hiện tại',

            'end_time.date' => 'Thời gian kết thúc không đúng định dạng',
            'end_time.after' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu',

            'type.boolean' => 'Type không đúng định dạng',

            'teacher_email.email' => 'Email giảng viên không đúng định dạng',
            'tutor_email.email' => 'Email tutor không đúng định dạng',

            'content.max' => 'Nội dung không được quá 2000 ký tự',
        ];
    }
}
