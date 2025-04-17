<?php

namespace Modules\NeaMeeting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingNotifiedUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'meeting_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'notified_at' => 'nullable',
            'notification_type' => 'nullable|string|max:255',
            'notification_status' => 'nullable|string|max:255'
        ];
    }
}