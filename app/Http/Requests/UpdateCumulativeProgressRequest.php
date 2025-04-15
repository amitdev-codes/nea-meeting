<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCumulativeProgressRequest extends FormRequest
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
}