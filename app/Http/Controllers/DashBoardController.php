<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Modules\Groups\Models\Group;
use App\Models\CumulativeProgress;
use App\Services\DashBoardService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Helpers\NepaliDateConverter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Modules\Groups\Models\GroupMember;
use Modules\NeaMeeting\Models\Meeting;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Modules\Calendar\Services\NepaliCalendarService;


class DashBoardController extends Controller
{
    protected $dashboardService;
    protected $calendarService;

    public function __construct(DashBoardService $dashboardService,NepaliCalendarService $calendarService,)
    {
        $this->dashboardService = $dashboardService;
        $this->calendarService = $calendarService;
    }
    public function welcome(Request $request): View
    {
        return view('welcome');
    }

    public function locale(Request $request): RedirectResponse
    {
        $locale = $request->query('locale');
        if (in_array($locale, array_keys(config('app.available_locales')))) {
            $request->user()->update(['locale' => $locale]);
            session(['locale' => $locale]);
            App::setLocale($locale);
        } else {
            return back()->with('notification', ['icon' => 'error', 'title' => __('menu.locale'), 'message' => __('notification.locale_not_available')]);
        }
        return back()->with('notification', ['icon' => 'success', 'title' => __('menu.locale'), 'message' => __('notification.locale_success')]);
    }
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now();
        $calendarData = $this->calendarService->getCompleteCalendarData($user);
        $dashboardData = $this->dashboardService->getDashboardData($user, $today);
        $dashboardData = array_merge([
            'todaysMeetings' => 0,
            'yesterdaysMeetings' => 0,
            'upcomingMeetings' => null,
            'thisMonthMeetings' => 0,
            'totalMeetings' => 0,
            'comingMeetings' => 0,
            'meetingsPerMonthLabels' => [],
            'meetingsPerMonthData' => [],
            'statusLabels' => [],
            'statusData' => [],
            'userMeetingsPerDayLabels' => [],
            'userMeetingsPerDayData' => [],
        ], $dashboardData);

        if ($user->hasRole(['md','user','guest'])) {
            return view('pages.dashboard.user', [
                'calendarData' => $calendarData['calendarData'],
                'todaysDate' => $calendarData['todaysDate'],
                'years' => $calendarData['years'],
                'months' => $calendarData['months'],
                'days' => $calendarData['days'],
                'currentBsYear' => $calendarData['currentBsYear'],
                'currentBsMonth' => $calendarData['currentBsMonth'],
                'currentNepaliDay' => $calendarData['currentNepaliDay'],
                'dashboardData' => $dashboardData]);
        }
        return view('pages.dashboard.admin', ['calendarData' => $calendarData,'dashboardData' => $dashboardData]);
    }
       
}
