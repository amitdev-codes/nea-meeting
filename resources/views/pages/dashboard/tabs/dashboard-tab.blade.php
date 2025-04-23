<div class="tab-pane fade" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
    <!-- Total Counts Section -->
    <div class="container-xxl py-4">
        @include('partials.dashboard.summary-cards', [
            'todaysMeetings' => $todaysMeetings,
            'comingMeetings' => $comingMeetings,
            'thisMonthMeetings' => $thisMonthMeetings,
            'totalMeetings' => $totalMeetings
        ])
    </div>

    <!-- Charts Section -->
    <div class="row g-4">
        @if (auth()->user()->hasRole(['admin', 'superadmin']))
            @include('partials.dashboard.meetings-chart', [
                'labels' => $meetingsPerMonthLabels,
                'data' => $meetingsPerMonthData,
                'chartId' => 'meetingsChart',
                'title' => 'Meetings Per Month'
            ])
            @include('partials.dashboard.status-chart', [
                'labels' => $statusLabels,
                'data' => $statusData,
                'chartId' => 'statusChart',
                'title' => 'Meeting Status Distribution'
            ])
        @elseif(auth()->user()->hasRole(['user', 'guest', 'md']))
            @include('partials.dashboard.meetings-chart', [
                'labels' => $userMeetingsPerDayLabels,
                'data' => $userMeetingsPerDayData,
                'chartId' => 'userMeetingsChart',
                'title' => 'तपाईंको संगठनको मिति अनुसार बैठकहरू'
            ])
            @include('partials.dashboard.status-chart', [
                'labels' => $statusLabels,
                'data' => $statusData,
                'chartId' => 'statusChart',
                'title' => 'तपाईंको बैठक स्थिति वितरण'
            ])
        @endif
    </div>
</div>