system tailored for NEA Nepal. Let me explain the key components:

Users table: Stores information about employees who can create or attend meetings.
Meeting Rooms: Tracks physical meeting spaces with details about capacity and available equipment.
Meetings: The core table that stores meeting details including title, time, location, and status.
Meeting Attendees: A junction table that links users to meetings and tracks attendance.
Meeting Documents: Stores files associated with meetings (agendas, presentations, etc.).
Meeting Minutes: Records the official minutes of each meeting.
Action Items: Tracks tasks assigned during meetings, who they're assigned to, and their status.
Meeting Reminders: Manages automated reminders sent to participants.
Meeting Recurrence: Handles settings for recurring meetings.
Notifications: An optional table to manage system notifications.

Would you like me to modify any of these migrations or would you prefer them in a different format? I can also help with creating specific migration files for frameworks like Ruby on Rails, Laravel, Django, or other technologies if needed.