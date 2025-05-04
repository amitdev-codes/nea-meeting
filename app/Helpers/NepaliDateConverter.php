<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NepaliDateConverter
{
    // Nepali month names
    public static $nepaliMonths = [
        1 => 'बैशाख', // Baishakh
        2 => 'जेठ',   // Jestha
        3 => 'असार',  // Asar
        4 => 'साउन',  // Shrawan
        5 => 'भदौ',   // Bhadra
        6 => 'असोज',  // Ashoj
        7 => 'कात्तिक', // Kartik
        8 => 'मंसिर',  // Mangsir
        9 => 'पुस',    // Poush
        10 => 'माघ',  // Magh
        11 => 'फागुन', // Falgun
        12 => 'चैत',  // Chaitra
    ];

    // English month names for Nepali months
    public static $englishNepaliMonths = [
        1 => 'Baishakh',
        2 => 'Jestha',
        3 => 'Asar',
        4 => 'Shrawan',
        5 => 'Bhadra',
        6 => 'Ashoj',
        7 => 'Kartik',
        8 => 'Mangsir',
        9 => 'Poush',
        10 => 'Magh',
        11 => 'Falgun',
        12 => 'Chaitra',
    ];

    // Nepali weekday names
    public static $nepaliWeekdays = [
        'Sunday' => 'आइतबार',    // Aaitabar
        'Monday' => 'सोमबार',    // Sombar
        'Tuesday' => 'मंगलबार',  // Mangalbar
        'Wednesday' => 'बुधबार', // Bud agricultur
        'Thursday' => 'बिहीबार', // Bihibar
        'Friday' => 'शुक्रबार',  // Shukrabar
        'Saturday' => 'शनिबार',  // Shanibar
    ];
    
    // Nepali digits
    public static $nepaliDigits = [
        '0' => '०', '1' => '१', '2' => '२', '3' => '३', '4' => '४',
        '5' => '५', '6' => '६', '7' => '७', '8' => '८', '9' => '९',
    ];
    
    /**
     * Convert numeric string to Nepali digits
     */
    public static function toNepaliDigits($number): string
    {
        return strtr((string)$number, self::$nepaliDigits);
    }
    
    /**
     * Get today's Nepali date and time
     */
    public static function getTodayNepaliDateTime(): array
    {
        $today = Carbon::now();
        $nepaliDate = self::toNepaliDate($today);
        
        // Create a more natural time string
        $suffix = $today->hour >= 18 || $today->hour < 6 ? 'बेलुका' : 'बिहान';
        $time = $today->format('h:i A') . ' ' . $suffix;
        
        // Format date
        $year = self::toNepaliDigits($nepaliDate['year']);
        $day = self::toNepaliDigits($nepaliDate['day']);
        
        return [
            'date' => sprintf('%s-%s-%s', $year, 
                self::toNepaliDigits(sprintf('%02d', $nepaliDate['month'])), 
                self::toNepaliDigits(sprintf('%02d', $nepaliDate['day']))),
            'formatted_date' => "{$year} {$nepaliDate['month_name']} {$day} गते",
            'month_name' => $nepaliDate['month_name'],
            'english_month_name' => $nepaliDate['english_month_name'], // Added English month name
            'weekday' => $nepaliDate['weekday'],
            'time' => $time,
            'full_date_time' => "{$year} {$nepaliDate['month_name']} {$day} गते, {$nepaliDate['weekday']}"
        ];
    }
    
    /**
     * Get today's Nepali date
     */
    public static function getTodayNepaliDate(): array
    {
        $today = Carbon::today();
        return self::toNepaliDate($today);
    }

    /**
     * Convert Gregorian (AD) date to Nepali (BS) date with month and weekday names
     */
    public static function toNepaliDate(Carbon $gregorianDate): array
    {
        // Find the correct record that contains the given date
        $record = DB::table('nepali_calendar')
            ->where(function ($query) use ($gregorianDate) {
                $start = $gregorianDate->copy()->startOfDay()->toDateString();
                $query->whereRaw('start_date <= ?', [$start])
                      ->whereRaw('DATE_ADD(start_date, INTERVAL days - 1 DAY) >= ?', [$start]);
            })
            ->first();
        
        if (!$record) {
            throw new \Exception('Date out of range (2070–2085 BS)');
        }
        
        // Calculate day within month
        $startDate = Carbon::parse($record->start_date)->startOfDay();
        $day = $startDate->diffInDays($gregorianDate->startOfDay()) + 1;
        
        return [
            'year' => $record->bs_year,
            'month' => $record->month,
            'month_name' => self::$nepaliMonths[$record->month],
            'english_month_name' => self::$englishNepaliMonths[$record->month], // Added English month name
            'day' => $day,
            'weekday' => self::$nepaliWeekdays[$gregorianDate->englishDayOfWeek],
        ];
    }
    /**
     * Convert Nepali (BS) date to Gregorian (AD) date with month and weekday names
     */
    public static function toGregorianDate(int $bsYear, int $bsMonth, int $bsDay): array
    {
        $record = DB::table('nepali_calendar')
            ->where('bs_year', $bsYear)
            ->where('month', $bsMonth)
            ->first();

        if (!$record || $bsDay <= 0 || $bsDay > $record->days) {
            throw new \Exception('Invalid Nepali date');
        }

        $startDate = Carbon::parse($record->start_date);
        $gregorianDate = $startDate->addDays($bsDay - 1);
        $weekday = $gregorianDate->englishDayOfWeek;

        return [
            'gregorian_date' => $gregorianDate->toDateString(),
            'year' => $record->bs_year,
            'month' => $record->month,
            'month_name' => self::$nepaliMonths[$record->month],
            'english_month_name' => self::$englishNepaliMonths[$record->month], // Added English month name
            'day' => $bsDay,
            'weekday' => self::$nepaliWeekdays[$weekday],
        ];
    }
    
    /**
     * Format a Nepali date for display
     */
    public static function formatNepaliDate(int $year, int $month, int $day): string
    {
        $monthName = self::$nepaliMonths[$month];
        $nepaliYear = self::toNepaliDigits($year);
        $nepaliDay = self::toNepaliDigits($day);
        
        return "{$nepaliYear} {$monthName} {$nepaliDay} गते";
    }

    /**
     * Convert Nepali date to English Gregorian date
     */
    public static function convertToEnglishDate($nepaliDate)
    {
        [$bsYear, $bsMonth, $bsDay] = explode('-', $nepaliDate);
        $data = self::toGregorianDate((int)$bsYear, (int)$bsMonth, (int)$bsDay);
        return $data['gregorian_date'];
    }

    /**
     * Convert Gregorian year to Nepali year
     */
    public static function gregorianToNepaliYear(int $gregorianYear): int
    {
        $gregorianDate = Carbon::create($gregorianYear, 1, 1)->startOfDay();
        $nepaliDate = self::toNepaliDate($gregorianDate);
        return $nepaliDate['year'];
    }

    /**
     * Convert Gregorian month to Nepali month (English name)
     */
    public static function gregorianToNepaliMonth(int $gregorianYear, int $gregorianMonth): string
    {
        $gregorianDate = Carbon::create($gregorianYear, $gregorianMonth, 1)->startOfDay();
        $nepaliDate = self::toNepaliDate($gregorianDate);
        return $nepaliDate['english_month_name']; // Return English month name
    }
}