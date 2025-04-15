<?php
// app/Http/Controllers/PhotoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        foreach ($request->file('photos') as $photo) {
            $photo->store('photos');
        }

        return response()->json(['message' => 'Photos uploaded successfully'], 200);
    }
}
