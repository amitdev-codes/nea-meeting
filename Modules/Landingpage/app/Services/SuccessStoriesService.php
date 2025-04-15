<?php

namespace Modules\Landingpage\Services;

use Illuminate\Support\Facades\Http;

class SuccessStoriesService
{


    public function __construct()
    {
        $this->accessToken = 'AUVcXRvNW8m0IW72oCfhkiOoGAo';
    }

    // Function to fetch Instagram feed
    public function fetchStories()
    {
        $response = Http::get('https://graph.instagram.com/me/media', [
            'fields' => 'id,caption,media_type,media_url,permalink',
            'access_token' => $this->accessToken
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
    public function viewStories()
    {
        $response = Http::get('https://graph.instagram.com/me/media', [
            'fields' => 'id,caption,media_type,media_url,permalink',
            'access_token' => $this->accessToken
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}