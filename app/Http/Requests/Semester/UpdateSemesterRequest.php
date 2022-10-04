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
        return [
            'name' => 'min:5|max:100|unique:semesters,name,'.$this->id,
            'start_time' => 'date|before:end_time',
            'end_time' => [
                'date',
                function ($attribute, $value, $fail) {
                    $isExistsAnother = Semester::where('id', '!=', $this->id)
                    ->where(function ($q){
                        return $q->where('start_time', '<=', $this->start_time)
                        ->where('end_time', '>=', $this->start_time)
                        ->orWhere('start_time', '<=', $this->end_time)
                        ->where('end_time', '>=', $this->end_time)
                        ->orWhere('start_time', '>=', $this->start_time)
                        ->where('end_time', '<=', $this->end_time);
                    })->first();
                    if ($isExistsAnother) {
                        $fail('Đã có kỳ học khác diễn ra trong thời gian này (' . $isExistsAnother->name . ')');
                    }
                },
            ],
        ];
    }
    public function messages()
    {
        return [
            'name.min' => 'Tên kỳ học phải lớn hơn 5 ký tự',
            'name.required' => 'Tên kỳ học phải nhỏ hơn 100 ký tự',
            'name.unique' => 'Tên kỳ học đã tồn tại',
            'start_time.date' => 'Thời gian bắt đầu không đúng định dạng',
            'start_time.before' => 'Thời gian bắt đầu phải lớn hơn thời gian kết thúc',
            'end_time.date' => 'Thời gian bắt đầu không đúng định dạng',
        ];
    }
}
