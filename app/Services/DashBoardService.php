<?php
namespace App\Services;
use Carbon\Carbon;
use App\Models\User;
use Modules\Groups\Models\Group;
use App\Models\CumulativeProgress;
use Illuminate\Support\Facades\DB;
use App\Helpers\NepaliDateConverter;
use Modules\Groups\Models\GroupMember;
use Modules\NeaMeeting\Models\Meeting;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class DashboardService
{
    public function getDashboardData(User $user, Carbon $today)
    {
        $data = [
            'todaysMeetings' => 0,
            'yesterdaysMeetings' => 0,
            'upcomingMeetings' => 0,
            'thisMonthMeetings' => 0,
            'totalMeetings' => 0,
            'meetingsPerMonthLabels' => [],
            'meetingsPerMonthData' => [],
            'statusLabels' => [],
            'statusData' => [],
            'userMeetingsPerDayLabels' => [],
            'userMeetingsPerDayData' => [],
        ];

        if ($user->hasRole(['admin', 'superadmin'])) {
            return $this->getAdminDashboardData($data, $today);
        } else {
            // Combined data for MD, user, and guest roles
            return $this->getOrganizationUserDashboardData($data, $user, $today);
        }
    }

    private function getAdminDashboardData(array $data, Carbon $today): array
    {
        // Basic metrics
        $data['todaysMeetings'] = $this->getTodaysMeetingsCount(null, $today);
        $data['yesterdaysMeetings'] = $this->getYesterdaysMeetingsCount(null, $today);
        $data['upcomingMeetings'] = $this->getUpcomingMeetingsCount(null, $today);
        $data['thisMonthMeetings'] = $this->getThisMonthMeetingsCount(null, $today);
        $data['totalMeetings'] = $this->getTotalMeetingsCount();
        
        // Meetings per Nepali month
        $nepaliMonthsData = $this->getMeetingsPerNepaliMonth($today);
        $data['meetingsPerMonthLabels'] = array_column($nepaliMonthsData, 'label');
        $data['meetingsPerMonthData'] = array_column($nepaliMonthsData, 'count');
        
        // Meeting status distribution
        $statusCounts = $this->getMeetingStatusDistribution();
        $data['statusLabels'] = array_keys($statusCounts);
        $data['statusData'] = array_values($statusCounts);
        
        return $data;
    }

    private function getOrganizationUserDashboardData(array $data, User $user, Carbon $today): array
    {
        $organizationId = $user->organization_id;

        // Basic metrics
        $data['todaysMeetings'] = $this->getTodaysMeetingsCount($organizationId, $today);
        $data['upcomingMeetings'] = $this->getUpcomingMeetingsCount($organizationId, $today);
        
        // Include comingMeetings for MD role to maintain backward compatibility
        if ($user->hasRole('md')) {
            $data['comingMeetings'] = $data['upcomingMeetings'];
        }
        
        $data['thisMonthMeetings'] = $this->getThisMonthMeetingsCount($organizationId, $today);
        $data['totalMeetings'] = $this->getTotalMeetingsCount($organizationId);
        
        // Meetings by date
        $userMeetingsPerDate = $this->getMeetingsByDate($organizationId);
        $data['userMeetingsPerDayLabels'] = array_column($userMeetingsPerDate, 'label');
        $data['userMeetingsPerDayData'] = array_column($userMeetingsPerDate, 'count');
        
        // Meeting status distribution
        $statusCounts = $this->getMeetingStatusDistribution($organizationId);
        $data['statusLabels'] = array_keys($statusCounts);
        $data['statusData'] = array_values($statusCounts);
        
        // Add paginated upcoming meetings for all non-admin users
        $todayDate = Carbon::today()->toDateString();
        $query = Meeting::where('meeting_date_ad', '>=', $todayDate)
            ->whereJsonContains('meetings.organizations', (string) $organizationId)
            ->orderBy('meeting_date_ad', 'asc')
            ->orderBy('start_time', 'asc');
            
        $data['upcomingMeetings'] = $query->paginate(10);
            
        return $data;
    }

    private function getTodaysMeetingsCount(?int $organizationId, Carbon $today): int
    {
        $query = Meeting::whereDate('meeting_date_ad', $today);
        
        if ($organizationId) {
            $query->whereJsonContains('meetings.organizations', (string) $organizationId);
        }
        
        return $query->count();
    }
    private function getYesterdaysMeetingsCount(?int $organizationId, Carbon $today): int
    {
        $yesterday = $today->copy()->subDay();
        $query = Meeting::whereDate('meeting_date_ad', $yesterday);
        
        if ($organizationId) {
            $query->whereJsonContains('meetings.organizations', (string) $organizationId);
        }
        
        return $query->count();
    }

    private function getUpcomingMeetingsCount(?int $organizationId, Carbon $today): int
    {
        $query = Meeting::where('meeting_date_ad', '>', $today);
        
        if ($organizationId) {
            $query->whereJsonContains('meetings.organizations', (string) $organizationId);
        }
        
        return $query->count();
    }

    private function getThisMonthMeetingsCount(?int $organizationId, Carbon $today): int
    {
        $query = Meeting::whereYear('meeting_date_ad', $today->year)
            ->whereMonth('meeting_date_ad', $today->month);
        
        if ($organizationId) {
            $query->whereJsonContains('meetings.organizations', (string) $organizationId);
        }
        
        return $query->count();
    }

    private function getTotalMeetingsCount(?int $organizationId = null): int
    {
        $query = Meeting::query();
        
        if ($organizationId) {
            $query->whereJsonContains('meetings.organizations', (string) $organizationId);
        }
        
        return $query->count();
    }
    private function getMeetingsPerNepaliMonth(Carbon $today, ?int $organizationId = null, string $role = 'user'): array
    {
        $nepaliMonthsData = [];
        $currentNepaliDate = NepaliDateConverter::toNepaliDate($today);
        $currentNepaliYear = $currentNepaliDate['year'];
        $currentNepaliMonth = $currentNepaliDate['month'];

        // Determine the fiscal year start and end
        // Nepali fiscal year starts from Shrawan (month 4) and ends at Ashadh (month 3) next year
        $fiscalYearStart = $currentNepaliMonth >= 4 ? $currentNepaliYear : $currentNepaliYear - 1;
        $fiscalYearEnd = $fiscalYearStart + 1;

        // Iterate through all 12 months of the fiscal year, starting from Shrawan
        for ($monthOffset = 4; $monthOffset <= 15; $monthOffset++) {
            $nepaliMonth = $monthOffset > 12 ? $monthOffset - 12 : $monthOffset;
            $year = $monthOffset > 12 ? $fiscalYearEnd : $fiscalYearStart;

            $calendarRecord = DB::table('nepali_calendar')
                ->where('bs_year', $year)
                ->where('month', $nepaliMonth)
                ->first();

            if (!$calendarRecord) {
                continue; // Skip if no calendar record exists
            }

            $daysInMonth = $calendarRecord->days;
            $startGregorian = NepaliDateConverter::toGregorianDate($year, $nepaliMonth, 1)['gregorian_date'];
            $endGregorian = NepaliDateConverter::toGregorianDate($year, $nepaliMonth, $daysInMonth)['gregorian_date'];

            $endNepali = NepaliDateConverter::toNepaliDate(Carbon::parse($endGregorian));
            if ($endNepali['month'] != $nepaliMonth) {
                $endGregorian = Carbon::parse($endGregorian)->subDay()->toDateString();
            }

            $query = Meeting::whereBetween('meeting_date_ad', [$startGregorian, $endGregorian]);

            // Apply organization filter for non-admin roles
            if ($organizationId && !in_array($role, ['superadmin', 'centraladmin'])) {
                $query->whereJsonContains('meetings.organizations', (string) $organizationId);
            }

            $count = $query->count();

            // Only include months with meetings
            if ($count > 0) {
                $nepaliMonthsData[] = [
                    'label' => NepaliDateConverter::$nepaliMonths[$nepaliMonth],
                    'count' => $count,
                ];
            }
        }

        return $nepaliMonthsData;
    }

    // private function getMeetingsPerNepaliMonth(Carbon $today): array
    // {
    //     $nepaliMonthsData = [];
    //     $currentNepaliDate = NepaliDateConverter::toNepaliDate($today);
    //     $currentNepaliYear = $currentNepaliDate['year'];
    //     $currentNepaliMonth = $currentNepaliDate['month'];
        
    //     for ($i = 5; $i >= 0; $i--) {
    //         $monthOffset = $currentNepaliMonth - $i;
    //         $year = $currentNepaliYear;
            
    //         if ($monthOffset <= 0) {
    //             $monthOffset += 12;
    //             $year--;
    //         }
            
    //         $calendarRecord = DB::table('nepali_calendar')
    //             ->where('bs_year', $year)
    //             ->where('month', $monthOffset)
    //             ->first();
            
    //         if (!$calendarRecord) {
    //             $nepaliMonthsData[] = [
    //                 'label' => NepaliDateConverter::$nepaliMonths[$monthOffset],
    //                 'count' => 0,
    //             ];
    //             continue;
    //         }
            
    //         $daysInMonth = $calendarRecord->days;
    //         $startGregorian = NepaliDateConverter::toGregorianDate($year, $monthOffset, 1)['gregorian_date'];
    //         $endGregorian = NepaliDateConverter::toGregorianDate($year, $monthOffset, $daysInMonth)['gregorian_date'];

              

            
    //         $endNepali = NepaliDateConverter::toNepaliDate(Carbon::parse($endGregorian));
    //         if ($endNepali['month'] != $monthOffset) {
    //             $endGregorian = Carbon::parse($endGregorian)->subDay()->toDateString();
    //         }
            
    //         $count = Meeting::whereBetween('meeting_date_ad', [$startGregorian, $endGregorian])->count();
    //         $nepaliMonthsData[] = [
    //             'label' => NepaliDateConverter::$nepaliMonths[$monthOffset],
    //             'count' => $count,
    //         ];
    //     }
        
    //     return $nepaliMonthsData;
    // }

    // private function getMeetingStatusDistribution(?int $organizationId = null): array
    // {
    //     $query = Meeting::select('status')->groupBy('status');
        
    //     if ($organizationId) {
    //         $query->whereJsonContains('meetings.organizations', (string) $organizationId);
    //     }
        
    //     return $query->pluck('status')
    //         ->mapWithKeys(function ($status) use ($organizationId) {
    //             $countQuery = Meeting::where('status', $status);
                
    //             if ($organizationId) {
    //                 $countQuery->whereJsonContains('meetings.organizations', (string) $organizationId);
    //             }

    //             // dd($countQuery);
                
    //             return [$status => $countQuery->count()];
    //         })->toArray();
    // }
    private function getMeetingStatusDistribution(?int $organizationId = null, string $role = 'user'): array
    {
        $query = Meeting::select('status')->groupBy('status');

        // Apply organization filter for non-admin roles
        if ($organizationId && !in_array($role, ['superadmin', 'centraladmin'])) {
            $query->whereJsonContains('meetings.organizations', (string) $organizationId);
        }

        return $query->pluck('status')
            ->mapWithKeys(function ($status) use ($organizationId, $role) {
                $countQuery = Meeting::where('status', $status);

                // Apply organization filter for non-admin roles
                if ($organizationId && !in_array($role, ['superadmin', 'centraladmin'])) {
                    $countQuery->whereJsonContains('meetings.organizations', (string) $organizationId);
                }

                return [$status => $countQuery->count()];
            })->toArray();
    }

    private function getMeetingsByDate(int $organizationId): array
    {
        return Meeting::whereJsonContains('meetings.organizations', (string) $organizationId)
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
    }
}