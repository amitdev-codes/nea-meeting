<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function proxy(Request $request)
    {
        $imageUrl = $request->get('url');
        $contents = file_get_contents($imageUrl);
        $type = 'image/jpeg'; // or determine dynamically

        return response($contents)->header('Content-Type', $type);
    }
}
