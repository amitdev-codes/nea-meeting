<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEA Meeting Cancellation Notice</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; color: #333;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; margin: 20px auto; border: 1px solid #e0e0e0; border-radius: 8px;">
        <tr>
            <td style="padding: 20px; text-align: center;">
                <img src="{{ asset('assets/img/nea-logo.png') }}" alt="NEA Logo" style="max-width: 80px; height: auto;">
            </td>
        </tr>
        <tr>
            <td style="padding: 0 20px 20px;">
                <h1 style="font-size: 24px; color: #d32f2f; text-align: center; margin: 0 0 20px;">NEA Meeting Cancellation Notice</h1>
                <p style="font-size: 16px; line-height: 1.5; margin: 0 0 20px;">
                    Dear <strong>{{ $user->username }}</strong>,
                </p>
                <p style="font-size: 16px; line-height: 1.5; margin: 0 0 20px;">
                    We regret to inform you that the following meeting has been cancelled:
                </p>

                <!-- Meeting Details -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px; padding: 20px; margin-bottom: 20px;">
                    <tr>
                        <td>
                            <h2 style="font-size: 20px; color: #333; margin: 0 0 10px;">{{ $meeting->title }}</h2>
                            <p style="font-size: 14px; line-height: 1.5; margin: 5px 0;">
                                <strong>Date:</strong> {{ $meetingDate }}<br>
                                <strong>Time:</strong> {{ $startTime }} - {{ $endTime }}<br>
                                <strong>Type:</strong> {{ $meeting->meeting_type }}<br>
                                @if($meeting->is_virtual)
                                    <strong>Meeting Type:</strong> Virtual<br>
                                    <strong>Link:</strong> {{ $meeting->virtual_meeting_link }} (No longer active)
                                @else
                                    <strong>Location:</strong> {{ $meeting->meeting_location ?? 'N/A' }}
                                    @if($meeting->meeting_room_id)
                                        <br><strong>Room:</strong> {{ $meeting->meetingRoom->name ?? 'N/A' }}
                                    @endif
                                @endif
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Meeting Description -->
                @if($meeting->description)
                    <h3 style="font-size: 18px; color: #333; margin: 0 0 10px;">Meeting Description:</h3>
                    <p style="font-size: 14px; line-height: 1.5; margin: 0 0 20px;">{{ $meeting->description }}</p>
                @endif

                <!-- Cancellation Reason -->
                @if($reason)
                    <h3 style="font-size: 18px; color: #333; margin: 0 0 10px;">Reason for Cancellation:</h3>
                    <p style="font-size: 14px; line-height: 1.5; margin: 0 0 20px;">{{ $reason }}</p>
                @endif

                <p style="font-size: 16px; line-height: 1.5; margin: 0 0 20px;">
                    No further action is required. If you have any questions, please contact the meeting organizer.
                </p>
                <p style="font-size: 16px; line-height: 1.5; margin: 0;">
                    Thank you,<br>
                    <strong>NEA Meeting Management System</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center; background-color: #f4f4f4; border-top: 1px solid #e0e0e0;">
                <p style="font-size: 12px; color: #888; margin: 0;">
                    © {{ date('Y') }} Nepal Electricity Authority. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>