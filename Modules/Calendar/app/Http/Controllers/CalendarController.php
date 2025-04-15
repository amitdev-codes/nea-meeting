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
    
        $months = DB::table('nepali_calendar')
            ->where('bs_year', $currentBsYear)
            ->distinct()
            ->pluck('month');
    
        $days = $calendarData ? $calendarData['days'] : 30; // Use array syntax
        $todaysDate = NepaliDateConverter::getTodayNepaliDateTime();
    
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

        return response()->json((array) $calendarData); // Already an array
    }

    public function getCalendarGridPartial(Request $request)
    {
        $calendarData = $request->input('calendarData'); // Already an array from JSON
        return view('calendar::partials.calendar-grid', compact('calendarData'))->render();
    }
}