<?php

namespace App\Http\Requests\User;

use App\Rules\AdminRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'string|min:5|max:50',
            'gender' => 'integer|min:1|max:2',
            'address' => 'string|min:5|max:150',
            'phone_number' => 'numeric|digits_between:10,12',
            'dob' => 'date|before:now',
            'role_id' => ['integer', 'exists:roles,id', new AdminRule],
            'status' => ['integer', 'min:0', 'max:1', new AdminRule],
        ];
    }
}
