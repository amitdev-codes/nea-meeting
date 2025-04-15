<?php

namespace Modules\NeaMeeting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingReminderRequest extends FormRequest
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
            'reminder_time' => 'nullable',
            'sent' => 'nullable|integer',
            'sent_at' => 'nullable'
        ];
    }
}