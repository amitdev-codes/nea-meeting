<?php

namespace Modules\NeaMeeting\Models;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\NeaMeeting\Models\MeetingRoom;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Meeting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'meetings';

    protected $fillable = [
        'title',
        'description',
        'meeting_type',
        'meeting_date', // Add this if you want to store it
        'start_time',
        'end_time',
        'meeting_room_id',
        'meeting_location',
        'is_virtual',
        'virtual_meeting_link',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'string', // or 'time' if using Laravel 9+
        'end_time' => 'string',
        'meeting_room_id' => 'integer',
        'is_virtual' => 'boolean', // Should be boolean, not integer
        'created_by' => 'integer',
        'status' => 'string', // Status should be string, not boolean
    ];

    public function meetingRoom(): BelongsTo
    {
        return $this->belongsTo(MeetingRoom::class, 'meeting_room_id', 'id');
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
}