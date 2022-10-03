<?php

namespace App\Http\Requests\Semester;

use App\Models\Semester;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSemesterRequest extends FormRequest
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
            'name' => 'min:5|max:100|string|unique:semesters,name,'.$this->id,
            'start_time' => [
                'date_format:Y-m-d H:i:s',
                function($attribute, $value, $fail)
                {
                    if($this->start_time >= $this->end_time) {
                        $fail('Thời gian bắt đầu không được lớn hơn thời gian kết thúc');
                    }elseif ($this->start_time <= thePresentTime()) {
                        $fail('Thời gian không hợp lệ');
                    }
                },
                function ($attribute, $value, $fail) {
                    $checkStartTime = Semester::all();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->start_time >= $startTimeIsset && $this->start_time <= $endTimeIsset) {
                            $fail('Thời gian bắt đầu học kỳ bạn đã đăng ký');
                        }
                    }
                },
            ],

            'end_time' => [
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    if ($this->end_time <= $this->start_time) {
                        $fail('Thời gian kết thúc không được nhỏ hơn thời gian bắt đầu');
                    }elseif ($this->end_time <= thePresentTime()) {
                        $fail('Thời gian không hợp lệ');
                    }
                },
                function ($attribute, $value, $fail) {
                    $checkStartTime = Semester::all();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        if ($this->end_time >= $startTimeIsset &&  $this->end_time <= $endTimeIsset) {
                            $fail('Thời gian kết thúc học kỳ bạn đã đăng ký');
                        }
                    }
                },
            ],
        ];
    }
}
