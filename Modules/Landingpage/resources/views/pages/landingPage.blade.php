@extends('landingpage::layouts/frontMaster')

@php
    $locale = Session::get('locale');
    App::setLocale('np');
@endphp

@section('navbar')
    <div class="bg-white shadow-sm">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    <img src="{{ asset('assets/img/nea-logo.png') }}" alt="Government Logo" width="full" class="img-fluid"
                        style="
                margin-left: 171%;
            ">
                </div>
                <div class="col-md-8 text-center">
                    <h3 class="text-uppercase fw-bold text-danger mb-1">{{ __('landing.government of nepal') }}</h3><br>
                    <h2 class="fw-bold text-danger mb-1">{{ __('landing.nepal electricity authority') }}</h2><br>
                    <h4 class="fw-bold text-success">{{ __('landing.meeting management system') }}</h4>
                </div>


                <div class="col-md-2 text-center text-md-end">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-log-in me-1"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 fw-bold text-white"><i class="bx bx-calendar-event me-2"></i> Upcoming Meetings</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Title</th>
                                        <th>Meeting Date</th>
                                        <th>Meeting Start Time</th>
                                        <th>Meeting Location</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th class="pe-4 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($meetings as $meeting)
                                        <tr class="align-middle">

                                            <td class="ps-4">
                                                <strong class="text-primary">{{ $meeting->title }}</strong>
                                            </td>

                                            <td>{{ $meeting->meeting_date }}</td>

                                            <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</td>

                                            <td>
                                                @if ($meeting->is_virtual)
                                                    <span class="badge bg-purple">Virtual</span>
                                                @else
                                                    {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                                                @endif
                                            </td>
                                            <td>{{ $meeting->description }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'warning' }}">
                                                    {{ $meeting->meeting_type }}
                                                </span>
                                            </td>

                                            <td class="pe-4 text-end">
                                                <a href="{{ route('admin.landingPage.view', $meeting->id) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bx bx-show-alt me-1"></i> Details
                                                </a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="py-5">
                                                    <i class="bx bx-calendar-x bx-lg text-muted mb-3"></i>
                                                    <p class="text-muted">No upcoming meetings scheduled</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($meetings->hasPages())
                            <div class="card-footer bg-light border-top py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Showing {{ $meetings->firstItem() }} to {{ $meetings->lastItem() }} of
                                        {{ $meetings->total() }} entries
                                    </div>
                                    <div>
                                        {{ $meetings->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<link href="https://fonts.googleapis.com/css2?family=Kalimati&display=swap" rel="stylesheet">
@push('styles')
    <style>
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .badge.bg-purple {
            background-color: #6f42c1;
        }

        .scrollspy-example {
            position: relative;
            height: auto;
            overflow: auto;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>
@endpush
