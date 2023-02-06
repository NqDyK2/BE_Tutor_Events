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
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:5120',
            'type' => 'integer',
            'location' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'content' => 'required|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sự kiện không được để trống',
            'name.string' => 'Tên sự kiện không đúng định dạng',

            'location.required' => 'Địa điểm sự kiện không được để trống',
            'location.string' => 'Địa điểm sự kiện không đúng định dạng',

            'image.required' => 'Ảnh không được để trống',
            'image.image' => 'Ảnh không đúng định dạng',
            'image.mimes' => 'Ảnh không đúng định dạng',
            'image.max' => 'Dung lượng ảnh không được vượt quá 5MB',

            'start_time.required' => 'Thời gian bắt đầu không được để trống',
            'start_time.date' => 'Thời gian bắt đầu không đúng định dạng',

            'end_time.required' => 'Thời gian kết thúc không được để trống',
            'end_time.date' => 'Thời gian kết thúc không đúng định dạng',
            'end_time.after' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu',

            'type.integer' => 'Type không đúng định dạng',
            'type.required' => 'Không được để trống type',

            'content.required' => 'Nội dung không được để trống',
            'content.max' => 'Nội dung không được quá 2000 ký tự',
        ];
    }
}
