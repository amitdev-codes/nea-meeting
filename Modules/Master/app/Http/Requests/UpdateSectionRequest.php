<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:sections,code',
            'name' => 'required|string|max:255',
            'name_np' => 'nullable|string|max:255',
            'status' => 'boolean'
        ];
    }
}