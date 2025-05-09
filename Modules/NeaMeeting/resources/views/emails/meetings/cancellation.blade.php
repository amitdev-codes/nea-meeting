@component('mail::message')
# NEA Meeting Cancellation Notification

<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ asset('assets/img/nea-logo.png') }}" alt="NEA Logo" style="max-width: 80px;">
</div>

Dear Sir/Madam,

We regret to inform you that the following meeting has been **cancelled**:

@component('mail::panel')
## {{ $meeting->title }}

**Original Date:** {{ $meetingDate }}<br>
**Original Time:** {{ $startTime }} - {{ $endTime }}<br>
**Type:** {{ $meeting->meeting_type }}<br>

@if($meeting->is_virtual)
**Meeting Type:** Virtual
@else
**Location:** {{ $meeting->meeting_location ?? 'To be announced' }}
@if($meeting->meeting_room_id)
<br>**Room:** {{ $meeting->meetingRoom->name ?? 'No room specified' }}
@endif
@endif
@endcomponent

@if($meeting->description)
### Meeting Description:
{{ $meeting->description }}
@endif

If you have any questions or require further details, please contact the meeting organizer.

Thank you,<br>
NEA Meeting Management System

<div style="text-align: center; margin-top: 25px; color: #888; font-size: 12px;">
    © {{ date('Y') }} Nepal Electricity Authority. All rights reserved.
</div>
@endcomponent