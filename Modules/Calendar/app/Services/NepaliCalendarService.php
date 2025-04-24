<?php
namespace Modules\Calendar\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\NepaliDateConverter;

class NepaliCalendarService
{
    /**
     * Get all calendar years from the nepali_calendar table
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCalendarYears()
    {
        return DB::table('nepali_calendar')->distinct()->pluck('bs_year');
    }
    
    /**
     * Get all months for a specific Nepali year
     *
     * @param int $bsYear
     * @return \Illuminate\Support\Collection
     */
    public function getMonthsForYear(int $bsYear)
    {
        return DB::table('nepali_calendar')
            ->where('bs_year', $bsYear)
            ->distinct()
            ->pluck('month');
    }
    
    /**
     * Get current Nepali date information
     *
     * @return array
     */
    public function getCurrentNepaliDate()
    {
        $today = Carbon::now();
        return NepaliDateConverter::toNepaliDate($today);
    }
    
    /**
     * Get calendar data for a specific Nepali year and month
     *
     * @param int $bsYear
     * @param int $bsMonth
     * @return array
     */
    public function getCalendarData(int $bsYear, int $bsMonth)
    {
        $calendarData = DB::table('nepali_calendar')
            ->where('bs_year', $bsYear)
            ->where('month', $bsMonth)
            ->first();
            
        // Convert to array for consistency
        return $calendarData ? (array) $calendarData : [];
    }
    
    /**
     * Get meeting counts for each day in a Nepali month
     *
     * @param int $bsYear
     * @param int $bsMonth
     * @param int $daysInMonth
     * @param User $user
     * @return array
     */
    public function getMeetingCountsForMonth(int $bsYear, int $bsMonth, int $daysInMonth, User $user)
    {
        $meetingCounts = [];
        $today = Carbon::today()->toDateString(); // Get today's date in YYYY-MM-DD format
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Convert Nepali date to English (Gregorian) date
            $englishDate = NepaliDateConverter::toGregorianDate($bsYear, $bsMonth, $day);
            $gregorianDate = $englishDate['gregorian_date'];
            
            // Query meetings for this English date, only for today or future dates
            $count = DB::table('meetings')
                ->whereDate('meeting_date_ad', $gregorianDate)
                // ->whereDate('meeting_date_ad', '>=', $today)
                ->when(!$user->hasAnyRole(['admin', 'superadmin']), function ($query) use ($user) {
                    return $query->whereJsonContains('meetings.organizations', (string) $user->organization_id);
                })
                ->count();

                
            $meetingCounts[$day] = $count;
        }
        
        return $meetingCounts;
    }
    
    /**
     * Get complete calendar data with meeting counts
     *
     * @param User $user
     * @param int|null $bsYear
     * @param int|null $bsMonth
     * @return array
     */
    public function getCompleteCalendarData(User $user, ?int $bsYear = null, ?int $bsMonth = null)
    {
        // Get the current Nepali date
        $nepaliDate = $this->getCurrentNepaliDate();
        
        // Use provided values or defaults from current date
        $currentBsYear = $bsYear ?? $nepaliDate['year'];
        $currentBsMonth = $bsMonth ?? $nepaliDate['month'];
        $currentNepaliDay = $nepaliDate['day'];
        
        // Get calendar data for the specified month
        $calendarData = $this->getCalendarData($currentBsYear, $currentBsMonth);
        
        // Get days in month (default to 30 if not available)
        $daysInMonth = $calendarData['days'] ?? 30;
        
        // Get meeting counts for each day
        $meetingCounts = $this->getMeetingCountsForMonth($currentBsYear, $currentBsMonth, $daysInMonth, $user);
        
        // Add meeting counts to the calendar data
        $calendarData['meeting_counts'] = $meetingCounts;
        
        // Get all years and months for dropdowns
        $years = $this->getCalendarYears();
        $months = $this->getMonthsForYear($currentBsYear);
        
        // Get today's full Nepali date/time for display
        $todaysDate = NepaliDateConverter::getTodayNepaliDateTime();
        
        return [
            'years' => $years,
            'todaysDate' => $todaysDate,
            'months' => $months,
            'days' => $daysInMonth,
            'currentBsYear' => $currentBsYear,
            'currentBsMonth' => $currentBsMonth,
            'currentNepaliDay' => $currentNepaliDay,
            'calendarData' => $calendarData,
        ];
    }
}