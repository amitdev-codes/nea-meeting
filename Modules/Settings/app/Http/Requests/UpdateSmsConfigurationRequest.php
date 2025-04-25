<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSmsConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'sms_provider_id' => 'required|exists:sms_providers,id',
            'api_token' => 'nullable|string',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'sender_id' => 'nullable|string',
            'base_url' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'additional_params' => 'nullable|json',
            'is_active'=>'nullable|boolean'
        ];
    }
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['is_active'] = $this->has('is_active') ? 1 : 0;

        return $key ? data_get($validated, $key, $default) : $validated;
    }
}