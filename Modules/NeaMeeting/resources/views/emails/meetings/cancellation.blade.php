@component('mail::message')
# NEA Meeting Cancellation Notice

<div style="text-align: center; margin-bottom: 25px;">
    <img src="{{ asset('assets/img/nea-logo.png') }}" alt="NEA Logo" style="max-width: 80px;">
</div>

Dear **{{ $user->username }}**,

We regret to inform you that the following meeting has been **cancelled**:

@component('mail::panel')
## {{ $meeting->title }}

**Date:** {{ $meetingDate }}<br>
**Time:** {{ $startTime }} - {{ $endTime }}<br>
**Type:** {{ $meeting->meeting_type }}<br>

@if($meeting->is_virtual)
**Meeting Type:** Virtual<br>
**Link:** {{ $meeting->virtual_meeting_link ?? 'N/A' }}
@else
**Location:** {{ $meeting->meeting_location ?? 'N/A' }}
@if($meeting->meeting_room_id)
<br>**Room:** {{ $meeting->meetingRoom->name ?? 'N/A' }}
@endif
@endif
@endcomponent

@if($meeting->description)
### Meeting Description:
{{ $meeting->description }}
@endif

### Reason for Cancellation:
@if($meeting->cancellation_reason)
{{ $meeting->cancellation_reason }}
@else
No specific reason provided.
@endif

If you have any questions or need further assistance, please contact the meeting organizer.

Thank you for your understanding,<br>
NEA Meeting Management System

<div style="text-align: center; margin-top: 25px; color: #888; font-size: 12px;">
    © {{ date('Y') }} Nepal Electricity Authority. All rights reserved.
</div>
@endcomponent