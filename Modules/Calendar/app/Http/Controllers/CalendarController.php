<?php

namespace Modules\Calendar\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\NepaliDateConverter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Calendar\Services\NepaliCalendarService;

class CalendarController extends Controller
{

    protected $calendarService;
    
    /**
     * Constructor to inject the calendar service
     */
    public function __construct(NepaliCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }
    


    public function index(Request $request)
    {
        $user = Auth::user();
        // Get year and month from request if provided
        $year = $request->input('year');
        $month = $request->input('month');
        // Get all calendar data from the service
        $calendarData = $this->calendarService->getCompleteCalendarData($user, $year, $month);
        return view('calendar::pages.calendar.nepali-calendar', $calendarData);
    }
    
    public function getMonthData(Request $request)
    {
        $user = Auth::user();
        $year = $request->input('year');
        $month = $request->input('month');
        
        if (!$year || !$month) {
            return response()->json(['error' => 'Year and month are required'], 400);
        }
        
        $calendarData = $this->calendarService->getCompleteCalendarData($user, $year, $month);
        
        return response()->json($calendarData);
    }
    public function getMonths($year)
    {
        $months = DB::table('nepali_calendar')
            ->where('bs_year', $year)
            ->distinct()
            ->pluck('month');

        return response()->json($months);
    }
    public function getYears()
    {
        $years = DB::table('nepali_calendar')
            ->distinct()
            ->pluck('bs_year');

        return response()->json($years);
    }

    public function getDays($year, $month)
    {
        $days = DB::table('nepali_calendar')
            ->where('bs_year', $year)
            ->where('month', $month)
            ->value('days');

        return response()->json($days ?? 30);
    }

    public function getCalendarData($year, $month)
    {
        // dd('test');
        $calendarData = DB::table('nepali_calendar')
            ->where('bs_year', $year)
            ->where('month', $month)
            ->first();

        if (!$calendarData) {
            return response()->json(['error' => 'Calendar data not found'], 404);
        }

        $calendarData = (array) $calendarData;
    
        // Get meeting counts for each day in the month
        $daysInMonth = $calendarData['days'] ?? 30;
        $meetingCounts = [];
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Format the date as YYYY-MM-DD
            $nepaliDate = sprintf('%s-%s-%02d', $year, $month, $day);
            
            // Count meetings for this date
            $count = DB::table('meetings')
                ->where('meeting_date', $nepaliDate)
                ->count();
                // dd($count);
            
            $meetingCounts[$day] = $count;
        }
        
        // Add meeting counts to the calendar data
        $calendarData['meeting_counts'] = $meetingCounts;
        
        return response()->json($calendarData);
    }

    public function getCalendarGridPartial(Request $request)
    {
        $calendarData = $request->input('calendarData'); // Already an array from JSON
        return view('calendar::partials.calendar-grid', compact('calendarData'))->render();
    }
    public function getMeetingCounts($year, $month)
    {

        // Get all days in the month
        $daysInMonth = DB::table('nepali_calendar')
            ->where('bs_year', $year)
            ->where('month', $month)
            ->value('days') ?? 30;
        
        $meetingCounts = [];
        // For each day, get meeting count
        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Format the date as YYYY-MM-DD
            $nepaliDate = sprintf('%s-%s-%02d', $year, $month, $day);
            // Count meetings for this date
            $count = DB::table('meetings')
                ->where('nepali_date', $nepaliDate)
                ->count();
            $meetingCounts[$day] = $count;
        }
        
        return response()->json($meetingCounts);
    }
}