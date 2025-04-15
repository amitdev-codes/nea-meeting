<?php

use Illuminate\Support\Facades\Cache;
use App\Models\SiteSetting; // Update this to match your SiteSetting model location

if (!function_exists('getSiteSettings')) {
    function getSiteSettings()
    {
        return Cache::remember('site_settings', 60 * 60, function () {
            return SiteSetting::first();
        });
    }
}
