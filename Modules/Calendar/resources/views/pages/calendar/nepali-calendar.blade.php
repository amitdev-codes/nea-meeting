<?php use App\Helpers\NepaliDateConverter; ?>
@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-fluid m-4">
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
                                        {{ NepaliDateConverter::$nepaliMonths[$month] }}</option>
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
                    
                    <!-- Right: Nepali Month/Year | AD Month/Year -->
                    <div class="d-flex align-items-center">
                        <span id="monthYearRange" class="fw-semibold" style="color: #dc3545;">
                            {{ NepaliDateConverter::toNepaliDigits($currentBsYear) }} 
                            {{ NepaliDateConverter::$nepaliMonths[$currentBsMonth] }} | 
                            {{ !empty($calendarData['start_date']) ? Carbon\Carbon::parse($calendarData['start_date'])->format('M') . '/' . Carbon\Carbon::parse($calendarData['start_date'])->addDays($calendarData['days'] - 1)->format('M Y') : 'Mar/Apr 2025' }}
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

        <!-- Today's Special Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0 fs-6">आजको विशेष</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center bg-light p-3 rounded info-card">
                            <div class="bg-primary text-white rounded p-2 me-3">
                                <i class="bx bx-calendar-event fs-3"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-semibold">आजको तिथि</h6>
                                <p class="mb-0 text-muted" id="todayTithi">{{ $todayTithi ?? '---' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center bg-light p-3 rounded info-card">
                            <div class="bg-success text-white rounded p-2 me-3">
                                <i class="bx bx-sun fs-3"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-semibold">सूर्योदय</h6>
                                <p class="mb-0 text-muted" id="sunrise">{{ $sunrise ?? '---' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center bg-light p-3 rounded info-card">
                            <div class="bg-warning text-white rounded p-2 me-3">
                                <i class="bx bx-moon fs-3"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-semibold">सूर्यास्त</h6>
                                <p class="mb-0 text-muted" id="sunset">{{ $sunset ?? '---' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
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

        .weekday.sunday, .weekday.saturday {
            color: #dc3545;
        }

        .calendar-day {
            border: 1px solid #e9ecef;
            padding: 0.5rem;
            position: relative;
            min-height: 70px;
            transition: all 0.2s ease;
            background-color: #fff;
        }

        .calendar-day:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .calendar-day.today {
            background-color: rgba(255, 99, 71, 0.2);
            border: none;
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

        .calendar-day.sunday, .calendar-day.saturday {
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

        .calendar-day .events {
            margin-top: 3px;
        }

        .calendar-day .event {
            background-color: #198754;
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .info-card {
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            .calendar-day .english-date, .calendar-day .event, .calendar-day .ad-date {
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
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
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

            document.getElementById('day').addEventListener('change', updateCalendar);

            const todayCells = document.querySelectorAll('.calendar-day.today');
            if (todayCells.length > 0) {
                todayCells[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
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

            document.getElementById('currentMonthYear').textContent = `${NepaliDateConverter.nepaliMonths[month]} ${NepaliDateConverter.toNepaliDigits(year)}`;

            fetch(`/calendar/get-calendar-data/${year}/${month}`)
                .then(response => response.json())
                .then(data => {
                    const startDate = new Date(data.start_date);
                    const endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + data.days - 1);
                    const adRange = `${startDate.toLocaleString('en-US', { month: 'short' })}/${endDate.toLocaleString('en-US', { month: 'short' })} ${endDate.getFullYear()}`;
                    document.getElementById('monthYearRange').textContent = `${NepaliDateConverter.toNepaliDigits(year)} ${NepaliDateConverter.nepaliMonths[month]} | ${adRange}`;

                    fetch(`/calendar/get-calendar-grid-partial`, { // Updated route
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
                        updateTodayInfo(data);
                        const firstDayCell = document.querySelector('.calendar-day:not(.other-month)');
                        if (firstDayCell) {
                            firstDayCell.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    });
                });
        }

        function navigateMonth(change) {
            let monthSelect = document.getElementById('month');
            let yearSelect = document.getElementById('year');
            let currentMonthIndex = monthSelect.selectedIndex;
            let newMonthIndex = currentMonthIndex + change;

            if (newMonthIndex < 0) {
                if (yearSelect.selectedIndex > 0) {
                    yearSelect.selectedIndex--;
                    fetchMonths(yearSelect.value).then(() => {
                        let monthSelect = document.getElementById('month');
                        monthSelect.selectedIndex = monthSelect.options.length - 1;
                        updateCalendar();
                    });
                }
            } else if (newMonthIndex >= monthSelect.options.length) {
                if (yearSelect.selectedIndex < yearSelect.options.length - 1) {
                    yearSelect.selectedIndex++;
                    fetchMonths(yearSelect.value).then(() => {
                        let monthSelect = document.getElementById('month');
                        monthSelect.selectedIndex = 0;
                        updateCalendar();
                    });
                }
            } else {
                monthSelect.selectedIndex = newMonthIndex;
                updateCalendar();
            }
        }

        function updateTodayInfo(data) {
            if (data.today) {
                document.getElementById('todayTithi').textContent = data.today.tithi || '---';
                document.getElementById('sunrise').textContent = data.today.sunrise || '---';
                document.getElementById('sunset').textContent = data.today.sunset || '---';
            }
        }

        const NepaliDateConverter = {
            nepaliMonths: @json(\App\Helpers\NepaliDateConverter::$nepaliMonths),
            toNepaliDigits: function(number) {
                const digits = @json(\App\Helpers\NepaliDateConverter::$nepaliDigits);
                return String(number).split('').map(d => digits[d] || d).join('');
            }
        };
    </script>
@endpush