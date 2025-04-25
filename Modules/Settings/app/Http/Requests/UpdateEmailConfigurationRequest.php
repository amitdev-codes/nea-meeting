<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
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