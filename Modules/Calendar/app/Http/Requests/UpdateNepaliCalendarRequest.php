<?php

namespace Modules\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNepaliCalendarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
        public function rules(): array
    {
        return [
            'bs_year' => ['sometimes', 'integer', 'min:1900', 'max:2099'],
            'month' => ['sometimes', 'integer', 'min:1', 'max:12'],
            'days' => ['sometimes', 'integer', 'min:1', 'max:32'],
            'start_date' => ['sometimes', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'bs_year.integer' => 'The Nepali year must be a number.',
            'bs_year.min' => 'The Nepali year must be at least 1900.',
            'bs_year.max' => 'The Nepali year cannot exceed 2099.',
            'month.integer' => 'The month must be a number.',
            'month.min' => 'The month must be between 1 and 12.',
            'month.max' => 'The month must be between 1 and 12.',
            'days.integer' => 'The number of days must be a number.',
            'days.min' => 'The number of days must be at least 1.',
            'days.max' => 'The number of days cannot exceed 32.',
            'start_date.date' => 'The start date must be a valid date.',
        ];
    }
}
