<?php

namespace App\Http\Controllers;

use App\Helpers\NepaliDateConverter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DateConverterController extends Controller
{
    public function getToday(Request $request)
    {
        $today = NepaliDateConverter::getTodayNepaliDate();

        return response()->json([
            'gregorian' => Carbon::today()->toDateString(),
            'nepali' => [
                'date' => sprintf('%d-%02d-%02d', $today['year'], $today['month'], $today['day']),
                'month_name' => $today['month_name'],
                'weekday' => $today['weekday'],
            ],
        ]);
    }

    public function convertToNepali(Request $request)
    {
        $gregorianDate = Carbon::parse($request->input('date', now()));
        $nepaliDate = NepaliDateConverter::toNepaliDate($gregorianDate);

        return response()->json([
            'gregorian' => $gregorianDate->toDateString(),
            'nepali' => [
                'date' => sprintf('%d-%02d-%02d', $nepaliDate['year'], $nepaliDate['month'], $nepaliDate['day']),
                'month_name' => $nepaliDate['month_name'],
                'weekday' => $nepaliDate['weekday'],
            ],
        ]);
    }

    public function convertToGregorian(Request $request)
    {
        $bsYear = $request->input('year');
        $bsMonth = $request->input('month');
        $bsDay = $request->input('day');
        $result = NepaliDateConverter::toGregorianDate($bsYear, $bsMonth, $bsDay);

        return response()->json([
            'nepali' => [
                'date' => sprintf('%d-%02d-%02d', $bsYear, $bsMonth, $bsDay),
                'month_name' => $result['month_name'],
                'weekday' => $result['weekday'],
            ],
            'gregorian' => $result['gregorian_date'],
        ]);
    }
}