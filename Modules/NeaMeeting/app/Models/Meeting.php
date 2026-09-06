<?php

namespace Modules\NeaMeeting\Models;

use Spatie\Image\Enums\Fit;
use App\Enums\MeetingStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Models\Organization;
use Modules\NeaMeeting\Models\MeetingRoom;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\NeaMeeting\Models\ExternalContact;
use Modules\NeaMeeting\Models\MeetingAttendee;
use Modules\NeaMeeting\Models\MeetingNotifiedUser;
use Modules\NeaMeeting\Models\MeetingOrganization;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\NeaMeeting\Models\MeetingNotifiedExternalContact;

class Meeting extends Model implements HasMedia
{
    use  InteractsWithMedia;

    protected $table = 'meetings';

    protected $fillable = [
        'title',
        'description',
        'meeting_type',
        'meeting_date',
        'meeting_date_ad',
        'start_time',
        'end_time',
        'meeting_location',
        'meeting_rooms',
        'meeting_room_id',
        'is_virtual',
        'is_external',
        'virtual_meeting_link',
        'status',
        'created_by',
        'organizations',
        'remarks',
        'google_calendar_event_id',
        'google_calendar_link',
    ];

    protected $casts = [
        'organizations' => 'array',
        'start_time' => 'string', // or 'time' if using Laravel 9+
        'end_time' => 'string',
        'meeting_room_id' => 'integer',
        'is_virtual' => 'boolean',
        'is_external' => 'boolean',
        'created_by' => 'integer',
        // 'status' => MeetingStatus::class,
        'status' => 'string',
    ];
    protected $attributes = [
        'fiscal_year_id' => 8,
    ];

    public function meetingRoom(): BelongsTo
    {
        return $this->belongsTo(MeetingRoom::class, 'meeting_room_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('meetings')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'text/plain',
                'application/octet-stream' // Allow generic type

        ]);
    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->keepOriginalImageFormat()
            ->fit(Fit::Crop, 120, 120)
            ->nonQueued();

        $this
            ->addMediaConversion('preview')
            ->keepOriginalImageFormat()
            ->fit(Fit::Crop, 400, 400)
            ->nonQueued();
    }
    public function meetingOrganizations()
    {
        return $this->hasMany(MeetingOrganization::class);
    }


    public function attendees()
    {
        return $this->hasMany(MeetingAttendee::class, 'meeting_id');
    }
    public function notifiedUsers()
    {
        return $this->hasMany(MeetingNotifiedUser::class, 'meeting_id');
    }
    public function notifiedExternalContacts()
    {
        return $this->hasMany(MeetingNotifiedExternalContact::class);
    }
    public function externalContacts(): Morphmany
    {
        return $this->morphMany(ExternalContact::class, 'contactable');
    }
}
