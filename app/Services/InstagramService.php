<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InstagramService
{
    protected $accessToken;

    public function __construct()
    {
        // $this->accessToken = env('INSTAGRAM_ACCESS_TOKEN');
        $this->accessToken = 'AUVcXRvNW8m0IW72oCfhkiOoGAo';
    }

    // Function to fetch Instagram feed
    public function fetchInstagramFeed()
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
