<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCumulativeProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'fiscal_year_id' => 'required|exists:mst_fiscal_years,id',
            'project_start_date'=>'required',
            'project_end_date'=>'required',
            'total_given_expenditure'=>'required',
            'total_budget'=>'required',
            'total_disbursed'=>'required',
            'total_group_formed_target'=>'required',
            'status' => 'nullable|boolean',
        ];
    }
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['status'] = $this->has('status') ? 1 : 0;


        return $key ? data_get($validated, $key, $default) : $validated;
    }
}