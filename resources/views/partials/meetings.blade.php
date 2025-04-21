<?php use App\Helpers\NepaliDateConverter; ?>
@extends('landingpage::layouts/frontMaster')

@php
    $locale = Session::get('locale');
    App::setLocale('np');
@endphp

@section('navbar')
    @include('landingpage::partials.header')
@endsection

@section('content')
    <main class="main-content py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card meeting-card shadow-lg border-1 rounded-4 overflow-hidden">
                        <!-- Tab Navigation -->
                        <div class="card-header bg-gradient-primary text-white py-3">
                            <ul class="nav nav-tabs card-header-tabs mb-0" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="meetings-tab" data-bs-toggle="tab"
                                        data-bs-target="#meetings" type="button" role="tab" aria-controls="meetings"
                                        aria-selected="true">
                                        <i class="bx bx-calendar-event me-2"></i>
                                        {{ __('field.upcoming_meetings') }}
                                        @if ($upcomingMeetings->isNotEmpty())
                                            <span class="badge bg-white text-primary rounded-pill ms-2">
                                                {{ $upcomingMeetings->total() }}
                                            </span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="dashboard-tab" data-bs-toggle="tab"
                                        data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard"
                                        aria-selected="false">
                                        <i class="bx bx-bar-chart-alt-2 me-2"></i>
                                        {{ __('field.dashboard') }}
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Tab Content -->
                        <div class="card-body p-0 tab-content" id="myTabContent">
                            <!-- Meetings Tab -->
                            <div class="tab-pane fade show active" id="meetings" role="tabpanel"
                                aria-labelledby="meetings-tab">
                                @if ($upcomingMeetings->isNotEmpty())
                                    <!-- Desktop Table View -->
                                    <div class="table-responsive d-none d-md-block">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4 fw-semibold nepali_td">{{ __('field.title') }}</th>
                                                    <th class="fw-semibold nepali_td">{{ __('field.date') }}</th>
                                                    <th class="fw-semibold nepali_td">{{ __('field.time') }}</th>
                                                    <th class="fw-semibold nepali_td">{{ __('field.location') }}</th>
                                                    <th class="fw-semibold nepali_td d-none d-lg-table-cell">
                                                        {{ __('field.description') }}</th>
                                                    <th class="fw-semibold nepali_td">{{ __('field.meeting_type') }}</th>
                                                    <th class="fw-semibold nepali_td d-none d-xl-table-cell">
                                                        {{ __('field.meeting_status') }}</th>
                                                    <th class="pe-4 text-end">{{ __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($upcomingMeetings as $meeting)
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="avatar-xs">
                                                                    <div
                                                                        class="avatar-title bg-primary text-white rounded-circle">
                                                                        <i class="bx bx-calendar fs-5"></i>
                                                                    </div>
                                                                </div>
                                                                <h6 class="mb-0 text-primary fw-medium">
                                                                    {{ $meeting->title }}</h6>
                                                            </div>
                                                        </td>
                                                        <td>{{ $meeting->meeting_date }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}
                                                        </td>
                                                        <td>
                                                            @if ($meeting->is_virtual)
                                                                <span
                                                                    class="badge bg-purple text-white rounded-pill px-2 py-1">{{ __('Virtual') }}</span>
                                                            @else
                                                                {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                                                            @endif
                                                        </td>
                                                        <td class="d-none d-lg-table-cell">
                                                            <p class="text-muted mb-0 text-truncate"
                                                                style="max-width: 250px;">
                                                                {{ $meeting->description }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white rounded-pill px-2 py-1">
                                                                {{ $meeting->meeting_type }}
                                                            </span>
                                                        </td>
                                                        <td class="d-none d-xl-table-cell">
                                                            <span
                                                                class="badge {{ $meeting->status === 'Ongoing' ? 'bg-success' : ($meeting->status === 'Scheduled' ? 'bg-warning' : 'bg-secondary') }} text-white rounded-pill px-2 py-1">
                                                                {{ $meeting->status }}
                                                            </span>
                                                        </td>
                                                        <td class="pe-4 text-end">
                                                            <a href="{{ route('admin.landingPage.view', $meeting->id) }}"
                                                                class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                                data-bs-toggle="tooltip" title="{{ __('View Details') }}">
                                                                <i class="bx bx-show-alt fs-5"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile Card View -->
                                    <div class="d-md-none">
                                        @foreach ($upcomingMeetings as $meeting)
                                            <div class="meeting-card-mobile p-4 border-bottom bg-white hover-bg-light">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-primary text-white rounded-circle">
                                                                <i class="bx bx-calendar fs-5"></i>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-primary fw-medium">{{ $meeting->title }}
                                                        </h6>
                                                    </div>
                                                    <span
                                                        class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white rounded-pill px-2 py-1">
                                                        {{ $meeting->meeting_type }}
                                                    </span>
                                                </div>
                                                <div class="meeting-details ps-4">
                                                    <div class="row g-3 mb-2">
                                                        <div class="col-5">
                                                            <small
                                                                class="text-muted nepali_td fw-medium">{{ __('field.date') }}:</small>
                                                        </div>
                                                        <div class="col-7">
                                                            <small>{{ $meeting->meeting_date }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 mb-2">
                                                        <div class="col-5">
                                                            <small
                                                                class="text-muted nepali_td fw-medium">{{ __('field.time') }}:</small>
                                                        </div>
                                                        <div class="col-7">
                                                            <small>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 mb-2">
                                                        <div class="col-5">
                                                            <small
                                                                class="text-muted nepali_td fw-medium">{{ __('field.location') }}:</small>
                                                        </div>
                                                        <div class="col-7">
                                                            <small>
                                                                @if ($meeting->is_virtual)
                                                                    <span
                                                                        class="badge bg-purple text-white rounded-pill px-2 py-1">{{ __('Virtual') }}</span>
                                                                @else
                                                                    {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-5">
                                                            <small
                                                                class="text-muted nepali_td fw-medium">{{ __('field.status') }}:</small>
                                                        </div>
                                                        <div class="col-7">
                                                            <small>
                                                                <span
                                                                    class="badge {{ $meeting->status === 'Ongoing' ? 'bg-success' : ($meeting->status === 'Scheduled' ? 'bg-warning' : 'bg-secondary') }} text-white rounded-pill px-2 py-1">
                                                                    {{ $meeting->status }}
                                                                </span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="text-end mt-3">
                                                        <a href="{{ route('admin.landingPage.view', $meeting->id) }}"
                                                            class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                                            <i class="bx bx-show-alt me-1"></i> {{ __('View') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-light text-primary rounded-circle">
                                                <i class="bx bx-calendar-x fs-1"></i>
                                            </div>
                                        </div>
                                        <h5 class="fw-semibold">{{ __('No Upcoming Meetings') }}</h5>
                                        <p class="text-muted">{{ __('There are currently no scheduled meetings.') }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Dashboard Tab -->
                            <div class="tab-pane fade" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                                <!-- Total Counts Section -->
                                <div class="container-xxl py-4">
                                    <div class="row g-4 mb-4">
                                        <!-- Today's Meetings -->
                                        <div class="col-md-3">
                                            <div class="card bg-primary text-white shadow-sm">
                                                <div class="card-body d-flex align-items-center">
                                                    <i class="bx bx-calendar-event bx-md me-3"></i>
                                                    <div>
                                                        <h5 class="card-title text-white mb-1">आजका बैठकहरू</h5>
                                                        <h2 class="mb-0 text-white">
                                                            {{ NepaliDateConverter::toNepaliDigits($todaysMeetings) }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Upcoming Meetings -->
                                        <div class="col-md-3">
                                            <div class="card bg-success text-white shadow-sm">
                                                <div class="card-body d-flex align-items-center">
                                                    <i class="bx bx-time-five bx-md me-3"></i>
                                                    <div>
                                                        <h5 class="card-title text-white mb-1">आगामी बैठकहरू</h5>
                                                        <h2 class="mb-0 text-white">
                                                            {{ NepaliDateConverter::toNepaliDigits($comingMeetings) }}
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- This Month Meetings -->
                                        <div class="col-md-3">
                                            <div class="card bg-info text-white shadow-sm">
                                                <div class="card-body d-flex align-items-center">
                                                    <i class="bx bx-calendar-month bx-md me-3"></i>
                                                    <div>
                                                        <h5 class="card-title text-white mb-1">यो महिनाका बैठकहरू</h5>
                                                        <h2 class="mb-0 text-white">
                                                            {{ NepaliDateConverter::toNepaliDigits($thisMonthMeetings) }}
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Total Meetings -->
                                        <div class="col-md-3">
                                            <div class="card bg-warning text-white shadow-sm">
                                                <div class="card-body d-flex align-items-center">
                                                    <i class="bx bx-list-ul bx-md me-3"></i>
                                                    <div>
                                                        <h5 class="card-title text-white mb-1">कुल बैठकहरू</h5>
                                                        <h2 class="mb-0 text-white">
                                                            {{ NepaliDateConverter::toNepaliDigits($totalMeetings) }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Charts Section -->
                                <!-- Charts Section -->
                                <div class="row g-4">
                                    @if (auth()->user()->hasRole(['admin', 'superadmin']))
                                        <!-- Meetings Per Month Bar Chart for Admin/Superadmin -->
                                        <div class="col-md-6">
                                            <div class="card chart-card">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Meetings Per Month</h5>
                                                </div>
                                                <div class="card-body">
                                                    @if (!empty($meetingsPerMonthLabels))
                                                        <canvas id="meetingsChart" class="chart-canvas" width="400"
                                                            height="300"></canvas>
                                                    @else
                                                        <p class="text-center text-muted">No meeting data available for
                                                            this period.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Meeting Status Pie Chart for Admin/Superadmin -->
                                        <div class="col-md-6">
                                            <div class="card chart-card">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Meeting Status Distribution</h5>
                                                </div>
                                                <div class="card-body">
                                                    @if (!empty($statusLabels))
                                                        <canvas id="statusChart" class="chart-canvas" width="400"
                                                            height="300"></canvas>
                                                    @else
                                                        <p class="text-center text-muted">No status data available.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(auth()->user()->hasRole(['user', 'guest', 'md']))
                                        <!-- Meetings Per Date Bar Chart for User -->
                                        <div class="col-md-6">
                                            <div class="card chart-card">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">तपाईंको संगठनको मिति अनुसार बैठकहरू</h5>
                                                </div>
                                                <div class="card-body">
                                                    @if (!empty($userMeetingsPerDayLabels))
                                                        <canvas id="userMeetingsChart" class="chart-canvas"
                                                            width="400" height="300"></canvas>
                                                    @else
                                                        <p class="text-center text-muted">तपाईंको संगठनको लागि कुनै बैठकहरू
                                                            फेला परेन।</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Meeting Status Pie Chart for User -->
                                        <div class="col-md-6">
                                            <div class="card chart-card">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">तपाईंको बैठक स्थिति वितरण</h5>
                                                </div>
                                                <div class="card-body">
                                                    @if (!empty($statusLabels))
                                                        <canvas id="statusChart" class="chart-canvas" width="400"
                                                            height="300"></canvas>
                                                    @else
                                                        <p class="text-center text-muted">No status data available.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Pagination for Meetings Tab -->
                        @if ($upcomingMeetings->hasPages())
                            <div class="card-footer bg-light py-3">
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                    <small class="text-center text-md-start text-muted">
                                        {{ __('Showing') }} {{ $upcomingMeetings->firstItem() }} {{ __('to') }}
                                        {{ $upcomingMeetings->lastItem() }} {{ __('of') }}
                                        {{ $upcomingMeetings->total() }} {{ __('entries') }}
                                    </small>
                                    <div class="pagination-container">
                                        {{ $upcomingMeetings->onEachSide(1)->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer')
    @include('landingpage::partials.footer')
@endsection

@push('scripts')
    <script type="module">
        // Fallback for empty data
        const meetingsPerMonthLabels = @json($meetingsPerMonthLabels ?? []);
        const meetingsPerMonthData = @json($meetingsPerMonthData ?? []);
        const statusLabels = @json($statusLabels ?? ['No Data']);
        const statusData = @json($statusData ?? [0]);
        const userMeetingsPerDayLabels = @json($userMeetingsPerDayLabels ?? []);
        const userMeetingsPerDayData = @json($userMeetingsPerDayData ?? []);
        // Meetings Per Day Chart for User
        new Chart(document.getElementById('userMeetingsChart'), {
            type: 'bar',
            data: {
                labels: userMeetingsPerDayLabels.length ? userMeetingsPerDayLabels : ['No Data'],
                datasets: [{
                    label: 'बैठकहरूको संख्या',
                    data: userMeetingsPerDayData.length ? userMeetingsPerDayData : [0],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                maintainAspectRatio: false,
                responsive: true
            }
        });

        // Meeting Status Chart for User
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true
            }
        });
    </script>
@endpush
<style>

        .nav-link {
            font-weight: bold; /* Makes all tab headers bold */
            color: #fff; /* Ensures text is visible on the gradient background */
        }

        /* Style for the active tab */
        .nav-link.active {
            background-color: rgba(0, 0, 0, 0.3) !important; /* Darker background for active tab */
            font-weight: bold; /* Reinforce bold font for active tab */
            color: #fff !important; /* White text for contrast */
        }
    .nav-tabs .nav-link {
        color: #fff;
        background: transparent;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        background: #fff;
        color: #007bff;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .nav-tabs .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .chart-placeholder {
        position: relative;
        width: 100%;
    }

    .chart-placeholder canvas {
        max-height: 200px;
    }

    .hover-bg-light:hover {
        background-color: #f1f5f9 !important;
    }

    /* Ensure tab content has proper padding */
    .tab-content .tab-pane {
        background: #fff;
    }

    /* Responsive adjustments for Dashboard */
    @media (max-width: 767.98px) {
        .nav-tabs .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .chart-placeholder canvas {
            max-height: 150px;
        }
    }
</style>
