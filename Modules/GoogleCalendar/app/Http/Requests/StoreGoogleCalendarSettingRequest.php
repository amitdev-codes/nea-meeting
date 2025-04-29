<?php

namespace Modules\GoogleCalendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoogleCalendarSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'is_enabled' => 'nullable|integer',
            'google_calendar_id' => 'nullable|string|max:255',
            'client_id' => 'nullable|string|max:255',
            'client_secret' => 'nullable|string|max:255',
            'redirect_uri' => 'nullable|string|max:255',
            'service_account_json' => 'nullable|string',
            'auth_method' => 'nullable',
            'organization_id' => 'nullable|integer'
        ];
    }
}