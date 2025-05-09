@component('mail::message')
# NEA Meeting Reminder

<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ asset('assets/img/nea-logo.png') }}" alt="NEA Logo" style="max-width: 80px;">
</div>

Dear Sir/Madam,

This is a reminder for the following upcoming meeting:

@component('mail::panel')
## {{ $meeting->title }}

**Date:** {{ $meetingDate }}<br>
**Time:** {{ $startTime }} - {{ $endTime }}<br>
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

Please ensure your attendance or contact the meeting organizer if you are unable to attend.

Thank you,<br>
NEA Meeting Management System

<div style="text-align: center; margin-top: 25px; color: #888; font-size: 12px;">
    © {{ date('Y') }} Nepal Electricity Authority. All rights reserved.
</div>
@endcomponent