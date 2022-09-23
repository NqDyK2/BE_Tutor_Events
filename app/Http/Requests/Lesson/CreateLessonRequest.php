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
        return [
            'classroom_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $classroom = Classroom::find($value);
                    if (!$classroom) {
                        $fail('Classroom is not exists.');
                    }
                },
            ],

            'class_location' => [
                function ($attribute, $value, $fail) {
                    $checkStartTime = Lesson::where('class_location', $value)
                    ->where('classroom_id', '<>', $this->classroom_id)->get();
                    foreach ($checkStartTime as $time) { 
                        $startTimeIsset = strtotime($time->start_time);
                        $endTimeIsset = strtotime($time->end_time);
                        $startTimeCreate = strtotime($this->start_time);
                        $endTimeCreate = strtotime($this->end_time);
                        $thePresentTime = now();
                        $thePresentTime = strtotime($thePresentTime);
                        if ($startTimeCreate >= $startTimeIsset && $startTimeCreate <= $endTimeIsset) {
                            $fail('This start time already has class use');
                        }elseif ($endTimeCreate >= $startTimeIsset && $endTimeCreate <= $endTimeIsset) {
                            $fail('This end time has used class');
                        }elseif ($startTimeCreate <= $thePresentTime) {
                            $fail('Invalid time');
                        }
                    }
                },
            ],

            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $endTimeCreate = strtotime($this->end_time);
                    $startTimeCreate = strtotime($value);
                    if ($startTimeCreate >= $endTimeCreate) {
                        $fail('the start time cannot be greater than the end time');
                    }
                },
            ],

            'end_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $endTimeCreate = strtotime($value);
                    $startTimeCreate = strtotime($this->start_time);
                    if ($endTimeCreate <= $startTimeCreate) {
                        $fail('the end time must not be less than the start time');
                    }
                },
            ],

            'type' => 'required|integer'
        ];
    }
}
