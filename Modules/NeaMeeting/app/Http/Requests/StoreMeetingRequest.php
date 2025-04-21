<?php

namespace Modules\NeaMeeting\Http\Requests;

use Carbon\Carbon;
use App\Helpers\NepaliDateConverter;
use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust as needed
    }

    protected function prepareForValidation()
    {
        $meetingDate = $this->input('meeting_date'); // Expected format: YYYY-MM-DD (e.g., 2082-04-05)
        $startTime = $this->input('start_time'); // Expected format: h:i A (e.g., 1:00 PM)
        $endTime = $this->input('end_time'); // Expected format: h:i A (e.g., 3:00 PM)

        if ($meetingDate && $startTime) {
            try {
                // Parse Nepali date (YYYY-MM-DD)
                [$bsYear, $bsMonth, $bsDay] = explode('-', $meetingDate);
                $bsYear = (int)$bsYear;
                $bsMonth = (int)$bsMonth;
                $bsDay = (int)$bsDay;

                // Convert to Gregorian date using helper function
                $gregorianData = NepaliDateConverter::toGregorianDate($bsYear, $bsMonth, $bsDay); // Adjust namespace as needed
                $gregorianDate = $gregorianData['gregorian_date'];

                // Parse full datetime values
                $startDateTime = Carbon::createFromFormat('Y-m-d h:i A', "$gregorianDate $startTime");
                // $endDateTime = Carbon::createFromFormat('Y-m-d h:i A', "$gregorianDate $endTime");
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
                \Log::error('Nepali date conversion failed: ' . $e->getMessage());
                // Let validation fail if conversion fails
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
            'meeting_type' => 'nullable|string|max:50',
            'meeting_date' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}$/', // Validate Nepali date format (YYYY-MM-DD)
            'meeting_date_ad' => 'required|date', // Validate Gregorian date
            'start_time' => 'required|date', // Already converted to full datetime
            'end_time' => 'nullable|date',
            'meeting_room_id' => 'nullable|exists:meeting_rooms,id',
            'meeting_location' => 'nullable|string|max:255',
            'meeting_rooms' => 'nullable|string|max:255',
            'is_virtual' => 'nullable|boolean',
            'is_external' => 'nullable|boolean',
            'virtual_meeting_link' => 'nullable|string|max:255|required_if:is_virtual,1',
            'status' => 'string|in:scheduled,completed,cancelled',
            'created_by' => 'required|exists:users,id',
            'meeting_documents' => 'nullable|array',
            'meeting_documents.*' => 'string', // Temporary filenames for Dropzone
            'organizations' => 'nullable|array', // Add validation for organizations as array
            'organizations.*' => 'integer|exists:organizations,id', 
        ];
    }

    public function messages(): array
    {
        return [
          
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