<ul class="nav nav-tabs card-header-tabs mb-0" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold" id="meetings-tab" data-bs-toggle="tab" data-bs-target="#meetings" type="button" role="tab" aria-controls="meetings" aria-selected="true">
            <i class="bx bx-calendar-event me-2 text-white "></i>
            {{ __('field.upcoming_meetings') }}
            @if ($upcomingMeetings->isNotEmpty())
                <span class="badge text-white rounded-pill ms-2">
                    {{ $upcomingMeetings->total() }}
                </span>
            @endif
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab" aria-controls="calendar" aria-selected="false">
            <i class="bx bx-calendar me-2"></i>
            {{ __('field.calendar') }}
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">
            <i class="bx bx-bar-chart-alt-2 me-2"></i>
            {{ __('field.dashboard') }}
        </button>
    </li>
</ul>