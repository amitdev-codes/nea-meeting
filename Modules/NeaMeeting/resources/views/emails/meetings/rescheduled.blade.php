@component('mail::message')
# NEA Meeting Rescheduled Notification

<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ asset('assets/img/nea-logo.png') }}" alt="NEA Logo" style="max-width: 100px;">
</div>

Dear Sir/Madam,

We wish to inform you that the following meeting has been **rescheduled**:

@component('mail::panel')
## {{ $meeting->title }}

**New Date:** {{ $meetingDate }}<br>
**New Time:** {{ $startTime }} - {{ $endTime }}<br>
**Type:** {{ $meeting->meeting_type }}<br>

@if($meeting->is_virtual)
**Meeting Type:** Virtual<br>
**Link:** [{{ $meeting->virtual_meeting_link }}]({{ $meeting->virtual_meeting_link }})
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

Please update your calendar accordingly. If you have any questions, please contact the meeting organizer.

Thank you,<br>
NEA Meeting Management System

<div style="text-align: center; margin-top: 25px; color: #888; font-size: 12px;">
    © {{ date('Y') }} Nepal Electricity Authority. All rights reserved.
</div>
@endcomponent