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
                <div class="card meeting-card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                            <h2 class="h4 mb-0 text-white nepali_td fw-semibold">
                                <i class="bx bx-calendar-event me-2"></i>
                                {{ __('field.upcoming_meetings') }}
                            </h2>
                            @if($upcomingMeetings->isNotEmpty())
                                <span class="badge bg-white text-primary rounded-pill px-3 py-1 fw-medium">
                                    {{ $upcomingMeetings->total() }} {{ __('field.meetings') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($upcomingMeetings->isNotEmpty())
                            <!-- Desktop Table View -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4 fw-semibold nepali_td">{{ __('field.title') }}</th>
                                            <th class="fw-semibold nepali_td">{{ __('field.date') }}</th>
                                            <th class="fw-semibold nepali_td">{{ __('field.time') }}</th>
                                            <th class="fw-semibold nepali_td">{{ __('field.location') }}</th>
                                            <th class="fw-semibold nepali_td d-none d-lg-table-cell">{{ __('field.description') }}</th>
                                            <th class="fw-semibold nepali_td">{{ __('field.meeting_type') }}</th>
                                            <th class="fw-semibold nepali_td d-none d-xl-table-cell">{{ __('field.meeting_status') }}</th>
                                            <th class="pe-4 text-end">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingMeetings as $meeting)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-primary text-white rounded-circle">
                                                                <i class="bx bx-calendar fs-5"></i>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-primary fw-medium">{{ $meeting->title }}</h6>
                                                    </div>
                                                </td>
                                                <td>{{ $meeting->meeting_date }}</td>
                                                <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</td>
                                                <td>
                                                    @if ($meeting->is_virtual)
                                                        <span class="badge bg-purple text-white rounded-pill px-2 py-1">{{ __('Virtual') }}</span>
                                                    @else
                                                        {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                                                    @endif
                                                </td>
                                                <td class="d-none d-lg-table-cell">
                                                    <p class="text-muted mb-0 text-truncate" style="max-width: 250px;">
                                                        {{ $meeting->description }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white rounded-pill px-2 py-1">
                                                        {{ $meeting->meeting_type }}
                                                    </span>
                                                </td>
                                                <td class="d-none d-xl-table-cell">
                                                    <span class="badge {{ $meeting->status === 'Ongoing' ? 'bg-success' : ($meeting->status === 'Scheduled' ? 'bg-warning' : 'bg-secondary') }} text-white rounded-pill px-2 py-1">
                                                        {{ $meeting->status }}
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <a href="{{ route('admin.landingPage.view', $meeting->id) }}"
                                                       class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                       data-bs-toggle="tooltip"
                                                       title="{{ __('View Details') }}">
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
                                @foreach($upcomingMeetings as $meeting)
                                    <div class="meeting-card-mobile p-4 border-bottom bg-white hover-bg-light">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title bg-primary text-white rounded-circle">
                                                        <i class="bx bx-calendar fs-5"></i>
                                                    </div>
                                                </div>
                                                <h6 class="mb-0 text-primary fw-medium">{{ $meeting->title }}</h6>
                                            </div>
                                            <span class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white rounded-pill px-2 py-1">
                                                {{ $meeting->meeting_type }}
                                            </span>
                                        </div>
                                        <div class="meeting-details ps-4">
                                            <div class="row g-3 mb-2">
                                                <div class="col-5">
                                                    <small class="text-muted nepali_td fw-medium">{{ __('field.date') }}:</small>
                                                </div>
                                                <div class="col-7">
                                                    <small>{{ $meeting->meeting_date }}</small>
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-2">
                                                <div class="col-5">
                                                    <small class="text-muted nepali_td fw-medium">{{ __('field.time') }}:</small>
                                                </div>
                                                <div class="col-7">
                                                    <small>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</small>
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-2">
                                                <div class="col-5">
                                                    <small class="text-muted nepali_td fw-medium">{{ __('field.location') }}:</small>
                                                </div>
                                                <div class="col-7">
                                                    <small>
                                                        @if ($meeting->is_virtual)
                                                            <span class="badge bg-purple text-white rounded-pill px-2 py-1">{{ __('Virtual') }}</span>
                                                        @else
                                                            {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-5">
                                                    <small class="text-muted nepali_td fw-medium">{{ __('field.status') }}:</small>
                                                </div>
                                                <div class="col-7">
                                                    <small>
                                                        <span class="badge {{ $meeting->status === 'Ongoing' ? 'bg-success' : ($meeting->status === 'Scheduled' ? 'bg-warning' : 'bg-secondary') }} text-white rounded-pill px-2 py-1">
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
                    @if ($upcomingMeetings->hasPages())
                        <div class="card-footer bg-light py-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                <small class="text-center text-md-start text-muted">
                                    {{ __('Showing') }} {{ $upcomingMeetings->firstItem() }} {{ __('to') }} {{ $upcomingMeetings->lastItem() }} {{ __('of') }} {{ $upcomingMeetings->total() }} {{ __('entries') }}
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

<style>
/* General Styles */
body {
    font-family: 'Inter', sans-serif;
    background-color: #f5f7fa;
}

.main-content {
    min-height: calc(100vh - 200px);
}

.container {
    max-width: 1400px;
}

/* Card Styles */
.meeting-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.meeting-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.card-header.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 1rem 1rem 0 0;
}

.card-header h2 {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Table Styles */
.table {
    font-size: 0.95rem;
    border-collapse: separate;
    border-spacing: 0;
}

.table th,
.table td {
    vertical-align: middle;
    padding: 1rem;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f1f5f9;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
    transition: transform 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

.badge.bg-purple {
    background-color: #6f42c1;
}

.badge.bg-success {
    background-color: #28a745;
}

.badge.bg-warning {
    background-color: #ffc107;
}

.badge.bg-info {
    background-color: #17a2b8;
}

.badge.bg-secondary {
    background-color: #6c757d;
}

/* Button Styles */
.btn-outline-primary {
    border-width: 2px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

/* Mobile Card View */
.meeting-card-mobile {
    transition: all 0.3s ease;
}

.meeting-card-mobile:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}

.meeting-details small {
    font-size: 0.9rem;
}

.meeting-details .row {
    align-items: center;
}

/* No Meetings Section */
.text-center .avatar-lg {
    width: 80px;
    height: 80px;
}

.text-center h5 {
    font-weight: 600;
    color: #343a40;
}

.text-center p {
    font-size: 1rem;
    color: #6c757d;
}

/* Pagination */
.pagination-container .pagination {
    justify-content: center;
}

.page-link {
    border-radius: 0.5rem;
    margin: 0 3px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.page-link:hover {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Custom Scrollbar */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f3f5;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #adb5bd;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #6c757d;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .meeting-card-mobile {
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .pagination-container .pagination {
        flex-wrap: wrap;
    }

    .page-item {
        margin-bottom: 0.5rem;
    }
}

@media (min-width: 1400px) {
    .container {
        max-width: 1480px;
    }
}

@media (min-width: 1920px) {
    .container {
        max-width: 1800px;
    }

    .table {
        font-size: 1.1rem;
    }

    .avatar-xs {
        width: 2.5rem;
        height: 2.5rem;
    }

    .h4 {
        font-size: 1.75rem;
    }
}
</style>