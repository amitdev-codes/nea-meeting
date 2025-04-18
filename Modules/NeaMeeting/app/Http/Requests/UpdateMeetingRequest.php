<?php

namespace Modules\NeaMeeting\Http\Requests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    protected function prepareForValidation()
    {
        $meetingDate = $this->input('meeting_date');
        $startTime = $this->input('start_time');
        $endTime = $this->input('end_time');
        
        if ($meetingDate && $startTime) {
            try {
                // Convert from custom calendar to Gregorian
                $gregorianYear = (int)substr($meetingDate, 0, 4) - 57;
                $gregorianDate = "$gregorianYear-" . substr($meetingDate, 5);
                
                // Parse full datetime values
                $startDateTime = Carbon::createFromFormat('Y-m-d h:i A', "$gregorianDate $startTime");
               
                $mergeData = [
                    'meeting_date_ad' => $gregorianDate, // Store Gregorian date
                    'start_time' => $startDateTime->toDateTimeString(),
                ];
                if ($endTime) {
                    $endDateTime = Carbon::createFromFormat('Y-m-d h:i A', "$gregorianDate $endTime");
                    $mergeData['end_time'] = $endDateTime->toDateTimeString();
                }
                $this->merge($mergeData);
            } catch (\Exception $e) {
                \Log::error('Time parsing failed: ' . $e->getMessage());
                // Don't merge invalid data, let validation catch it
            }
        }
                // Process organizations input to ensure it's an array
                if ($this->has('organizations')) {
                    $organizations = $this->input('organizations');
                    
                    // If organizations is provided as a string (like a comma-separated list), convert it to array
                    if (is_string($organizations) && !empty($organizations)) {
                        $this->merge(['organizations' => explode(',', $organizations)]);
                    }
                }
        if (!$this->has('created_by')) {
            $this->merge(['created_by' => auth()->id()]);
        }
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'meeting_type' => 'required|string|max:50',
            'meeting_date' => 'nullable|string', // Not stored in DB
            'start_time' => 'required|date', // Changed from date_format:H:i:s to date
            'end_time' => 'nullable|date', // Changed to date
            'meeting_room_id' => 'nullable|exists:meeting_rooms,id',
            'meeting_location' => 'nullable|string|max:255',
            'is_virtual' => 'boolean',
            'is_external' => 'boolean',
            'virtual_meeting_link' => 'nullable|string|max:255|required_if:is_virtual,1',
            'status' => 'string|in:scheduled,completed,cancelled',
            'created_by' => 'required|exists:users,id',
            'meeting_documents' => 'nullable|array',
            // Adjust for filenames instead of files if using Dropzone
            'meeting_documents.*' => 'string', // Temporary filenames, not files
            'organizations' => 'nullable|array', // Add validation for organizations as array
            'organizations.*' => 'integer|exists:organizations,id', // Ensure each organization ID exists
        ];
    }

    public function messages(): array
    {
        return [
            'start_time.date' => 'The start time must be a valid time (e.g., 1:00 PM).',
            'created_by.required' => 'The creator ID is required.',
            'organizations.*.exists' => 'One or more selected organizations do not exist.',
        ];
    }
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['is_external'] = $this->has('is_external') ? 1 : 0;
        $validated['is_virtual'] = $this->has('is_virtual') ? 1 : 0;

        return $key ? data_get($validated, $key, $default) : $validated;
    }
}