<?php

namespace Modules\NeaMeeting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer',
            'meeting_id' => 'nullable|integer',
            'notification_type' => 'nullable|string|max:50',
            'message' => 'nullable|string',
            'is_read' => 'nullable|integer'
        ];
    }
}