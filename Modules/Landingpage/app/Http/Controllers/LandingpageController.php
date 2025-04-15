<?php

namespace Modules\Landingpage\Http\Controllers;

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
        $meetings=Meeting::paginate(10);
        return view('landingpage::pages.landingPage', compact('meetings'));
    }
    public function view($id){
        $meeting=Meeting::find($id);
        $meeting->load(['media' => function($query) {
            $query->where('collection_name', 'meetings');
        }]);
        return view('landingpage::pages.viewMeetings',['resource'=>$meeting]);
    }


}
