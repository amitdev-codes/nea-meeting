<?php

namespace Modules\Calendar\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\NepaliDateConverter;
use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        $years = DB::table('nepali_calendar')->distinct()->pluck('bs_year');
        $today = Carbon::now();
        $nepaliDate = NepaliDateConverter::toNepaliDate($today);
        $currentBsYear = $nepaliDate['year']; // e.g., 2081
        $currentBsMonth = $nepaliDate['month']; // e.g., 12 (Chaitra)
        $currentNepaliDay = $nepaliDate['day']; // e.g., 2
    
        $calendarData = DB::table('nepali_calendar')
            ->where('bs_year', $currentBsYear)
            ->where('month', $currentBsMonth)
            ->first();
        
        // Convert to array for consistency
        $calendarData = $calendarData ? (array) $calendarData : [];

        $calendarData = (array) $calendarData;

        // Get meeting counts for each day in the month
        $daysInMonth = $calendarData['days'] ?? 30;
        $meetingCounts = [];
        $today = Carbon::today()->toDateString(); // Get today's date in YYYY-MM-DD format
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Format the Nepali date
            $nepaliDate = sprintf('%s-%s-%02d', $years, $currentBsMonth, $day);
        
            // Convert Nepali date to English (Gregorian) date
            $englishDate = NepaliDateConverter::toGregorianDate($currentBsYear, $currentBsMonth, $day);
            $gregorianDate = $englishDate['gregorian_date'];
        
            // Query meetings for this English date, only for today or future dates
            $count = DB::table('meetings')
                ->whereDate('meeting_date_ad', $gregorianDate)
                ->whereDate('meeting_date_ad', '>=', $today) // Only count today or upcoming meetings
                ->count();
        
            $meetingCounts[$day] = $count;
        }
        
        // Add meeting counts to the calendar data
        $calendarData['meeting_counts'] = $meetingCounts;
        $months = DB::table('nepali_calendar')
            ->where('bs_year', $currentBsYear)
            ->distinct()
            ->pluck('month');
    
        $days = $calendarData ? $calendarData['days'] : 30; // Use array syntax
        $todaysDate = NepaliDateConverter::getTodayNepaliDateTime();

        // dd('test');
    
        return view('calendar::pages.calendar.nepali-calendar', compact(
            'years', 'todaysDate', 'months', 'days', 'currentBsYear', 'currentBsMonth', 'currentNepaliDay', 'calendarData'
        ));
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