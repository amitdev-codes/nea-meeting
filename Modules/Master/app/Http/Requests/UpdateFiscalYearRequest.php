<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFiscalYearRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:fiscal_years,code,'.$this->route('fiscal_year'),
            'date_from_bs' => 'required|string|date_format:Y-m-d',
            'date_to_bs' => 'required|string|date_format:Y-m-d|after_or_equal:date_from_bs',
            'date_from_ad' => 'required|date|date_format:Y-m-d',
            'date_to_ad' => 'required|date|date_format:Y-m-d|after_or_equal:date_from_ad',
            'is_current' => 'nullable|boolean',
            'is_previous' => 'nullable|boolean',
            'is_next' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['status'] = $this->has('status') ? 1 : 0;

        return $key ? data_get($validated, $key, $default) : $validated;
    }
}
