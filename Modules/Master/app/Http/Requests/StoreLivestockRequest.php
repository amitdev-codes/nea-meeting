<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLivestockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:{{table}},code',
            'name' => 'required|string|max:255',
            'name_np' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ];
    }
}