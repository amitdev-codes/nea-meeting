<?php

namespace Modules\Landingpage\Http\Controllers;

use Carbon\Carbon;
use App\Models\Slider;
use App\Models\Contact;
use App\Mail\ContactMail;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\DashBoardService;
use App\Helpers\NepaliDateConverter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Validator;
use Modules\NeaMeeting\Models\Meeting;
use Modules\Landingpage\Models\LandingPageMenu;
use Modules\SuccessStories\Models\SuccessStory;
use Modules\NeaMeeting\DataTables\MeetingDataTable;

class LandingpageController extends Controller
{

    public function index()
    {
        $today = Carbon::today()->toDateString();// Get today's date
        $upcomingMeetings = Meeting::where('meeting_date_ad', '>=', $today)->orderBy('meeting_date_ad', 'asc')->orderBy('start_time', 'asc')->paginate(10);
        $pastMeetings = Meeting::where('meeting_date_ad', '<', $today)->orderBy('meeting_date_ad', 'desc')->orderBy('start_time', 'desc')->paginate(10);
        return view('landingpage::pages.landingPage', compact('upcomingMeetings', 'pastMeetings'));
    }
    public function view($id, Request $request)
    {
        // dd($id);
        $meeting = Meeting::with('meetingRoom', 'media')->findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                'title' => $meeting->title,
                'meeting_location' => $meeting->meeting_location,
                'is_virtual' => $meeting->is_virtual,
                'meeting_date' => $meeting->meeting_date,
                'meeting_date_ad' => $meeting->meeting_date_ad,
                'start_time' => \Carbon\Carbon::parse($meeting->start_time)->format('h:i A'),
                'end_time' => \Carbon\Carbon::parse($meeting->end_time)->format('h:i A'),
                'meeting_rooms' => $meeting->meeting_rooms,
                'meeting_type' => $meeting->meeting_type,
                'is_external' => $meeting->is_external ? __('field.yes') : __('field.no'),
                'is_virtual_meeting' => $meeting->is_virtual_meeting ? __('field.yes') : __('field.no'),
                'virtual_meeting_link' => $meeting->virtual_meeting_link,
                'status' => $meeting->status,
                'media' => $meeting->media->map(function ($media) {
                    return [
                        'name' => $media->name,
                        'file_name' => $media->file_name,
                        'url' => $media->getUrl(),
                        'mime_type' => $media->mime_type,
                    ];
                }),
            ]);
        }

        // Fallback for non-AJAX requests
        return view('landingpage::pages.viewMeetings',['resource'=>$meeting]);
    }
}
