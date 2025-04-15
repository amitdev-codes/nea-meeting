<?php use App\Helpers\NepaliDateConverter; ?>

@if(isset($calendarData))
    <div class="calendar">
        <div class="calendar-header">
            <div class="row g-0">
                @foreach([
                    ['nepali' => 'आइत', 'english' => 'Sun'],
                    ['nepali' => 'सोम', 'english' => 'Mon'],
                    ['nepali' => 'मंगल', 'english' => 'Tue'],
                    ['nepali' => 'बुध', 'english' => 'Wed'],
                    ['nepali' => 'बिही', 'english' => 'Thu'],
                    ['nepali' => 'शुक्र', 'english' => 'Fri'],
                    ['nepali' => 'शनि', 'english' => 'Sat']
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
                $startDate = Carbon\Carbon::parse($calendarData['start_date']);
                $startDay = $startDate->dayOfWeek; // 0 (Sun) to 6 (Sat)
                $today = Carbon\Carbon::now();
                $currentDay = $today->day;
                $currentMonth = $today->month;
                $currentYear = $today->year;
                
                $isCurrentMonth = ($startDate->month == $currentMonth && $startDate->year == $currentYear);
                
                $totalDaysDisplayed = $daysInMonth + $startDay;
                $totalWeeks = ceil($totalDaysDisplayed / 7);
                
                for ($week = 0; $week < $totalWeeks; $week++) {
                    for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
                        $currentPosition = $week * 7 + $dayOfWeek;
                        $isSaturday = $dayOfWeek == 7;
                        $isSunday = $dayOfWeek == 1;
                        
                        if ($currentPosition <= $startDay) {
                            echo '<div class="col calendar-day other-month ' . ($isSaturday ? 'saturday' : '') . ($isSunday ? 'sunday' : '') . '"></div>';
                        } 
                        else if ($currentPosition <= $totalDaysDisplayed) {
                            $day = $currentPosition - $startDay;
                            $nepaliDay = NepaliDateConverter::toNepaliDigits($day);
                            $isToday = ($isCurrentMonth && $startDate->copy()->addDays($day - 1)->isToday());
                            $englishDate = isset($calendarData['english_dates'][$day]) ? $calendarData['english_dates'][$day] : '';
                            $events = isset($calendarData['events'][$day]) ? $calendarData['events'][$day] : [];
                            $adDay = $startDate->copy()->addDays($day - 1)->day;
                            
                            echo '<div class="col calendar-day ' . ($isToday ? 'today ' : '') . ($isSunday ? 'sunday ' : '') . ($isSaturday ? 'saturday ' : '') . '">';
                            echo '<div class="day-content">';
                            echo '<div class="nepali-date">' . $nepaliDay . '</div>';
                            if(!empty($englishDate)) {
                                echo '<div class="english-date">' . $englishDate . '</div>';
                            }
                            if(!empty($events)) {
                                echo '<div class="events">';
                                foreach($events as $event) {
                                    echo '<span class="event">' . $event . '</span>';
                                }
                                echo '</div>';
                            }
                            echo '<div class="ad-date">' . $adDay . '</div>';
                            echo '</div>';
                            echo '</div>';
                        } 
                        else {
                            echo '<div class="col calendar-day other-month ' . ($isSaturday ? 'saturday' : '') . ($isSunday ? 'sunday' : '') . '"></div>';
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
<style>
    .calendar {
        width: 100%;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
    
    .weekday.sunday, .weekday.saturday {
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
    
    .day-content {
        display: flex;
        flex-direction: column;
        height: 100%;
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
        background-color: #198754;
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
    }
</style>