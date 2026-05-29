<?php use App\Helpers\NepaliDateConverter; ?>

@if (isset($calendarData))
    <div class="calendar">
        <div class="calendar-header">
            <div class="row g-0">
                @foreach ([
                    ['nepali' => 'आइत', 'english' => 'Sun'],
                    ['nepali' => 'सोम',  'english' => 'Mon'],
                    ['nepali' => 'मंगल','english' => 'Tue'],
                    ['nepali' => 'बुध',  'english' => 'Wed'],
                    ['nepali' => 'बिही', 'english' => 'Thu'],
                    ['nepali' => 'शुक्र','english' => 'Fri'],
                    ['nepali' => 'शनि',  'english' => 'Sat'],
                ] as $index => $day)
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
                    $startDate   = Carbon\Carbon::parse($calendarData['start_date']);
                    $startDay    = $startDate->dayOfWeek; // 0 (Sun) – 6 (Sat)
                    $today       = Carbon\Carbon::now();
                    $currentDay  = $today->day;
                    $currentMonth= $today->month;
                    $currentYear = $today->year;
                    $isCurrentMonth = $startDate->month == $currentMonth && $startDate->year == $currentYear;

                    $totalDaysDisplayed = $daysInMonth + $startDay;
                    $totalWeeks = ceil($totalDaysDisplayed / 7);

                    $nepaliYear  = $calendarData['bs_year']  ?? NepaliDateConverter::gregorianToNepaliYear($startDate->year);
                    $nepaliMonth = $calendarData['month']    ?? NepaliDateConverter::gregorianToNepaliMonth($startDate->month);

                    for ($week = 0; $week < $totalWeeks; $week++) {
                        for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
                            $currentPosition = $week * 7 + $dayOfWeek;
                            $isSaturday = $dayOfWeek == 7;
                            $isSunday   = $dayOfWeek == 1;

                            if ($currentPosition <= $startDay) {
                                // Empty leading cell
                                echo '<div class="col calendar-day other-month'
                                    . ($isSaturday ? ' saturday' : '')
                                    . ($isSunday   ? ' sunday'   : '')
                                    . '"></div>';

                            } elseif ($currentPosition <= $totalDaysDisplayed) {
                                $day       = $currentPosition - $startDay;
                                $nepaliDay = NepaliDateConverter::toNepaliDigits($day);

                                $isToday      = $startDate->copy()->addDays($day - 1)->isToday();
                                $englishDate  = $calendarData['english_dates'][$day] ?? '';
                                $events       = $calendarData['events'][$day] ?? [];
                                $adDay        = $startDate->copy()->addDays($day - 1)->day;
                                $meetingCount = $calendarData['meeting_counts'][$day] ?? 0;

                                $nepaliDate = sprintf('%s-%s-%02d', $nepaliYear, $nepaliMonth, $day);

                                $classes = 'col calendar-day'
                                    . ($isToday    ? ' today'    : '')
                                    . ($isSunday   ? ' sunday'   : '')
                                    . ($isSaturday ? ' saturday' : '');

                                echo '<div class="' . $classes . '" data-nepali-date="' . $nepaliDate . '">';
                                echo '<div class="day-content">';

                                // Meeting badge
                                if ($meetingCount > 0) {
                                    echo '<div class="meeting-count-wrapper">';
                                    echo '<div class="meeting-count" data-tooltip="'
                                        . $meetingCount . ' Upcoming Meeting'
                                        . ($meetingCount > 1 ? 's' : '') . '">'
                                        . $meetingCount . '</div>';
                                    echo '</div>';
                                }

                                // Nepali date number
                                echo '<div class="nepali-date">' . $nepaliDay . '</div>';

                                // English date label
                                if (!empty($englishDate)) {
                                    echo '<div class="english-date">' . $englishDate . '</div>';
                                }

                                // Events
                                if (!empty($events)) {
                                    echo '<div class="events">';
                                    foreach ($events as $event) {
                                        echo '<span class="event">' . $event . '</span>';
                                    }
                                    echo '</div>';
                                }

                                // AD date (bottom-right)
                                echo '<div class="ad-date">' . $adDay . '</div>';
                                echo '</div>'; // .day-content
                                echo '</div>'; // .calendar-day

                            } else {
                                // Empty trailing cell
                                echo '<div class="col calendar-day other-month'
                                    . ($isSaturday ? ' saturday' : '')
                                    . ($isSunday   ? ' sunday'   : '')
                                    . '"></div>';
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

@push('vendor-style')
<style>
        /* ─── Root tokens ───────────────────────────────────────────────── */
        :root {
            --cal-accent:          #D85A30;
            --cal-accent-hover:    #B84A22;
            --cal-accent-soft:     #FAECE7;
            --cal-accent-mid:      #F0997B;
            --cal-today-bg:        #FFF7F4;
            --cal-today-border:    #D85A30;
            --cal-red-fg:          #993C1D;
            --cal-selected-bg:     #E1F5EE;
            --cal-selected-border: #0F6E56;
            --cal-selected-fg:     #085041;
            --cal-header-bg:       #2C2C2A;
            --cal-header-border:   #444441;
            --cal-cell-border:     rgba(0,0,0,0.08);
            --cal-other-bg:        #F8F7F5;
            --cal-weekend-bg:      #FFF9F8;
            --cal-radius:          10px;
            --cal-cell-min-h:      108px;
            --cal-np-size:         1.4rem;
            --cal-en-size:         0.72rem;
            --cal-ad-size:         0.68rem;
            --cal-ev-size:         0.7rem;
            --cal-badge-size:      20px;
        }

        /* ─── Wrapper ───────────────────────────────────────────────────── */
        .calendar {
            width: 100%;
            border-radius: var(--cal-radius);
            overflow: hidden;
            border: 1px solid var(--cal-cell-border);
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
        }

        /* ─── Header row (weekday labels) ───────────────────────────────── */
        .calendar-header {
            background: var(--cal-header-bg);
            padding: 0;
        }

        .calendar-header .weekday {
            padding: 11px 4px;
            text-align: center;
        }

        .calendar-header .nepali-weekday {
            font-size: 13px;
            font-weight: 500;
            color: #B4B2A9;
            display: block;
            line-height: 1;
            letter-spacing: 0.02em;
        }

        .calendar-header .english-weekday {
            font-size: 10px;
            color: #888780;
            display: block;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .calendar-header .weekday.sunday .nepali-weekday,
        .calendar-header .weekday.saturday .nepali-weekday {
            color: var(--cal-accent-mid);
        }

        .calendar-header .weekday.sunday .english-weekday,
        .calendar-header .weekday.saturday .english-weekday {
            color: var(--cal-accent-hover);
            opacity: 0.7;
        }

        /* ─── Calendar body / cells ─────────────────────────────────────── */
        .calendar-body {
            background: #fff;
        }

        .calendar-day {
            border-right: 1px solid var(--cal-cell-border);
            border-bottom: 1px solid var(--cal-cell-border);
            padding: 8px 8px 6px;
            min-height: var(--cal-cell-min-h);
            position: relative;
            background: #fff;
            transition: background 0.12s ease;
            cursor: pointer;
        }

        .calendar-day:hover {
            background: #FAFAF9;
        }

        /* Weekends */
        .calendar-day.sunday,
        .calendar-day.saturday {
            background: var(--cal-weekend-bg);
        }
        .calendar-day.sunday:hover,
        .calendar-day.saturday:hover {
            background: var(--cal-accent-soft);
        }

        /* Empty / other-month cells */
        .calendar-day.other-month {
            background: var(--cal-other-bg);
            cursor: default;
            pointer-events: none;
        }
        .calendar-day.other-month:hover {
            background: var(--cal-other-bg);
        }

        /* Today */
        .calendar-day.today {
            background: var(--cal-today-bg);
            outline: 2px solid var(--cal-today-border);
            outline-offset: -2px;
            border-radius: 2px;
            z-index: 1;
        }

        /* Selected */
        .calendar-day.selected {
            background: var(--cal-selected-bg);
            outline: 2px solid var(--cal-selected-border);
            outline-offset: -2px;
            border-radius: 2px;
            z-index: 1;
        }

        /* ─── Day content layout ────────────────────────────────────────── */
        .day-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }

        /* ─── Nepali date number ────────────────────────────────────────── */
        .calendar-day .nepali-date {
            font-size: var(--cal-np-size);
            font-weight: 500;
            color: #2C2C2A;
            line-height: 1;
            padding: 2px 0 3px;
            /* leave room for badge on the right */
            padding-right: 26px;
        }

        .calendar-day.today .nepali-date     { color: var(--cal-today-border); }
        .calendar-day.selected .nepali-date  { color: var(--cal-selected-fg);  }
        .calendar-day.sunday .nepali-date,
        .calendar-day.saturday .nepali-date  { color: var(--cal-red-fg);       }

        /* Today overrides weekend colour */
        .calendar-day.today.sunday .nepali-date,
        .calendar-day.today.saturday .nepali-date { color: var(--cal-today-border); }

        /* ─── English date label ────────────────────────────────────────── */
        .calendar-day .english-date {
            font-size: var(--cal-en-size);
            color: #888780;
            margin-bottom: 4px;
            letter-spacing: 0.015em;
        }

        /* ─── Events list ───────────────────────────────────────────────── */
        .calendar-day .events {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin: 2px 0;
        }

        .calendar-day .event {
            background: var(--cal-accent-soft);
            color: var(--cal-accent);
            border-radius: 3px;
            padding: 2px 5px;
            font-size: var(--cal-ev-size);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .calendar-day.selected .event {
            background: #9FE1CB;
            color: var(--cal-selected-fg);
        }

        /* ─── AD date (bottom-right) ────────────────────────────────────── */
        .calendar-day .ad-date {
            font-size: var(--cal-ad-size);
            color: #B4B2A9;
            text-align: right;
            padding: 2px 0 0;
            margin-top: auto;
            font-variant-numeric: tabular-nums;
        }

        .calendar-day.today .ad-date    { color: var(--cal-today-border); font-weight: 500; }
        .calendar-day.selected .ad-date { color: var(--cal-selected-fg);  }

        /* ─── Meeting count badge ───────────────────────────────────────── */
        .meeting-count-wrapper {
            position: absolute;
            top: 4px;
            right: 4px;
            z-index: 10;
        }

        .meeting-count {
            position: relative;
            background: #E24B4A;
            color: #fff;
            border-radius: 50%;
            width:  var(--cal-badge-size);
            height: var(--cal-badge-size);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(226,75,74,0.35);
            transition: transform 0.12s ease;
        }
        .meeting-count:hover {
            transform: scale(1.12);
        }

        /* Tooltip */
        .meeting-count::after {
            content: attr(data-tooltip);
            position: absolute;
            top: calc(100% + 6px);
            right: 50%;
            transform: translateX(50%);
            background: #2C2C2A;
            color: #fff;
            padding: 4px 9px;
            border-radius: 4px;
            font-size: 0.68rem;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.15s ease, visibility 0.15s ease;
            z-index: 20;
        }
        .meeting-count::before {
            content: '';
            position: absolute;
            top: 100%;
            right: 50%;
            transform: translateX(50%);
            border: 4px solid transparent;
            border-bottom-color: #2C2C2A;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.15s ease, visibility 0.15s ease;
            z-index: 20;
        }
        .meeting-count:hover::after,
        .meeting-count:hover::before {
            opacity: 1;
            visibility: visible;
        }

        /* Today's badge is blue */
        .calendar-day.today .meeting-count {
            background: var(--cal-today-border);
            box-shadow: 0 1px 3px rgba(216,90,48,0.4);
        }

        /* Selected badge is green */
        .calendar-day.selected .meeting-count {
            background: var(--cal-selected-border);
            box-shadow: 0 1px 3px rgba(15,110,86,0.35);
        }

        /* ─── Bootstrap row reset ───────────────────────────────────────── */
        .row.g-0 { margin: 0; }

        /* ─── Responsive ────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            :root {
                --cal-cell-min-h: 78px;
                --cal-np-size:    1.05rem;
                --cal-en-size:    0.62rem;
                --cal-ad-size:    0.58rem;
                --cal-ev-size:    0.6rem;
                --cal-badge-size: 18px;
            }

            .calendar-day { padding: 5px 5px 4px; }

            .calendar-header .nepali-weekday  { font-size: 11px; }
            .calendar-header .english-weekday { font-size: 9px;  }

            .meeting-count { font-size: 0.65rem; }
        }

        @media (max-width: 480px) {
            :root {
                --cal-cell-min-h: 62px;
                --cal-np-size:    0.9rem;
            }
            .calendar-header .english-weekday { display: none; }
            .calendar-day .english-date       { display: none; }
        }
    </style>
@endpush


@push('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const days = document.querySelectorAll('.calendar-day:not(.other-month)');
            let selected = null;

            days.forEach(function (cell) {
                cell.addEventListener('click', function () {
                    if (selected && selected !== cell) {
                        selected.classList.remove('selected');
                    }
                    if (selected === cell) {
                        cell.classList.remove('selected');
                        selected = null;
                    } else {
                        cell.classList.add('selected');
                        selected = cell;
                    }
                });
            });
        });
    </script>
@endpush
