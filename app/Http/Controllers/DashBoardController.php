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


class DashBoardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashBoardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    public function welcome(Request $request): View
    {
        return view('welcome');
    }


    public function dashboard()
    {
        $today = Carbon::now();
        $user = Auth::user();
    
        // Initialize variables
        $todaysMeetings = 0;
        $upcomingMeetings = 0;
        $thisMonthMeetings = 0;
        $totalMeetings = 0;
        $meetingsPerMonthLabels = [];
        $meetingsPerMonthData = [];
        $statusLabels = [];
        $statusData = [];
        $userMeetingsPerDayLabels = [];
        $userMeetingsPerDayData = [];
        $todayDate = Carbon::today()->toDateString();
    
        if ($user->hasRole(['admin', 'superadmin'])) {
            // Total Counts for Admin/Superadmin
            $todaysMeetings = Meeting::whereDate('meeting_date_ad', $today)->count();
            $upcomingMeetings = Meeting::where('meeting_date_ad', '>', $today)->count();
            $thisMonthMeetings = Meeting::whereYear('meeting_date_ad', $today->year)
                                       ->whereMonth('meeting_date_ad', $today->month)
                                       ->count();
            $totalMeetings = Meeting::count();
    
            // Meetings Per Nepali Month (last 6 months)
            $nepaliMonthsData = [];
            $currentNepaliDate = NepaliDateConverter::toNepaliDate($today);
            $currentNepaliYear = $currentNepaliDate['year'];
            $currentNepaliMonth = $currentNepaliDate['month'];
    
            for ($i = 5; $i >= 0; $i--) {
                $monthOffset = $currentNepaliMonth - $i;
                $year = $currentNepaliYear;
                if ($monthOffset <= 0) {
                    $monthOffset += 12;
                    $year--;
                }
    
                $calendarRecord = DB::table('nepali_calendar')
                    ->where('bs_year', $year)
                    ->where('month', $monthOffset)
                    ->first();
    
                if (!$calendarRecord) {
                    $nepaliMonthsData[] = [
                        'label' => NepaliDateConverter::$nepaliMonths[$monthOffset],
                        'count' => 0,
                    ];
                    continue;
                }
    
                $daysInMonth = $calendarRecord->days;
                $startGregorian = NepaliDateConverter::toGregorianDate($year, $monthOffset, 1)['gregorian_date'];
                $endGregorian = NepaliDateConverter::toGregorianDate($year, $monthOffset, $daysInMonth)['gregorian_date'];
    
                $endNepali = NepaliDateConverter::toNepaliDate(Carbon::parse($endGregorian));
                if ($endNepali['month'] != $monthOffset) {
                    $endGregorian = Carbon::parse($endGregorian)->subDay()->toDateString();
                }
    
                $count = Meeting::whereBetween('meeting_date_ad', [$startGregorian, $endGregorian])->count();
                $nepaliMonthsData[] = [
                    'label' => NepaliDateConverter::$nepaliMonths[$monthOffset],
                    'count' => $count,
                ];
            }
    
            $meetingsPerMonthLabels = array_column($nepaliMonthsData, 'label');
            $meetingsPerMonthData = array_column($nepaliMonthsData, 'count');
    
            // Meeting Status Distribution for Admin/Superadmin
            $statusCounts = Meeting::select('status')
                ->groupBy('status')
                ->pluck('status')
                ->mapWithKeys(function ($status) {
                    return [$status => Meeting::where('status', $status)->count()];
                })->toArray();
    
            $statusLabels = array_keys($statusCounts);
            $statusData = array_values($statusCounts);
        }elseif ($user->hasRole('md')) {
            // Total Counts for User (based on organization)
            $todaysMeetings = Meeting::whereDate('meeting_date_ad', $today)
                                    ->whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                    ->count();
            $comingMeetings = Meeting::where('meeting_date_ad', '>', $today)
                                      ->whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                      ->count();
            $thisMonthMeetings = Meeting::whereYear('meeting_date_ad', $today->year)
                                       ->whereMonth('meeting_date_ad', $today->month)
                                       ->whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                       ->count();
            $totalMeetings = Meeting::whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                    ->count();

                                    // dd($upcomingMeetings);

            // Meetings by Date for User’s Organization (in Nepali format)
            $userMeetingsPerDate = Meeting::whereJsonContains('meetings.organizations', (string) $user->organization_id)
                ->selectRaw('meeting_date_ad, COUNT(*) as meeting_count')
                ->groupBy('meeting_date_ad')
                ->orderBy('meeting_date_ad', 'asc')
                ->get()
                ->map(function ($item) {
                    $nepaliDate = NepaliDateConverter::toNepaliDate(Carbon::parse($item->meeting_date_ad));
                    return [
                        'label' => sprintf(
                            '%s %s',
                            $nepaliDate['month_name'],
                            NepaliDateConverter::toNepaliDigits($nepaliDate['day'])
                        ),
                        'count' => $item->meeting_count,
                    ];
                })->toArray();
    
            $userMeetingsPerDayLabels = array_column($userMeetingsPerDate, 'label');
            $userMeetingsPerDayData = array_column($userMeetingsPerDate, 'count');
    
            // Meeting Status Distribution for User’s Organization
            $statusCounts = Meeting::whereJsonContains('meetings.organizations', (string) $user->organization_id)
                ->select('status')
                ->groupBy('status')
                ->pluck('status')
                ->mapWithKeys(function ($status) use ($user) {
                    return [$status => Meeting::where('status', $status)
                                            ->whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                            ->count()];
                })->toArray();
    
            $statusLabels = array_keys($statusCounts);
            $statusData = array_values($statusCounts);
                $upcomingMeetings = Meeting::where('meeting_date_ad', '>=', $todayDate)->orderBy('meeting_date_ad', 'asc')->orderBy('start_time', 'asc')->paginate(10);
                return view('partials.meetings', compact(
                'todaysMeetings',
                'upcomingMeetings',
                'comingMeetings',
                'thisMonthMeetings',
                'totalMeetings',
                'meetingsPerMonthLabels',
                'meetingsPerMonthData',
                'statusLabels',
                'statusData',
                'userMeetingsPerDayLabels',
                'userMeetingsPerDayData'));
        }
         elseif ($user->hasRole(['user', 'guest'])) {
            // Total Counts for User (based on organization)
            $todaysMeetings = Meeting::whereDate('meeting_date_ad', $today)
                                    ->whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                    ->count();
            $upcomingMeetings = Meeting::where('meeting_date_ad', '>', $today)
                                      ->whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                      ->count();
            $thisMonthMeetings = Meeting::whereYear('meeting_date_ad', $today->year)
                                       ->whereMonth('meeting_date_ad', $today->month)
                                       ->whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                       ->count();
            $totalMeetings = Meeting::whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                    ->count();
    
            // Meetings by Date for User’s Organization (in Nepali format)
            $userMeetingsPerDate = Meeting::whereJsonContains('meetings.organizations', (string) $user->organization_id)
                ->selectRaw('meeting_date_ad, COUNT(*) as meeting_count')
                ->groupBy('meeting_date_ad')
                ->orderBy('meeting_date_ad', 'asc')
                ->get()
                ->map(function ($item) {
                    $nepaliDate = NepaliDateConverter::toNepaliDate(Carbon::parse($item->meeting_date_ad));
                    return [
                        'label' => sprintf(
                            '%s %s',
                            $nepaliDate['month_name'],
                            NepaliDateConverter::toNepaliDigits($nepaliDate['day'])
                        ),
                        'count' => $item->meeting_count,
                    ];
                })->toArray();
    
            $userMeetingsPerDayLabels = array_column($userMeetingsPerDate, 'label');
            $userMeetingsPerDayData = array_column($userMeetingsPerDate, 'count');
    
            // Meeting Status Distribution for User’s Organization
            $statusCounts = Meeting::whereJsonContains('meetings.organizations', (string) $user->organization_id)
                ->select('status')
                ->groupBy('status')
                ->pluck('status')
                ->mapWithKeys(function ($status) use ($user) {
                    return [$status => Meeting::where('status', $status)
                                            ->whereJsonContains('meetings.organizations', (string) $user->organization_id)
                                            ->count()];
                })->toArray();
    
            $statusLabels = array_keys($statusCounts);
            $statusData = array_values($statusCounts);
        }
    
        return view('pages.dashboard', compact(
            'todaysMeetings',
            'upcomingMeetings',
            'thisMonthMeetings',
            'totalMeetings',
            'meetingsPerMonthLabels',
            'meetingsPerMonthData',
            'statusLabels',
            'statusData',
            'userMeetingsPerDayLabels',
            'userMeetingsPerDayData',
            
        ));
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

        
}
