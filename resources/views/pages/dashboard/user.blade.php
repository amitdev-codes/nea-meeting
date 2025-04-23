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
                            @include('partials.tab-navigation', [
                                'upcomingMeetings' => $dashboardData['upcomingMeetings']
                            ])
                        </div>

                        <!-- Tab Content -->
                        <div class="card-body p-0 tab-content" id="myTabContent">
                            @include('pages.dashboard.tabs.meetings-tab', [
                                'upcomingMeetings' => $dashboardData['upcomingMeetings']
                            ])
                            @include('pages.dashboard.tabs.dashboard-tab', $dashboardData)
                            @include('pages.dashboard.tabs.calendar-tab')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer')
    @include('landingpage::partials.footer')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush
@push('scripts')
    <script type="module">
        window.meetingsPerMonthLabels = @json($dashboardData['meetingsPerMonthLabels'] ?? []);
        window.meetingsPerMonthData = @json($dashboardData['meetingsPerMonthData'] ?? []);
        window.statusLabels = @json($dashboardData['statusLabels'] ?? ['No Data']);
        window.statusData = @json($dashboardData['statusData'] ?? [0]);
        window.userMeetingsPerDayLabels = @json($dashboardData['userMeetingsPerDayLabels'] ?? []);
        window.userMeetingsPerDayData = @json($dashboardData['userMeetingsPerDayData'] ?? []);
    </script>
    <script src="{{ asset('js/dashboard.js') }}" type="module"></script>
@endpush
