@extends('landingpage::layouts/frontMaster')

@php
    $locale = Session::get('locale');
    App::setLocale('np');
@endphp

@section('navbar')
    @include('landingpage::partials.header')
@endsection

@section('content')
<main class="main-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card meeting-card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h2 class="h4 mb-0 text-white nepali_td">
                                <i class="bx bx-calendar-event me-2"></i>
                                {{ __('field.upcoming_meetings') }}
                            </h2>
                            @if($upcomingMeetings->isNotEmpty())
                                <span class="badge bg-light text-primary rounded-pill px-3">
                                    {{ $upcomingMeetings->total() }} {{ __('field.meetings') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($upcomingMeetings->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4 fw-bold nepali_td">{{ __('field.title') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.date') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.time') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.location') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.description') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.meeting_type') }}</th>
                                            <th class="fw-bold nepali_td">{{ __('field.meeting_status') }}</th>
                                            <th class="pe-4 text-end">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingMeetings as $meeting)
                                            <tr>
                                                <td class="ps-4">
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
                                                <td>
                                                    <p class="text-muted mb-0 text-truncate" style="max-width: 200px;">
                                                        {{ $meeting->description }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white">
                                                        {{ $meeting->meeting_type }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <p class="text-muted mb-0 text-truncate" style="max-width: 200px;">
                                                        {{ $meeting->status }}
                                                    </p>
                                                </td>
                                                <td class="pe-4 text-end">
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
                        @else
                            <div class="text-center py-5">
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
                        <div class="card-footer bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small>
                                    {{ __('Showing') }} {{ $upcomingMeetings->firstItem() }} {{ __('to') }} {{ $upcomingMeetings->lastItem() }} {{ __('of') }} {{ $upcomingMeetings->total() }} {{ __('entries') }}
                                </small>
                                {{ $upcomingMeetings->onEachSide(1)->links('pagination::bootstrap-5') }}
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