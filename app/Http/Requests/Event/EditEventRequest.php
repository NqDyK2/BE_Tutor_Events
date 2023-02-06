<?php

namespace App\Http\Requests\Event;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

class EditEventRequest extends FormRequest
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
            'name' => 'string',
            'image' => 'mimes:jpeg,jpg,png,gif|max:5120',
            'type' => 'integer',
            'location' => 'string',
            'start_time' => 'date|before:end_time',
            'end_time' => [
                'date',
                function ($attribute, $value, $fail) {
                    $end_time_event = Event::where('id', $this->event_id)->first();
                    if ($end_time_event->start_time >= $this->end_time) {
                        $fail('Thời gian kết thúc phải lớn hơn thời gian bắt đầu');
                    }
                },
            ],
            'content' => 'max:2000',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Tên sự kiện không đúng định dạng',

            'image.mimes' => 'Ảnh không đúng định dạng',
            'image.max' => 'Dung lượng ảnh không được vượt quá 5MB',

            'location.string' => 'Địa điểm sự kiện không đúng định dạng',

            'start_time.date' => 'Thời gian bắt đầu không đúng định dạng',
            'start_time.after' => 'Thời gian bắt đầu phải lớn hơn thời gian hiện tại',
            'start_time.before' => 'Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc',

            'end_time.date' => 'Thời gian kết thúc không đúng định dạng',

            'type.integer' => 'Type không đúng định dạng',

            'content.max' => 'Nội dung không được quá 2000 ký tự',
        ];
    }
}
