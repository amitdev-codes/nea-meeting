<?php use App\Helpers\NepaliDateConverter; ?>
<div class="tab-pane fade" id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
    <div class="p-4">
        <h5 class="mb-4">{{ __('Calendar View') }}</h5>
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
                                        <option value="{{ $year }}"
                                            {{ $year == $currentBsYear ? 'selected' : '' }}>
                                            {{ NepaliDateConverter::toNepaliDigits($year) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="dropdown-container">
                                <select class="form-select shadow-sm" id="month" name="month">
                                    @foreach ($months as $index => $month)
                                        <option value="{{ $month }}"
                                            {{ $month == $currentBsMonth ? 'selected' : '' }}>
                                            {{ NepaliDateConverter::$nepaliMonths[$month] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="dropdown-container">
                                <select class="form-select shadow-sm" id="day" name="day">
                                    @for ($i = 1; $i <= $days; $i++)
                                        <option value="{{ $i }}"
                                            {{ $i == $currentNepaliDay ? 'selected' : '' }}>
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
    </div>
</div>


@push('scripts')
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
        // console.log(todayCells.length);

        // alert('hello');
        if (todayCells.length > 0) {
            todayCells[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Load today's meetings by default
            const year = document.getElementById('year').value;
            const month = document.getElementById('month').value;
            const day = document.getElementById('day').value;

            console.log(year, month, day);

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
                if (nepaliDate) {
                    const [year, month, day] = nepaliDate.split('-');

                    // Update day dropdown selection
                    document.getElementById('day').value = parseInt(day);

                    // Load meetings for the selected date
                    // console.log(year, month, day);
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
            .then(response => {
                    console.log('Fetch response status:', response.status, response.statusText);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })

                .then(data => {
                    if (data.meetings && data.meetings.length > 0) {
                        let html = `<div class="list-group">`;
                        data.meetings.forEach(meeting => {
                            const startTime = new Date(meeting.start_time).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            const endTime = meeting.end_time ?
                                new Date(meeting.end_time).toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) :
                                '';
                            html += `
                                <a href="#" class="list-group-item list-group-item-action meeting-list-item ${meeting.status}" data-meeting-id="${meeting.id}">
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

                        // Add click event listeners to meeting items
                        document.querySelectorAll('.meeting-list-item').forEach(item => {
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                const meetingId = this.getAttribute('data-meeting-id');
                                fetchMeetingDetails(meetingId);
                                // Show the modal
                                const meetingModal = new bootstrap.Modal(document.getElementById(
                                    'meetingModal'));
                                meetingModal.show();
                            });
                        });
                    } else {
                        meetingsContainer.innerHTML = `
                            <div class="no-meetings">
                                <i class="bx bx-calendar-x fs-1 mb-2"></i>
                                <h6>No meetings scheduled for this date</h6>
                                <p class="mb-0">There are no meetings scheduled for the selected date.</p>

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

        function fetchMeetingDetails(meetingId) {
            fetch(`/view/${meetingId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // Populate Meeting Information
                    document.getElementById('meeting-title').querySelector('p').textContent = data.title || 'N/A';
                    document.getElementById('meeting-location').querySelector('p').textContent = data.is_virtual ?
                        '{{ __('Virtual') }}' : (data.meeting_location || data.meeting_room?.name || 'N/A');
                    document.getElementById('meeting-date').querySelector('p').textContent =
                        `${data.meeting_date} (${data.meeting_date_ad})` || 'N/A';
                    document.getElementById('start-time').querySelector('p').textContent = data.start_time || 'N/A';
                    document.getElementById('end-time').querySelector('p').textContent = data.end_time || 'N/A';
                    document.getElementById('meeting-room').querySelector('p').textContent = data.meeting_room?.name ||
                        'N/A';
                    document.getElementById('meeting-type').querySelector('p').textContent = data.meeting_type || 'N/A';
                    document.getElementById('is-external').querySelector('p').textContent = data.is_external || 'N/A';
                    document.getElementById('is-virtual-meeting').querySelector('p').textContent = data
                        .is_virtual_meeting || 'N/A';
                    document.getElementById('virtual-meeting-link').querySelector('p').textContent = data
                        .virtual_meeting_link || 'N/A';

                    // Handle virtual meeting link
                    const virtualMeetingLinkP = document.getElementById('virtual-meeting-link').querySelector('p');
                    const isValidUrl = url => /^https?:\/\//.test(url); // Simple URL validation
                    if (data.virtual_meeting_link && isValidUrl(data.virtual_meeting_link)) {
                        virtualMeetingLinkP.innerHTML =
                            `<a href="${data.virtual_meeting_link}" target="_blank" class="text-primary"><i class="bx bx-link me-2 detail-icon"></i>${data.virtual_meeting_link}</a>`;
                    } else {
                        virtualMeetingLinkP.innerHTML =
                            `<i class="bx bx-link me-2 detail-icon"></i>{{ __('N/A') }}`;
                    }

                    // Populate Documents
                    const documentsGrid = document.getElementById('documents-grid');
                    const emptyDocuments = document.getElementById('empty-documents');
                    const viewAllDocuments = document.getElementById('view-all-documents');
                    const documentCount = document.getElementById('document-count');
                    documentsGrid.innerHTML = '';

                    if (data.media && data.media.length > 0) {
                        emptyDocuments.style.display = 'none';
                        data.media.slice(0, 6).forEach(media => {
                            let iconClass, iconBg;
                            if (media.mime_type === 'application/pdf') {
                                iconClass = 'bxs-file-pdf';
                                iconBg = 'pdf-icon';
                            } else if (['text/plain', 'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                ].includes(media.mime_type)) {
                                iconClass = 'bxs-file-doc';
                                iconBg = 'doc-icon';
                            } else if (media.mime_type.startsWith('image/')) {
                                iconClass = '';
                                iconBg = 'img-icon';
                            } else {
                                iconClass = 'bxs-file';
                                iconBg = 'generic-icon';
                            }

                            const documentHtml = `
                            <div class="document-item">
                                <div class="document-preview">
                                    <div class="file-icon ${iconBg}">
                                        ${media.mime_type.startsWith('image/') ? `<img src="${media.url}" alt="${media.name || media.file_name}" class="thumbnail">` : `<i class="bx ${iconClass}"></i>`}
                                    </div>
                                    <div class="document-info">
                                        <div class="document-name text-truncate" title="${media.name || media.file_name}">
                                            ${media.name || media.file_name}
                                        </div>
                                        <div class="document-actions">
                                            <a href="${media.url}" target="_blank" class="action-btn view-btn" title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="${media.url}" download="${media.file_name}" class="action-btn download-btn" title="Download">
                                                <i class="bx bx-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                            documentsGrid.insertAdjacentHTML('beforeend', documentHtml);
                        });

                        if (data.media.length > 6) {
                            viewAllDocuments.style.display = 'block';
                            documentCount.textContent = data.media.length;
                        } else {
                            viewAllDocuments.style.display = 'none';
                        }
                    } else {
                        emptyDocuments.style.display = 'block';
                        viewAllDocuments.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching meeting details:', error));
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



