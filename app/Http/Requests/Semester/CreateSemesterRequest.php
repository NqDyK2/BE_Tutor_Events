<?php

namespace App\Http\Requests\Semester;

use App\Models\Semester;
use Illuminate\Foundation\Http\FormRequest;

class CreateSemesterRequest extends FormRequest
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
            'name' => 'required|min:5|max:100|unique:semesters|NULL,id,deleted_at,NULL',
            'start_time' => 'required|date|before:end_time',
            'end_time' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $isExistsAnother = Semester::where('start_time', '<=', $this->start_time)
                        ->where('end_time', '>=', $this->start_time)
                        ->orWhere('start_time', '<=', $this->end_time)
                        ->where('end_time', '>=', $this->end_time)
                        ->orWhere('start_time', '>=', $this->start_time)
                        ->where('end_time', '<=', $this->end_time)
                        ->first();
                    if ($isExistsAnother) {
                        $fail('Đã có kỳ học khác diễn ra trong thời gian này: ' . $isExistsAnother->name . ' (' . substr($isExistsAnother->start_time, 0, 10) . ' to ' . substr($isExistsAnother->end_time, 0, 10) . ')');
                    }
                },
            ],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên kỳ học không được để trống',
            'name.min' => 'Tên kỳ học phải lớn hơn 5 ký tự',
            'name.max' => 'Tên kỳ học phải nhỏ hơn 100 ký tự',
            'name.unique' => 'Tên kỳ học đã tồn tại',

            'start_time.required' => 'Thời gian bắt đầu không được để trống',
            'start_time.date' => 'Thời gian bắt đầu không đúng định dạng',
            'start_time.before' => 'Thời gian bắt đầu phải lớn hơn thời gian kết thúc',
            
            'end_time.required' => 'Thời gian kết thúc không được để trống',
            'end_time.date' => 'Thời gian kết thúc không đúng định dạng',
        ];
    }
}
