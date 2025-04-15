<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name_np' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:{{table}},code',
            'status' => 'boolean'
        ];
    }
}