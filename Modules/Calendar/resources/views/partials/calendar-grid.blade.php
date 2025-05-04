<?php use App\Helpers\NepaliDateConverter; ?>

@if (isset($calendarData))
    <div class="calendar">
        <div class="calendar-header">
            <div class="row g-0">
                @foreach ([['nepali' => 'आइत', 'english' => 'Sun'], ['nepali' => 'सोम', 'english' => 'Mon'], ['nepali' => 'मंगल', 'english' => 'Tue'], ['nepali' => 'बुध', 'english' => 'Wed'], ['nepali' => 'बिही', 'english' => 'Thu'], ['nepali' => 'शुक्र', 'english' => 'Fri'], ['nepali' => 'शनि', 'english' => 'Sat']] as $index => $day)
                    <div class="col weekday {{ $index == 0 ? 'sunday' : '' }} {{ $index == 6 ? 'saturday' : '' }}">
                        <div class="nepali-weekday">{{ $day['nepali'] }}</div>
                        <div class="english-weekday">{{ $day['english'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="calendar-body">
            <div class="row g-0">
                @php
                    $daysInMonth = $calendarData['days'] ?? 30;
                    $startDate = Carbon\Carbon::parse($calendarData['start_date']);
                    $startDay = $startDate->dayOfWeek; // 0 (Sun) to 6 (Sat)
                    $today = Carbon\Carbon::now();
                    $currentDay = $today->day;
                    $currentMonth = $today->month;
                    $currentYear = $today->year;
                    $isCurrentMonth = $startDate->month == $currentMonth && $startDate->year == $currentYear;

                    $totalDaysDisplayed = $daysInMonth + $startDay;
                    $totalWeeks = ceil($totalDaysDisplayed / 7);

                    // Get Nepali year and month from calendarData
                    $nepaliYear =
                        $calendarData['bs_year'] ?? NepaliDateConverter::gregorianToNepaliYear($startDate->year);
                    $nepaliMonth =
                        $calendarData['month'] ?? NepaliDateConverter::gregorianToNepaliMonth($startDate->month);

                    for ($week = 0; $week < $totalWeeks; $week++) {
                        for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
                            $currentPosition = $week * 7 + $dayOfWeek;
                            $isSaturday = $dayOfWeek == 7;
                            $isSunday = $dayOfWeek == 1;

                            if ($currentPosition <= $startDay) {
                                echo '<div class="col calendar-day other-month ' .
                                    ($isSaturday ? 'saturday' : '') .
                                    ($isSunday ? 'sunday' : '') .
                                    '"></div>';
                            } elseif ($currentPosition <= $totalDaysDisplayed) {
                                $day = $currentPosition - $startDay;
                                $nepaliDay = NepaliDateConverter::toNepaliDigits($day);

                                $isToday = $startDate
                                    ->copy()
                                    ->addDays($day - 1)
                                    ->isToday();
                                // @dd($isToday);
                                $englishDate = isset($calendarData['english_dates'][$day])
                                    ? $calendarData['english_dates'][$day]
                                    : '';
                                $events = isset($calendarData['events'][$day]) ? $calendarData['events'][$day] : [];
                                $adDay = $startDate->copy()->addDays($day - 1)->day;
                                $eventCount = count($events); // Calculate the number of events
                                $meetingCount = isset($calendarData['meeting_counts'][$day])
                                    ? $calendarData['meeting_counts'][$day]
                                    : 0;

                                // @dd($calendarData);

                                // Format Nepali date as YYYY-MM-DD
                                $nepaliDate = sprintf('%s-%s-%02d', $nepaliYear, $nepaliMonth, $day);

                                // Add data-nepali-date attribute
                                echo '<div class="col calendar-day ' .
                                    ($isToday ? 'today ' : '') .
                                    ($isSunday ? 'sunday ' : '') .
                                    ($isSaturday ? 'saturday ' : '') .
                                    '" data-nepali-date="' .
                                    $nepaliDate .
                                    '">';
                                echo '<div class="day-content">';
                                //  @dd($calendarData);
                                // Add meeting count in top-right corner
                                // Inside the calendar-day loop where meeting-count is rendered
                                if ($meetingCount > 0) {
                                    echo '<div class="meeting-count-wrapper">';
                                    echo '<div class="meeting-count" data-tooltip="' .
                                        $meetingCount .
                                        ' Upcoming Meeting' .
                                        ($meetingCount > 1 ? 's' : '') .
                                        '">' .
                                        $meetingCount .
                                        '</div>';
                                    echo '</div>';
                                }
                                echo '<div class="nepali-date">' . $nepaliDay . '</div>';
                                if (!empty($englishDate)) {
                                    echo '<div class="english-date">' . $englishDate . '</div>';
                                }
                                if (!empty($events)) {
                                    echo '<div class="events">';
                                    foreach ($events as $event) {
                                        echo '<span class="event">' . $event . '</span>';
                                    }
                                    echo '</div>';
                                }
                                echo '<div class="ad-date">' . $adDay . '</div>';
                                echo '</div>';
                                echo '</div>';
                            } else {
                                echo '<div class="col calendar-day other-month ' .
                                    ($isSaturday ? 'saturday' : '') .
                                    ($isSunday ? 'sunday' : '') .
                                    '"></div>';
                            }
                        }
                        if ($week < $totalWeeks - 1) {
                            echo '</div><div class="row g-0">';
                        }
                    }
                @endphp
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning m-3">
        Calendar data not available. Please select a valid year and month.
    </div>
@endif
@section('page-style')
    <style>
        .calendar {
            width: 100%;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background: #fff;
        }

        .calendar-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #e0e0e0;
            padding: 5px 0;
        }

        .calendar-header .weekday {
            padding: 12px 5px;
            text-align: center;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .weekday.sunday,
        .weekday.saturday {
            color: #dc3545;
        }

        .calendar-day {
            border: 1px solid #e9ecef;
            padding: 5px;
            position: relative;
            min-height: 100px;
            transition: all 0.2s ease;
        }

        .calendar-day:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .calendar-day.today {
            background-color: #e6f0ff;
            border: 2px solid #0d6efd;
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.3);
        }

        .calendar-day.sunday {
            background-color: rgba(220, 53, 69, 0.03);
        }

        .calendar-day.saturday {
            background-color: rgba(220, 53, 69, 0.03);
        }

        .calendar-day.other-month {
            background-color: #f8f9fa;
            color: #adb5bd;
        }

        /* Add styles for selected date */
        .calendar-day.selected {
            background-color: #28a745;
            /* Green background for selected date */
            border: 2px solid #218838;
            /* Darker green border */
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.3);
            color: #fff;
            /* White text for contrast */
        }

        .calendar-day.selected .nepali-date,
        .calendar-day.selected .english-date,
        .calendar-day.selected .ad-date,
        .calendar-day.selected .meeting-count {
            color: #fff;
            /* White text for child elements */
        }

        .calendar-day.selected .event {
            background-color: #218838;
            /* Slightly darker green for events */
        }

        .day-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
            /* For positioning meeting-count */
        }

        .calendar-day .nepali-date {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            padding: 5px 0;
        }

        .calendar-day.today .nepali-date {
            color: #0d6efd;
        }

        .calendar-day .english-date {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: center;
            margin-bottom: 5px;
        }

        .calendar-day .events {
            flex-grow: 1;
            margin: 5px 0;
        }

        .calendar-day .event {
            background-color: #52bd8b;
            color: white;
            border-radius: 3px;
            padding: 2px 5px;
            margin: 2px 0;
            display: block;
            font-size: 0.75rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .calendar-day .ad-date {
            font-size: 0.7rem;
            color: #6c757d;
            text-align: right;
            padding: 2px 5px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 3px;
        }

        .calendar-day.today .ad-date {
            color: #0d6efd;
            font-weight: 600;
        }

        /* New styles for meeting count */
        /* Meeting count wrapper for positioning */
        .meeting-count-wrapper {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 10;
            /* Ensure tooltip appears above other elements */
        }

        /* Existing meeting-count styles (unchanged) */
        .meeting-count {
            position: relative;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            /* Indicate hoverability */
        }

        /* Tooltip styles */
        .meeting-count:hover:after {
            content: attr(data-tooltip);
            /* Use data-tooltip attribute for content */
            position: absolute;
            top: 100%;
            /* Position below the circle */
            right: 50%;
            transform: translateX(50%);
            /* Center horizontally */
            background-color: #333;
            /* Dark background for tooltip */
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 20;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .meeting-count:hover:before {
            content: '';
            position: absolute;
            top: 100%;
            /* Triangle pointing up */
            right: 50%;
            transform: translateX(50%);
            border: 5px solid transparent;
            border-bottom-color: #333;
            /* Match tooltip background */
            z-index: 20;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .meeting-count:hover:after,
        .meeting-count:hover:before {
            opacity: 1;
            visibility: visible;
        }

        /* Adjust tooltip for today */
        .calendar-day.today .meeting-count {
            background-color: #0d6efd;
        }

        .calendar-day.today .meeting-count:hover:after {
            background-color: #0d6efd;
            /* Match today's color */
        }

        .calendar-day.today .meeting-count:hover:before {
            border-bottom-color: #0d6efd;
            /* Match today's color */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .meeting-count {
                width: 20px;
                height: 20px;
                font-size: 0.8rem;
            }

            .meeting-count:hover:after {
                font-size: 0.65rem;
                padding: 4px 8px;
            }
        }

        .calendar-day.today .meeting-count {
            background-color: #0d6efd;
            /* Blue for today */
        }

        .row.g-0 {
            margin: 0;
        }

        @media (max-width: 768px) {
            .calendar-day {
                min-height: 80px;
                padding: 3px;
            }

            .calendar-day .nepali-date {
                font-size: 1.1rem;
            }

            .calendar-day .english-date {
                font-size: 0.7rem;
            }

            .calendar-day .event {
                font-size: 0.65rem;
            }

            .calendar-day .ad-date {
                font-size: 0.6rem;
            }

            .meeting-count {
                width: 20px;
                height: 20px;
                font-size: 0.8rem;
            }
        }
    </style>
@endsection
