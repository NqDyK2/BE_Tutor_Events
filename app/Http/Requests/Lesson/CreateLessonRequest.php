<?php

namespace App\Http\Requests\Lesson;

use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Semester;
use Illuminate\Foundation\Http\FormRequest;

class CreateLessonRequest extends FormRequest
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
            'classroom_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $classroom = Classroom::find($value);
                    if (!$classroom) {
                        $fail('Lớp học không tồn tại');
                    }
                },
            ],
            'class_location_online' => [
                'url',
                function ($attribute, $value, $fail) {
                    $isExistsAnother = Lesson::where('class_location_online', $value)
                        ->where('classroom_id', '<>', $this->classroom_id)
                        ->whereHas('classroom', function ($q) {
                            $classroom = Classroom::find($this->classroom_id);
                            return $q->where('semester_id', '=', $classroom->semester_id);
                        })
                        ->first();
                    if ($isExistsAnother) {
                        $fail('Link học đã có lớp khác đăng ký');
                    }
                },
            ],
            'class_location_offline' => [
                function ($attribute, $value, $fail) {
                    $isExistsAnother = Lesson::where('class_location_offline', $value)
                        ->where(function ($q) {
                            return $q->where('start_time', '<=', $this->start_time)
                                ->where('end_time', '>=', $this->start_time)
                                ->orWhere('start_time', '<=', $this->end_time)
                                ->where('end_time', '>=', $this->end_time)
                                ->orWhere('start_time', '>=', $this->start_time)
                                ->where('end_time', '<=', $this->end_time);
                        })->first();
                    if ($isExistsAnother) {
                        $fail('Lớp học "' . $value . '" đã có lớp khác đăng ký từ ' . $isExistsAnother->start_time . ' đến ' .  $isExistsAnother->start_time);
                    }
                },
            ],
            'start_time' => 'required|date_format:Y-m-d H:i:s|before:end_time',
            'end_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
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
                        ->where(function ($q) {
                            return $q->where('start_time', '<=', $this->start_time)
                                ->where('end_time', '>=', $this->start_time)
                                ->orWhere('start_time', '<=', $this->end_time)
                                ->where('end_time', '>=', $this->end_time)
                                ->orWhere('start_time', '>=', $this->start_time)
                                ->where('end_time', '<=', $this->end_time);
                        })->first();
                    if ($isExistsAnother) {
                        $fail('Thời gian không được trùng với buổi học khác');
                    }
                },
            ],
            'type' => 'required|boolean',
            'teacher_email' => 'required|email',
            'tutor_email' => 'email',
            'document_path' => 'url',
        ];
    }

    public function messages()
    {
        return [
            'classroom_id.required' => 'Id lớp học không được để trống',
            'classroom_id.integer' => 'Id lớp học sai định dạng',

            'class_location_online.required' => 'Link học online không được để trống',
            'class_location_online.url' => 'Link học online sai định dạng',

            'class_location_offline.required' => 'Lớp học không được để trống',

            'start_time.required' => 'Thời gian bắt đầu không được để trống',
            'start_time.date_format' => 'Thời gian bắt đầu không đúng định dạng',
            'start_time.before' => 'Thời gian bắt đầu phải lớn hơn thời gian kết thúc',

            'end_time.required' => 'Thời gian kết thúc không được để trống',
            'end_time.date_format' => 'Thời gian kết thúc không đúng định dạng',

            'type.required' => 'Type không được để trống',
            'type.integer' => 'Type không đúng định dạng',

            'teacher_email.email' => 'Email giảng viên không đúng định dạng',
            'tutor_email.email' => 'Email tutor không đúng định dạng',

            'document_path.url' => 'Link tài nguyên buổi học không đúng định dạng',
        ];
    }
}
