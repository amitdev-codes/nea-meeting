
<ul class="nav nav-tabs nav-tabs-responsive" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold" id="meetings-tab" data-bs-toggle="tab" data-bs-target="#meetings" type="button" role="tab" aria-controls="meetings" aria-selected="true">
            <i class="bx bx-calendar-event"></i>
            <span class="tab-text">{{ __('field.upcoming_meetings') }}</span>
            @if ($upcomingMeetings->isNotEmpty())
                <span class="badge rounded-pill">
                    {{ $upcomingMeetings->total() }}
                </span>
            @endif
        </button>
    </li>
    
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab" aria-controls="calendar" aria-selected="false">
            <i class="bx bx-calendar"></i>
            <span class="tab-text">{{ __('field.calendar') }}</span>
        </button>
    </li>
    
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">
            <i class="bx bx-bar-chart-alt-2"></i>
            <span class="tab-text">{{ __('field.dashboard') }}</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" id="google-calendar-tab" data-bs-toggle="tab" data-bs-target="#google-calendar" type="button" role="tab" aria-controls="google-calendar" aria-selected="false">
            <i class="bx bxl-google"></i>
            <span class="tab-text">{{ __('Google Calendar') }}</span>
        </button>
    </li>
</ul>
