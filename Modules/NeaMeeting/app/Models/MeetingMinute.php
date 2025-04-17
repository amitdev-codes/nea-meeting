<?php

namespace Modules\NeaMeeting\Models;

use App\Models\User;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MeetingMinute extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
    protected $table = 'meeting_minutes';
    
    protected $fillable = [
        'meeting_id',
        'content',
        'recorded_by',
        'approved',
        'approved_by',
        'approved_at'
    ];
    
    protected $casts = [
        'meeting_id' => 'integer',
        'recorded_by' => 'integer',
        'approved' => 'integer',
        'approved_by' => 'integer',
        'approved_at' => 'datetime'
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('MeetingMinute')
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
    public function meeting():BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }


    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'recorded_by');
    }
}