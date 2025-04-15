@if(isset($calendarData))
    <div class="calendar">
        <div class="calendar-header">
            <div class="row g-0">
                @foreach(['आइत', 'सोम', 'मंगल', 'बुध', 'बिही', 'शुक्र', 'शनि'] as $index => $day)
                    <div class="col weekday {{ $index == 0 ? 'sunday' : '' }} {{ $index == 6 ? 'saturday' : '' }}">{{ $day }}</div>
                @endforeach
            </div>
        </div>
        
        <div class="calendar-body">
            <div class="row g-0">
            @php
                $daysInMonth = $calendarData->days;
                $startDay = Carbon\Carbon::parse($calendarData->start_date)->dayOfWeek;
                
                // Get current date information
                $today = now();
                $currentDay = $today->day;
                $currentMonth = $today->month;
                $currentYear = $today->year;
                
                // Check if we're displaying the current month
                $calendarStartDate = Carbon\Carbon::parse($calendarData->start_date);
                $isCurrentMonth = ($calendarStartDate->month == $currentMonth && $calendarStartDate->year == $currentYear);
                
                $dayCount = 1;
                $totalDaysDisplayed = $daysInMonth + $startDay;
                $totalWeeks = ceil($totalDaysDisplayed / 7);
                
                // Loop through each week
                for ($week = 0; $week < $totalWeeks; $week++) {
                    // Loop through each day of the week
                    for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++) {
                        $currentPosition = $week * 7 + $dayOfWeek;
                        $isSaturday = $dayOfWeek == 6;
                        $isSunday = $dayOfWeek == 0;
                        
                        // Determine if this position is before the start of the month
                        if ($currentPosition < $startDay) {
                            echo '<div class="col calendar-day other-month ' . ($isSaturday ? 'saturday' : '') . ($isSunday ? 'sunday' : '') . '"></div>';
                        } 
                        // Determine if this position is a day in the month
                        else if ($currentPosition < $totalDaysDisplayed) {
                            $day = $currentPosition - $startDay + 1;
                            $isToday = ($isCurrentMonth && $day == $currentDay);
                            
                            // Get the English date equivalent if available
                            $englishDate = isset($calendarData->english_dates) && isset($calendarData->english_dates[$day]) 
                                ? $calendarData->english_dates[$day] 
                                : '';
                            
                            // Get events for this day if available
                            $events = isset($calendarData->events) && isset($calendarData->events[$day]) 
                                ? $calendarData->events[$day] 
                                : [];
                            
                            echo '<div class="col calendar-day ' . ($isToday ? 'today ' : '') . ($isSunday ? 'sunday ' : '') . ($isSaturday ? 'saturday ' : '') . '">';
                            echo '<div class="nepali-date">' . $day . '</div>';
                            
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
                            
                            echo '</div>';
                        } 
                        // Determine if this position is after the end of the month
                        else {
                            echo '<div class="col calendar-day other-month ' . ($isSaturday ? 'saturday' : '') . ($isSunday ? 'sunday' : '') . '"></div>';
                        }
                    }
                    
                    // Close current row and start a new one if not the last week
                    if ($week < $totalWeeks - 1) {
                        echo '</div><div class="row g-0">';
                    }
                }
            @endphp
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        Calendar data not available. Please select a valid year and month.
    </div>
@endif

<style>
    /* Nepali Calendar Styling */
    .calendar {
        width: 100%;
        border: 2px solid #dee2e6;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .calendar-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    .calendar-header .weekday {
        padding: 10px;
        text-align: center;
        font-weight: bold;
        color: #495057;
        border-right: 1px solid #dee2e6;
    }
    
    .calendar-header .weekday:last-child {
        border-right: none;
    }
    
    .weekday.sunday {
        color: #dc3545;
    }
    
    .weekday.saturday {
        color: #dc3545;
    }
    
    .calendar-day {
        border: 1px solid #dee2e6;
        padding: 8px;
        position: relative;
        min-height: 80px;
        overflow: hidden;
    }
    
    .calendar-day.today {
        background-color: rgba(13, 110, 253, 0.1);
        border: 2px solid #0d6efd;
    }
    
    .calendar-day.sunday {
        background-color: rgba(220, 53, 69, 0.05);
    }
    
    .calendar-day.saturday {
        background-color: rgba(220, 53, 69, 0.05);
        color: #dc3545;
    }
    
    .calendar-day.other-month {
        background-color: #f8f9fa;
        color: #adb5bd;
    }
    
    .calendar-day .nepali-date {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 4px;
        text-align: center;
    }
    
    .calendar-day .english-date {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: center;
    }
    
    .calendar-day .events {
        margin-top: 4px;
        font-size: 0.75rem;
    }
    
    .calendar-day .event {
        background-color: #198754;
        color: white;
        border-radius: 3px;
        padding: 1px 4px;
        margin-bottom: 2px;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Grid line styling */
    .row.g-0 {
        border-bottom: 1px solid #dee2e6;
    }
    
    .row.g-0:last-child {
        border-bottom: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .calendar-day {
            min-height: 60px;
            padding: 4px;
        }
        
        .calendar-day .nepali-date {
            font-size: 1rem;
        }
        
        .calendar-day .english-date {
            font-size: 0.65rem;
        }
    }
</style>
