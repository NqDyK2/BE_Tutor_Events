<?php

namespace App\Http\Requests\Event;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
            'name' => 'required|string',
            'image' => 'mimes:jpeg,jpg,png,gif|required',
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
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'content' => 'required|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sự kiện không được để trống',
            'name.string' => 'Tên sự kiện không đúng định dạng',

            'image.mimes' => 'Ảnh không đúng định dạng',
            'image.required' => 'Ảnh không được để trống',

            'start_time.required' => 'Thời gian bắt đầu không được để trống',
            'start_time.date' => 'Thời gian bắt đầu không đúng định dạng',
            'start_time.after' => 'Thời gian bắt đầu phải lớn hơn thời gian hiện tại',

            'end_time.required' => 'Thời gian kết thúc không được để trống',
            'end_time.date' => 'Thời gian kết thúc không đúng định dạng',
            'end_time.after' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu',

            'status.integer' => 'Status không đúng định dạng',

            'content.required' => 'Nội dung không được để trống',
            'content.max' => 'Nội dung không được quá 2000 ký tự',
        ];
    }
}
