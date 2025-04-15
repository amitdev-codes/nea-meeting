<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title', 'title_size', 'title_case', 'title_color',
        'subtitle', 'subtitle_size', 'subtitle_case', 'subtitle_color',
        'url_text', 'url', 'link_type',
        'content_alignment', 'slider_status'
    ];

    protected $appends = ['thumb_url','preview_url'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($slider) {
            $slider->order = Slider::count() + 1;
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/assets/img/illustrations/page-misc-error-light.png');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->keepOriginalImageFormat()
            ->fit(Fit::Crop, 120, 120)
            ->performOnCollections('images')
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->keepOriginalImageFormat()
            ->fit(Fit::Crop, 1920, 1080)
            ->performOnCollections('images')
            ->nonQueued();
    }

    public function getPreviewUrlAttribute(){
        return $this->getMedia('images') ? $this->getFirstMediaUrl('images', 'preview') : null;
    }

    public function getThumbUrlAttribute(){
        return $this->getMedia('images') ? $this->getFirstMediaUrl('images', 'thumb') : null;
    }
}
