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

        // Total Counts
        $todaysMeetings = Meeting::whereDate('meeting_date_ad', $today)->count();
        $upcomingMeetings = Meeting::where('meeting_date_ad', '>', $today)->count();
        $thisMonthMeetings = Meeting::whereYear('meeting_date_ad', $today->year)
                                   ->whereMonth('meeting_date_ad', $today->month)
                                   ->count();
        $totalMeetings = Meeting::count();

        // Meetings Per Nepali Month (last 6 months)
        // dd($today);
        $nepaliMonthsData = [];
        $currentNepaliDate = NepaliDateConverter::toNepaliDate($today);
        $currentNepaliYear = $currentNepaliDate['year'];
        $currentNepaliMonth = $currentNepaliDate['month'];
        // dd( $currentNepaliYear,     $currentNepaliMonth);

                // Collect meetings for the last 6 Nepali months
                for ($i = 5; $i >= 0; $i--) {
                    $monthOffset = $currentNepaliMonth - $i;
                    $year = $currentNepaliYear;
                    if ($monthOffset <= 0) {
                        $monthOffset += 12;
                        $year--;
                    }

                    // dd($year,$monthOffset,1);

                // Get the number of days in the Nepali month
                $calendarRecord = DB::table('nepali_calendar')
                ->where('bs_year', $year)
                ->where('month', $monthOffset)
                ->first();

            if (!$calendarRecord) {
                // Fallback if no calendar data
                $nepaliMonthsData[] = [
                    'label' => NepaliDateConverter::$nepaliMonths[$monthOffset],
                    'count' => 0,
                ];
                continue;
            }

            $daysInMonth = $calendarRecord->days;

            // Get the Gregorian date range for the Nepali month
            $startGregorian = NepaliDateConverter::toGregorianDate($year, $monthOffset, 1)['gregorian_date'];
            $endGregorian = NepaliDateConverter::toGregorianDate($year, $monthOffset, $daysInMonth)['gregorian_date'];


            // dd($startGregorian,$endGregorian);

            // Ensure the end date is within the Nepali month
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

        // Meeting Status Distribution
        $statusCounts = Meeting::select('status')
            ->groupBy('status')
            ->pluck('status')
            ->mapWithKeys(function ($status) {
                return [$status => Meeting::where('status', $status)->count()];
            })->toArray();

        $statusLabels = array_keys($statusCounts);
        $statusData = array_values($statusCounts);

        return view('pages.dashboard', compact('todaysMeetings','upcomingMeetings','thisMonthMeetings','totalMeetings','meetingsPerMonthLabels','meetingsPerMonthData','statusLabels','statusData'));
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
