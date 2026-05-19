<?php

namespace Modules\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNepaliCalendarRequest extends FormRequest
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
            'bs_year' => ['required', 'integer', 'min:1900', 'max:2099'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'days' => ['required', 'integer', 'min:1', 'max:32'],
            'start_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'bs_year.required' => 'The Nepali year is required.',
            'bs_year.integer' => 'The Nepali year must be a number.',
            'bs_year.min' => 'The Nepali year must be at least 1900.',
            'bs_year.max' => 'The Nepali year cannot exceed 2099.',
            'month.required' => 'The month is required.',
            'month.integer' => 'The month must be a number.',
            'month.min' => 'The month must be between 1 and 12.',
            'month.max' => 'The month must be between 1 and 12.',
            'days.required' => 'The number of days is required.',
            'days.integer' => 'The number of days must be a number.',
            'days.min' => 'The number of days must be at least 1.',
            'days.max' => 'The number of days cannot exceed 32.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',
        ];
    }
}
