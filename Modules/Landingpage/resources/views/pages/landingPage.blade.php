@extends('landingpage::layouts/frontMaster')

@php
    $locale = Session::get('locale');
    App::setLocale('np');
@endphp

@section('navbar')
    @include('landingpage::partials.header')
@endsection

@section('content')
<main class="main-content py-3 py-md-3 py-lg-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-12 col-xl-12">
                <div class="card meeting-card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-2 py-md-3">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                            <h2 class="h5 h4-md mb-0 text-white nepali_td">
                                <i class="bx bx-calendar-event me-2"></i>
                                {{ __('field.upcoming_meetings') }}
                            </h2>
                            @if($upcomingMeetings->isNotEmpty())
                                <span class="badge bg-light text-primary rounded-pill px-2 px-md-3">
                                    {{ $upcomingMeetings->total() }} {{ __('field.meetings') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($upcomingMeetings->isNotEmpty())
                            <!-- Large screen table view -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3 ps-lg-4 fw-bold nepali_td">{{ __('field.title') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.date') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.time') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.location') }}</th>
                                            <th class="fw-bold nepali_td d-none d-lg-table-cell">{{ __('field.description') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.meeting_type') }}</th>
                                            <th class="fw-bold nepali_td d-none d-xl-table-cell">{{ __('field.meeting_status') }}</th>
                                            <th class="pe-3 pe-lg-4 text-end">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingMeetings as $meeting)
                                            <tr>
                                                <td class="ps-3 ps-lg-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <div class="avatar-title bg-primary text-white rounded">
                                                                <i class="bx bx-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-primary">{{ $meeting->title }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $meeting->meeting_date }}</td>
                                                <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</td>
                                                <td>
                                                    @if ($meeting->is_virtual)
                                                        <span class="badge bg-purple text-white">{{ __('Virtual') }}</span>
                                                    @else
                                                        {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                                                    @endif
                                                </td>
                                                <td class="d-none d-lg-table-cell">
                                                    <p class="text-muted mb-0 text-truncate" style="max-width: 200px;">
                                                        {{ $meeting->description }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white">
                                                        {{ $meeting->meeting_type }}
                                                    </span>
                                                </td>
                                                <td class="d-none d-xl-table-cell">
                                                    <p class="text-muted mb-0 text-truncate" style="max-width: 200px;">
                                                        {{ $meeting->status }}
                                                    </p>
                                                </td>
                                                <td class="pe-3 pe-lg-4 text-end">
                                                    <a href="{{ route('admin.landingPage.view', $meeting->id) }}"
                                                       class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                       data-bs-toggle="tooltip"
                                                       title="{{ __('View Details') }}">
                                                        <i class="bx bx-show-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Mobile card view -->
                            <div class="d-md-none">
                                @foreach($upcomingMeetings as $meeting)
                                    <div class="meeting-card-mobile p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <div class="avatar-title bg-primary text-white rounded">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                                <h6 class="mb-0 text-primary">{{ $meeting->title }}</h6>
                                            </div>
                                            <span class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white">
                                                {{ $meeting->meeting_type }}
                                            </span>
                                        </div>
                                        <div class="meeting-details ps-4">
                                            <div class="row g-2 mb-2">
                                                <div class="col-5">
                                                    <small class="text-muted nepali_td">{{ __('field.date') }}:</small>
                                                </div>
                                                <div class="col-7">
                                                    <small>{{ $meeting->meeting_date }}</small>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-5">
                                                    <small class="text-muted nepali_td">{{ __('field.time') }}:</small>
                                                </div>
                                                <div class="col-7">
                                                    <small>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</small>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-5">
                                                    <small class="text-muted nepali_td">{{ __('field.location') }}:</small>
                                                </div>
                                                <div class="col-7">
                                                    <small>
                                                        @if ($meeting->is_virtual)
                                                            <span class="badge bg-purple text-white">{{ __('Virtual') }}</span>
                                                        @else
                                                            {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-5">
                                                    <small class="text-muted nepali_td">{{ __('field.status') }}:</small>
                                                </div>
                                                <div class="col-7">
                                                    <small>{{ $meeting->status }}</small>
                                                </div>
                                            </div>
                                            <div class="text-end mt-3">
                                                <a href="{{ route('admin.landingPage.view', $meeting->id) }}"
                                                   class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bx bx-show-alt me-1"></i> {{ __('View') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 py-md-5">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-primary rounded-circle">
                                        <i class="bx bx-calendar-x fs-1"></i>
                                    </div>
                                </div>
                                <h5>{{ __('No Upcoming Meetings') }}</h5>
                                <p class="text-muted">{{ __('There are currently no scheduled meetings.') }}</p>
                            </div>
                        @endif
                    </div>
                    @if ($upcomingMeetings->hasPages())
                        <div class="card-footer bg-light py-2 py-md-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                                <small class="text-center text-md-start">
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
/* Add these styles to your CSS file or include them in a style tag */
@media (max-width: 767.98px) {
    .meeting-card-mobile {
        transition: all 0.3s ease;
    }
    
    .meeting-card-mobile:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .pagination-container .pagination {
        justify-content: center;
    }
}

@media (min-width: 1400px) {
    .container {
        max-width: 1320px;
    }
}

@media (min-width: 1600px) {
    .container {
        max-width: 1480px;
    }
}

@media (min-width: 1920px) {
    .container {
        max-width: 1800px;
    }
    
    .h4-md {
        font-size: 1.75rem;
    }
    
    .avatar-xs {
        width: 2rem;
        height: 2rem;
    }
    
    .table {
        font-size: 1.05rem;
    }
}

/* Custom scrollbar for table responsive on medium screens */
.table-responsive::-webkit-scrollbar {
    height: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #aaa;
}
</style>