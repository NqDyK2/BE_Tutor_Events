<?php

namespace App\Http\Requests\Lesson;

use App\Models\Classroom;
use App\Models\Lesson;
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
        function thePresentTime()
        {
            $thePresentTime = now();
            return $thePresentTime = strtotime($thePresentTime);
        }
        $this->start_time = strtotime($this->start_time);
        $this->end_time = strtotime($this->end_time);
        return [
            'classroom_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $classroom = Classroom::find($value);
                    if (!$classroom) {
                        $fail('Lớp học này không tồn tại');
                    }
                },
            ],

            'class_location_online' => [
                'url',
                function ($attribute, $value, $fail) {
                    $checkStartTime = Lesson::where('class_location_online', $value)
                    ->where('classroom_id', '<>', $this->classroom_id)->get();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->start_time >= $startTimeIsset && $this->start_time <= $endTimeIsset) {
                            $fail('Thời gian bắt đầu tiết học của link meet này đã có lớp đăng ký');
                        }elseif ($this->end_time >= $startTimeIsset &&  $this->end_time <= $endTimeIsset) {
                            $fail('Thời gian kết thúc tiết học của link meet này có lớp đăng ký');
                        }
                    }
                },
            ],
            'class_location_offline' => [
                'string',
                function ($attribute, $value, $fail) {
                    $checkStartTime = Lesson::where('class_location_offline', $value)
                    ->where('classroom_id', '<>', $this->classroom_id)->get();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->start_time >= $startTimeIsset && $this->start_time <= $endTimeIsset) {
                            $fail('Thời gian bắt đầu tiết học của phòng '. $value .' đã có lớp đăng ký');
                        }elseif ($this->end_time >= $startTimeIsset &&  $this->end_time <= $endTimeIsset) {
                            $fail('Thời gian kết thúc tiết học của phòng '. $value .' đã có lớp đăng ký');
                        }
                    }
                },
            ],

            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    if ($this->start_time >= $this->end_time) {
                        $fail('Thời gian bắt đầu không được lớn hơn thời gian kết thúc');
                    }elseif ($this->start_time <= thePresentTime()) {
                        $fail('Thời gian không hợp lệ');
                    }
                },
                function ($attribute, $value, $fail) {
                    $checkStartTime = Lesson::where('classroom_id', $this->classroom_id)->get();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->start_time >= $startTimeIsset && $this->start_time <= $endTimeIsset) {
                            $fail('Thời gian bắt đầu tiết học bạn đã đăng ký');
                        }
                    }
                },
            ],

            'end_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    if ($this->end_time <= $this->start_time) {
                        $fail('Thời gian kết thúc không được nhỏ hơn thời gian bắt đầu');
                    }elseif ($this->end_time <= thePresentTime()) {
                        $fail('Thời gian không hợp lệ');
                    }
                },
                function ($attribute, $value, $fail) {
                    $checkStartTime = Lesson::where('classroom_id', $this->classroom_id)->get();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->end_time >= $startTimeIsset &&  $this->end_time <= $endTimeIsset) {
                            $fail('Thời gian kết thúc tiết học bạn đã đăng ký');
                        }
                    }
                },
            ],

            'type' => 'required|integer',
            'tutor_email' => 'email'
        ];
    }
}
