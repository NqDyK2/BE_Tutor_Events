<?php

namespace App\Http\Requests\Feedback;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackEventRequest extends FormRequest
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
            'message' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'message.max' => 'Message không được để trống',
            'message.max' => 'Message không đúng dịnh dạng',
            'message.max' => 'Message không được quá 255 ký tự',
        ];
    }
}
