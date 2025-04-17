<?php

namespace Modules\NeaMeeting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'meeting_id' => 'nullable|integer',
            'organization_id' => 'nullable|integer',
            'status' => 'boolean'
        ];
    }
}