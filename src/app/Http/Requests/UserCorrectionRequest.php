<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCorrectionRequest extends FormRequest
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
            'requested_start_time' => ['date_format:H:i'],

            'requested_end_time' => ['date_format:H:i', 'after:requested_start_time'],

            'requested_break_start' => ['nullable', 'date_format:H:i', 'after:requested_start_time', 'before:requested_end_time'],

            'requested_break_end' => ['nullable', 'date_format:H:i', 'after:requested_break_start', 'before:requested_end_time'],

            'remarks' => ['required', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'requested_end_time.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'requested_break_start.after' => '休憩時間が勤務時間外です',
            'requested_break_end.before' => '休憩時間が勤務時間外です',
            'remarks.required' => '備考を記入してください'
        ];
    }
}
