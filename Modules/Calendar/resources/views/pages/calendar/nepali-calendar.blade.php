<?php use App\Helpers\NepaliDateConverter; ?>
@extends('layouts/contentNavbarLayout')
@section('content')
    <div class="container-fluid m-1 p-4">
        <!-- Calendar Grid -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <!-- Left: Today's Full Date -->
                    <h4 class="mb-0 fs-5" id="currentMonthYear">{{ $todaysDate['full_date_time'] }}</h4>

                    <!-- Center: Dropdowns -->
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 flex-grow-1 mx-3">
                        <div class="dropdown-container">
                            <select class="form-select shadow-sm" id="year" name="year">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ $year == $currentBsYear ? 'selected' : '' }}>
                                        {{ NepaliDateConverter::toNepaliDigits($year) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="dropdown-container">
                            <select class="form-select shadow-sm" id="month" name="month">
                                @foreach ($months as $index => $month)
                                    <option value="{{ $month }}" {{ $month == $currentBsMonth ? 'selected' : '' }}>
                                        {{ NepaliDateConverter::$nepaliMonths[$month] }}
                                        ({{ NepaliDateConverter::$englishNepaliMonths[$month] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="dropdown-container">
                            <select class="form-select shadow-sm" id="day" name="day">
                                @for ($i = 1; $i <= $days; $i++)
                                    <option value="{{ $i }}" {{ $i == $currentNepaliDay ? 'selected' : '' }}>
                                        {{ NepaliDateConverter::toNepaliDigits($i) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Right: Nepali Month/Year | English Nepali Month | AD Month/Year -->
                    <div class="d-flex align-items-center">
                        <span id="monthYearRange" class="fw-semibold" style="color: #dc3545;">
                            {{ NepaliDateConverter::toNepaliDigits($currentBsYear) }}
                            {{ NepaliDateConverter::$nepaliMonths[$currentBsMonth] }}
                            ({{ NepaliDateConverter::$englishNepaliMonths[$currentBsMonth] }}) |
                            {{ !empty($calendarData['start_date'])? Carbon\Carbon::parse($calendarData['start_date'])->format('M') .'/' .Carbon\Carbon::parse($calendarData['start_date'])->addDays($calendarData['days'] - 1)->format('M Y'): 'Mar/Apr 2025' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="calendarGrid" class="calendar-container">
                    @include('calendar::partials.calendar-grid', ['calendarData' => $calendarData])
                </div>
            </div>
        </div>
        <!-- Meeting Schedule Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-0 fs-6">Meeting Schedule</h5>
                <span id="selectedDate" class="badge bg-primary">{{ $todaysDate['full_date_time'] }}</span>
            </div>
            <div class="card-body p-4">
                <div id="meetingsList">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading meetings...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page-script')
    <script type="module">
        const NepaliDateConverter = {
            nepaliMonths: @json(\App\Helpers\NepaliDateConverter::$nepaliMonths),
            toNepaliDigits: function(number) {
                const digits = @json(\App\Helpers\NepaliDateConverter::$nepaliDigits);
                return String(number).split('').map(d => digits[d] || d).join('');
            }
        };

        document.getElementById('year').addEventListener('change', function() {
            const selectedYear = this.value;
            const currentMonth = document.getElementById('month').value;
            fetchMonths(selectedYear, currentMonth).then(() => updateCalendar());
        });

        document.getElementById('month').addEventListener('change', function() {
            const year = document.getElementById('year').value;
            const selectedMonth = this.value;
            fetchDays(year, selectedMonth).then(() => updateCalendar());
        });

        document.getElementById('day').addEventListener('change', function() {
            updateCalendar();
            const year = document.getElementById('year').value;
            const month = document.getElementById('month').value;
            const day = this.value;
            loadMeetings(year, month, day);
        });

        const todayCells = document.querySelectorAll('.calendar-day.today');
        if (todayCells.length > 0) {
            todayCells[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Load today's meetings by default
            const year = document.getElementById('year').value;
            const month = document.getElementById('month').value;
            const day = document.getElementById('day').value;
            loadMeetings(year, month, day);
        }

        // Set up event delegation for calendar day clicks
        document.addEventListener('click', function(e) {
            const calendarDay = e.target.closest('.calendar-day:not(.other-month)');
            // alert('test');
            if (calendarDay) {
                // Remove selected class from all cells
                document.querySelectorAll('.calendar-day.selected').forEach(el => {
                    el.classList.remove('selected');
                });

                // Add selected class to clicked cell
                calendarDay.classList.add('selected');

                // Get the Nepali date from the clicked cell
                const nepaliDate = calendarDay.getAttribute('data-nepali-date');
                console.log("nepaliDate", nepaliDate);
                if (nepaliDate) {
                    const [year, month, day] = nepaliDate.split('-');

                    // Update day dropdown selection
                    document.getElementById('day').value = parseInt(day);

                    // Load meetings for the selected date
                    loadMeetings(year, month, day);

                    // Format and display the Nepali date
                    const formattedDate =
                        `${NepaliDateConverter.nepaliMonths[month]} ${NepaliDateConverter.toNepaliDigits(day)}, ${NepaliDateConverter.toNepaliDigits(year)}`;
                    document.getElementById('selectedDate').textContent = formattedDate;
                }
            }
        });


        function fetchMonths(year, preserveMonth = null) {
            return fetch(`/calendar/get-months/${year}`)
                .then(response => response.json())
                .then(data => {
                    let monthSelect = document.getElementById('month');
                    monthSelect.innerHTML = '';
                    data.forEach(month => {
                        let option = document.createElement('option');
                        option.value = month;
                        option.text = NepaliDateConverter.nepaliMonths[month];
                        monthSelect.appendChild(option);
                    });
                    if (preserveMonth && data.includes(parseInt(preserveMonth))) {
                        monthSelect.value = preserveMonth;
                    } else {
                        monthSelect.value = data[0];
                    }
                    return fetchDays(year, monthSelect.value);
                });
        }

        function fetchDays(year, month) {
            return fetch(`/calendar/get-days/${year}/${month}`)
                .then(response => response.json())
                .then(data => {
                    let daySelect = document.getElementById('day');
                    daySelect.innerHTML = '';
                    for (let i = 1; i <= data; i++) {
                        let option = document.createElement('option');
                        option.value = i;
                        option.text = NepaliDateConverter.toNepaliDigits(i);
                        daySelect.appendChild(option);
                    }
                });
        }

        function updateCalendar() {
            const year = document.getElementById('year').value;
            const month = document.getElementById('month').value;
            const day = document.getElementById('day').value;

            document.getElementById('currentMonthYear').textContent =
                `${NepaliDateConverter.nepaliMonths[month]} ${NepaliDateConverter.toNepaliDigits(year)}`;

            fetch(`/calendar/get-calendar-data/${year}/${month}`)
                .then(response => response.json())
                .then(data => {
                    const startDate = new Date(data.start_date);
                    const endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + data.days - 1);
                    const adRange =
                        `${startDate.toLocaleString('en-US', { month: 'short' })}/${endDate.toLocaleString('en-US', { month: 'short' })} ${endDate.getFullYear()}`;
                    document.getElementById('monthYearRange').textContent =
                        `${NepaliDateConverter.toNepaliDigits(year)} ${NepaliDateConverter.nepaliMonths[month]} | ${adRange}`;

                    // Also fetch meeting dates for the current month to highlight on calendar
                    fetch(`/meetings/get-meetings-dates/${year}/${month}`)
                        .then(response => response.json())
                        .then(meetingDates => {
                            // Store meeting dates to use when rendering the calendar
                            data.meetingDates = meetingDates;

                            fetch(`/calendar/get-calendar-grid-partial`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    calendarData: data
                                })
                            })
                                .then(response => response.text())
                                .then(html => {
                                    document.getElementById('calendarGrid').innerHTML = html;

                                    // Add has-meetings class to days with meetings
                                    if (meetingDates && meetingDates.length > 0) {
                                        meetingDates.forEach(date => {
                                            const cellSelector =
                                                `.calendar-day[data-nepali-date="${date}"]`;
                                            const cell = document.querySelector(cellSelector);
                                            if (cell) {
                                                cell.classList.add('has-meetings');
                                            }
                                        });
                                    }

                                    // Highlight the selected day
                                    const selectedDay = document.querySelector(
                                        `.calendar-day[data-nepali-date="${year}-${month}-${day}"]`);
                                    if (selectedDay) {
                                        selectedDay.classList.add('selected');
                                        selectedDay.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                    }
                                });
                        });
                });
        }

        function loadMeetings(year, month, day) {
            const meetingsContainer = document.getElementById('meetingsList');
            meetingsContainer.innerHTML = `
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading meetings...</p>
                </div>
            `;

            // Update selected date display
            document.getElementById('selectedDate').textContent =
                `${NepaliDateConverter.nepaliMonths[month]} ${NepaliDateConverter.toNepaliDigits(day)}, ${NepaliDateConverter.toNepaliDigits(year)}`;

            fetch(`/meetings/get-by-date/${year}/${month}/${day}`)
                .then(response => response.json())
                .then(data => {
                    if (data.meetings && data.meetings.length > 0) {
                        let html = `<div class="list-group">`;
                        data.meetings.forEach(meeting => {
                            const startTime = new Date(meeting.start_time).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            // Only format endTime if meeting.end_time exists
                            const endTime = meeting.end_time ?
                                new Date(meeting.end_time).toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) :
                                '';

                            html += `
                                <a href="/meetings/meetings/${meeting.id}" class="list-group-item list-group-item-action meeting-list-item ${meeting.status}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">${meeting.title}</h6>
                                        <span class="badge bg-${getStatusBadgeColor(meeting.status)}">${meeting.status}</span>
                                    </div>
                                    <div class="mb-1 meeting-time">
                                        <i class="bx bx-time-five me-1"></i> ${startTime}${endTime ? ` - ${endTime}` : ''}
                                    </div>
                                    <div class="meeting-location">
                                        ${meeting.is_virtual ?
                                `<i class="bx bx-video me-1"></i> Virtual Meeting` :
                                `<i class="bx bx-map me-1"></i> ${meeting.meeting_location || meeting.meeting_room?.name || 'Location not specified'}`
                            }
                                    </div>
                                </a>
                            `;
                        });

                        html += `</div>`;
                        meetingsContainer.innerHTML = html;
                    } else {
                        meetingsContainer.innerHTML = `
                            <div class="no-meetings">
                                <i class="bx bx-calendar-x fs-1 mb-2"></i>
                                <h6>No meetings scheduled for this date</h6>
                                <p class="mb-0">There are no meetings scheduled for the selected date.</p>
                                <a href="/meetings/meetings/create" class="btn btn-primary btn-sm mt-3">
                                    <i class="bx bx-plus me-1"></i> Schedule New Meeting
                                </a>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching meetings:', error);
                    meetingsContainer.innerHTML = `
                        <div class="alert alert-danger">
                            Failed to load meetings. Please try again later.
                        </div>
                    `;
                });
        }

        function getStatusBadgeColor(status) {
            switch (status) {
                case 'Scheduled':
                    return 'primary';
                case 'Ongoing':
                    return 'info';
                case 'Completed':
                    return 'success';
                case 'Cancelled':
                    return 'danger';
                case 'Postponed':
                    return 'warning';
                default:
                    return 'secondary';
            }
        }
    </script>
@endpush
@push('page-style')
    <style>
        /* Enhanced Nepali Calendar Styling */
        .calendar {
            width: 100%;
            border: none;
            border-radius: 0.5rem;
            overflow: hidden;
            background: #fff;
        }

        .calendar-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            padding: 1rem;
        }

        .calendar-header .weekday {
            padding: 0.5rem;
            text-align: center;
            font-weight: 700;
            color: #2c3e50;
        }

        .nepali-weekday {
            font-size: 1rem;
            margin-bottom: 2px;
        }

        .english-weekday {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .weekday.sunday,
        .weekday.saturday {
            color: #dc3545;
        }

        .calendar-day {
            border: 1px solid #e9ecef;
            padding: 0.5rem;
            position: relative;
            min-height: 70px;
            transition: all 0.2s ease;
            background-color: #fff;
            cursor: pointer;
        }

        .calendar-day:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .calendar-day.today {
            background-color: rgba(255, 99, 71, 0.2);
            border: none;
            border-radius: 4px;
        }

        .calendar-day.selected {
            background-color: rgba(13, 110, 253, 0.2);
            border: 2px solid #0d6efd;
            border-radius: 4px;
        }

        .calendar-day.today .nepali-date {
            color: #dc3545;
        }

        .calendar-day.today::after {
            content: 'आज';
            position: absolute;
            top: 3px;
            right: 3px;
            background: #dc3545;
            color: #fff;
            padding: 1px 5px;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 600;
        }

        .calendar-day.has-meetings::before {
            content: '';
            position: absolute;
            bottom: 3px;
            left: 3px;
            width: 8px;
            height: 8px;
            background-color: #198754;
            border-radius: 50%;
        }

        .calendar-day.sunday,
        .calendar-day.saturday {
            background-color: rgba(220, 53, 69, 0.03);
        }

        .calendar-day.other-month {
            background-color: #f8f9fa;
            color: #adb5bd;
        }

        .calendar-day .nepali-date {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 3px;
        }

        .calendar-day .english-date {
            font-size: 0.7rem;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .calendar-day.selected {
            transition: background-color 0.3s ease, border 0.3s ease, color 0.3s ease;
        }

        .calendar-day .events {
            margin-top: 3px;
        }

        .calendar-day .event {
            background-color: #68ce9e;
            color: white;
            border-radius: 3px;
            padding: 1px 4px;
            margin-bottom: 2px;
            font-size: 0.65rem;
        }

        .calendar-day .ad-date {
            position: absolute;
            bottom: 3px;
            right: 3px;
            font-size: 0.65rem;
            color: #6c757d;
            padding: 1px 4px;
            border-radius: 2px;
        }

        .calendar-day.today .ad-date {
            color: #dc3545;
            font-weight: 600;
        }

        .calendar-day[data-nepali-date] {
            border: 1px solid green;
            /* Temporary for debugging */
        }

        /* Meeting List Styles */
        .meeting-list-item {
            border-left: 4px solid #0d6efd;
            background-color: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .meeting-list-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .meeting-list-item.completed {
            border-left-color: #198754;
        }

        .meeting-list-item.cancelled {
            border-left-color: #dc3545;
        }

        .meeting-list-item.pending {
            border-left-color: #fd7e14;
        }

        .meeting-time {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .meeting-location {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .no-meetings {
            padding: 30px;
            text-align: center;
            color: #6c757d;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        /* Dropdown Styling */
        .dropdown-container {
            display: flex;
            align-items: center;
        }

        .form-select {
            padding: 0.5rem;
            font-size: 0.9rem;
            min-width: 80px;
        }

        .form-label {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        /* Card Enhancements */
        .card {
            border: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .calendar-header {
                padding: 0.75rem;
            }

            .dropdown-container {
                margin-bottom: 0.5rem;
            }

            #monthYearRange {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 767.98px) {
            .calendar-day {
                min-height: 60px;
                padding: 0.3rem;
            }

            .calendar-day .nepali-date {
                font-size: 1rem;
            }

            .calendar-day .english-date,
            .calendar-day .event,
            .calendar-day .ad-date {
                font-size: 0.6rem;
            }

            .form-select {
                font-size: 0.85rem;
            }

            #monthYearRange {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 575.98px) {
            .container-fluid {
                margin: 1rem !important;
            }

            .calendar-day {
                min-height: 50px;
                padding: 0.2rem;
            }

            .calendar-header .weekday {
                padding: 0.3rem;
            }

            .nepali-weekday {
                font-size: 0.85rem;
            }

            .english-weekday {
                font-size: 0.65rem;
            }

            .form-label {
                font-size: 0.8rem;
            }

            #monthYearRange {
                font-size: 0.75rem;
            }
        }
    </style>
@endpush

