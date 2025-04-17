<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCorrectionRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'start_time' => ['date_format:H:i'],

            'end_time' => ['date_format:H:i', 'after:start_time'],

            'break_start' => ['nullable', 'date_format:H:i', 'after:start_time', 'before:end_time'],

            'break_end' => ['nullable', 'date_format:H:i', 'after:break_start', 'before:end_time'],

            'remarks' => ['required', 'string', 'max:255']
        ];
    }


    public function messages()
    {
        return [
            'end_time.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'break_start.after' => '休憩時間が勤務時間外です',
            'break_end.before' => '休憩時間が勤務時間外です',
            'remarks.required' => '備考を記入してください'
        ];
    }
}
