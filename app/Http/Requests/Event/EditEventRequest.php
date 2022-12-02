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
            'name' => 'nullable|string',
            'image' => 'mimes:jpeg,jpg,png,gif|nullable',
            'status' => 'nullable|integer',
            'location' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $isExistsAnother = Event::where('location', $value)
                        ->where(function ($q) {
                            return $q->where('start_time', '<', $this->start_time)
                                ->where('end_time', '>', $this->start_time)
                                ->orWhere('start_time', '<', $this->end_time)
                                ->where('end_time', '>', $this->end_time)
                                ->orWhere('start_time', '>', $this->start_time)
                                ->where('end_time', '<', $this->end_time);
                        })->first();
                    if ($isExistsAnother) {
                        $fail('Địa điểm "' . $value . '" đã có sự kiện khác đăng ký từ ' . $isExistsAnother->start_time . ' đến ' .  $isExistsAnother->start_time);
                    }
                },
            ],
            'start_time' => [
                'nullable',
                'date',
                'after:now'.$this->start_time,
                function ($attribute, $value, $fail) {
                    $end_time_event = Event::where('id', $this->event_id)->first();
                    if ($end_time_event->end_time < $this->start_time) {
                        $fail('Thời gian bắt đầu của sự kiện không được lớn hơn thời gian kết thúc');
                    }
                },
            ],
            'end_time' => [
                'nullable',
                'date',
                'after:start_time'.$this->end_time,
                function ($attribute, $value, $fail) {
                    $end_time_event = Event::where('id', $this->event_id)->first();
                    if ($end_time_event->start_time > $this->end_time) {
                        $fail('Thời gian kết thúc của sự kiện không được nhỏ hơn thời gian bắt đầu');
                    }
                },
            ],
            'content' => 'nullable|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Tên sự kiện không đúng định dạng',

            'image.mimes' => 'Ảnh không đúng định dạng',

            'start_time.date' => 'Thời gian bắt đầu không đúng định dạng',
            'start_time.after' => 'Thời gian bắt đầu phải lớn hơn thời gian hiện tại',

            'end_time.date' => 'Thời gian kết thúc không đúng định dạng',
            'end_time.after' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu',

            'status.integer' => 'Status không đúng định dạng',

            'content.max' => 'Nội dung không được quá 2000 ký tự',
        ];
    }
}
