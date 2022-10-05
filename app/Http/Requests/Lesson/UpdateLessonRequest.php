<?php

namespace App\Http\Requests\Lesson;

use App\Models\Classroom;
use App\Models\Lesson;
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
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    $checkStartTime = Lesson::where('class_location_online', $value)
                    ->where('classroom_id', '<>', $this->classroom_id)->get();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->start_time >= $startTimeIsset && $this->start_time <= $endTimeIsset || $this->end_time >= $startTimeIsset &&  $this->end_time <= $endTimeIsset || $startTimeIsset >= $this->start_time && $endTimeIsset <= $this->end_time) {
                            $fail('Khoảng thời gian này của link meet đã có lớp đăng ký');
                        }
                    }
                },
            ],
            'class_location_offline' => [
                'required',
                function ($attribute, $value, $fail) {
                    $checkStartTime = Lesson::where('class_location_offline', $value)
                    ->where('classroom_id', '<>', $this->classroom_id)->get();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->start_time >= $startTimeIsset && $this->start_time <= $endTimeIsset || $this->end_time >= $startTimeIsset &&  $this->end_time <= $endTimeIsset || $startTimeIsset >= $this->start_time && $endTimeIsset <= $this->end_time) {
                            $fail('Khoảng thời gian này của phòng học '.$value.' đã có lớp đăng ký');
                        }
                    }
                },
            ],

            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'before:end_time',
                function ($attribute, $value, $fail) {
                    $checkStartTime = Lesson::where('classroom_id', $this->classroom_id)->where('id','<>',$this->id)->get();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->start_time >= $startTimeIsset && $this->start_time <= $endTimeIsset || $this->end_time >= $startTimeIsset && $this->end_time <= $endTimeIsset || $startTimeIsset >= $this->start_time && $endTimeIsset <= $this->end_time) {
                            $fail('Khoảng thời gian này bạn đã đăng ký');
                        }elseif ($this->end_time <= thePresentTime()) {
                            $fail('Thời gian không hợp lệ');
                        }
                    }
                },
            ],
            
            'end_time' => 'required|date_format:Y-m-d H:i:s',
            'type' => 'required|integer',
            'tutor_email' => 'nullable|email',
            'document_path' => 'nullable|string',
        ];
    }
    
    public function messages()
    {
        return [
            'classroom_id.required' => 'Thiếu ID của lớp',
            'classroom_id.integer' => 'ID của lớp phải là số',

            'class_location_online.required' => 'địa chỉ buổi học online không được để trống',
            'class_location_online.url' => 'địa chỉ buổi học online phải là đường dẫn',

            'class_location_offline.required' => 'địa chỉ buổi học offline không được để trống',

            'start_time.required' => 'Thời gian bắt đầu không được để trống',
            'start_time.date_format' => 'Thời gian bắt đầu không đúng định dạng',
            'start_time.before' => 'Thời gian bắt đầu phải tồn tại trước thời gian kết thúc',

            'end_time.required' => 'Thời gian kết thúc không được để trống',
            'end_time.date_format' => 'Thời gian kết thúc không đúng định dạng',

            'type.required' => 'Hình thức học không được để trống',
            'type.integer' => 'phải là số',

            'tutor_email.email' => 'Email tutor không đúng định dạng',
            
            'document_path.string' => 'Tài liệu buổi học phải là chuỗi hoặc là đường dẫn',
        ];
    }
}
