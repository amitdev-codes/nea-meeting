<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SiteSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['settings'];

    protected $casts = [
        'settings' => 'json',
    ];

    protected $appends = ['company_logo_preview_url',
        'favicon_preview_url', 'about_us_preview_url'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($setting) {
            $setting->created_by = auth()->id();
        });

        static::updating(function ($setting) {
            $setting->modified_by = auth()->id();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('company_logo')
            ->singleFile()
            ->useFallbackUrl('/assets/img/default/404.png');

        $this->addMediaCollection('company_favicon')
            ->singleFile()
            ->useFallbackUrl('/assets/img/default/404.png');

        $this->addMediaCollection('about_us_image')
            ->singleFile()
            ->useFallbackUrl('/assets/img/default/404.png');

    }

    public function registerMediaConversions(?Media $media = null): void
    {

        $this->addMediaConversion('thumb')
            ->keepOriginalImageFormat()
            ->fit(Fit::Crop, 160, 160)
            ->performOnCollections('company_logo')
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->keepOriginalImageFormat()
            ->fit(Fit::Crop, 320, 320)
            ->performOnCollections('company_logo')
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->keepOriginalImageFormat()
            ->fit(Fit::Crop, 160, 160)
            ->performOnCollections('company_favicon')
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->keepOriginalImageFormat()
            ->fit(Fit::Crop, 850, 420)
            ->performOnCollections('about_us_image')
            ->nonQueued();
    }

    public function getCompanyLogoPreviewUrlAttribute()
    {
        return $this->getMedia('company_logo') ? $this->getFirstMediaUrl('company_logo', 'preview') : null;
    }

    public function getFaviconPreviewUrlAttribute()
    {
        return $this->getMedia('company_favicon') ? $this->getFirstMediaUrl('company_favicon', 'preview') : null;
    }

    public function getAboutUsPreviewUrlAttribute()
    {
        return $this->getMedia('about_us_image') ? $this->getFirstMediaUrl('about_us_image', 'preview') : null;
    }
}
