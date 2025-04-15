<?php

namespace Modules\Landingpage\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\SuccessStories\Models\SuccessStory;

class SuccessStoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = SuccessStory::latest()
            ->with('media') // Eager load media
            ->paginate(10);
        $currentMenu = (object) ['name' => 'success-stories', 'url' => 'success-stories'];
        return view('landingpage::pages.landingPage', compact('stories', 'currentMenu'));
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $story = SuccessStory::where('story_id', $id)
            ->where('is_verified', true)
            ->with('media') // Eager load media
            ->firstOrFail();
        $currentMenu = (object) ['name' => 'success-stories', 'url' => 'success-stories'];
        return view('landingpage::pages.successStories.show', compact('story', 'currentMenu'));
    }

}
