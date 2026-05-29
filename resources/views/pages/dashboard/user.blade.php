<?php use App\Helpers\NepaliDateConverter; ?>
@extends('landingpage::layouts/frontMaster')
@section('page-style')
    <style>
        .modal-content {
            border-radius: 10px;
            overflow: hidden;
            max-width: 100%;
            margin: 0 auto;
        }

        .modal-dialog {
            max-width: 90vw;
            margin: 1rem auto;
        }

        .modal-header {
            border-bottom: none;
            padding: 1rem 1.5rem;
        }

        .nav-tabs {
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 0;
            padding: 0 1rem;
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            padding: 0.75rem 1rem;
            color: #333;
            font-weight: 600;
            white-space: nowrap;
            flex: 1;
            text-align: center;
        }

        .nav-tabs .nav-link.active {
            background-color: transparent;
            border-bottom: 3px solid #0074d9;
            color: #0074d9;
        }

        .nav-tabs .nav-link:hover {
            color: #0074d9;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0062cc 0%, #0096ff 100%);
        }

        .detail-item label {
            font-weight: 600;
            color: #444;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .detail-icon {
            font-size: 1.1rem;
            color: #0074d9;
        }

        .detail-item p,
        .detail-item .form-control-plaintext {
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 6px;
            color: #333;
            font-size: 0.9rem;
            margin: 0;
            word-break: break-word;
        }

        .date-time-container {
            background-color: #f0f7ff;
            border-radius: 8px;
            border-left: 3px solid #0074d9;
            padding: 1rem;
        }

        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            padding: 0.5rem;
        }

        .document-item {
            position: relative;
        }

        .document-preview {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .document-preview:hover {
            border-color: #0074d9;
            box-shadow: 0 4px 12px rgba(0, 116, 217, 0.1);
            transform: translateY(-2px);
        }

        .file-icon {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .file-icon i {
            font-size: 1.5rem;
        }

        .pdf-icon {
            background-color: rgba(255, 0, 0, 0.1);
        }

        .pdf-icon i {
            color: #e53935;
        }

        .doc-icon {
            background-color: rgba(25, 118, 210, 0.1);
        }

        .doc-icon i {
            color: #1976d2;
        }

        .img-icon {
            background-color: rgba(76, 175, 80, 0.1);
        }

        .thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .generic-icon {
            background-color: rgba(158, 158, 158, 0.1);
        }

        .generic-icon i {
            color: #757575;
        }

        .document-info {
            flex-grow: 1;
            min-width: 0;
        }

        .document-name {
            font-size: 0.85rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .document-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .view-btn {
            background-color: rgba(0, 116, 217, 0.1);
            color: #0074d9;
        }

        .view-btn:hover {
            background-color: #0074d9;
            color: #ffffff;
        }

        .download-btn {
            background-color: rgba(76, 175, 80, 0.1);
            color: #4caf50;
        }

        .download-btn:hover {
            background-color: #4caf50;
            color: #ffffff;
        }

        .empty-documents {
            text-align: center;
            padding: 2rem 0;
        }

        .empty-documents i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 0.5rem;
            display: block;
        }

        .empty-documents p {
            color: #888;
            margin: 0;
            font-size: 0.9rem;
        }

        .view-all-btn {
            background: #0074d9;
            border-color: #0074d9;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .view-all-btn:hover {
            background: #005bb5;
            border-color: #005bb5;
        }

        .detail-item a.text-primary {
            text-decoration: none;
        }

        .detail-item a.text-primary:hover {
            text-decoration: underline;
        }

        .google-calendar-pane {
            background: #f7f9fc;
            padding: 1.5rem;
        }

        .google-calendar-panel {
            align-items: center;
            background: #fff;
            border: 1px solid #e4e9f2;
            border-radius: 8px;
            display: flex;
            gap: 1.5rem;
            justify-content: space-between;
            padding: 1.5rem;
        }

        .google-calendar-status {
            align-items: center;
            display: flex;
            gap: 1rem;
            min-width: 0;
        }

        .google-calendar-icon {
            align-items: center;
            border-radius: 8px;
            display: inline-flex;
            flex: 0 0 58px;
            height: 58px;
            justify-content: center;
            width: 58px;
        }

        .google-calendar-icon i {
            font-size: 2rem;
        }

        .google-calendar-icon.is-connected {
            background: #eaf7ef;
            color: #188038;
        }

        .google-calendar-icon.is-disconnected {
            background: #eef5ff;
            color: #1a73e8;
        }

        .connection-badge {
            align-items: center;
            border-radius: 999px;
            display: inline-flex;
            font-size: 0.78rem;
            font-weight: 700;
            gap: 0.35rem;
            line-height: 1;
            padding: 0.45rem 0.7rem;
        }

        .connection-badge.connected {
            background: #eaf7ef;
            color: #188038;
        }

        .connection-badge.disconnected {
            background: #eef5ff;
            color: #1a73e8;
        }

        .google-calendar-actions {
            flex: 0 0 auto;
        }

        .google-calendar-actions .btn {
            align-items: center;
            display: inline-flex;
            font-weight: 700;
            justify-content: center;
            min-height: 42px;
            white-space: nowrap;
        }

        .google-calendar-helper {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-top: 1rem;
        }

        .helper-item {
            align-items: center;
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 8px;
            color: #5f6b7a;
            display: flex;
            font-size: 0.9rem;
            gap: 0.65rem;
            padding: 0.85rem 1rem;
        }

        .helper-item i {
            color: #0074d9;
            font-size: 1.2rem;
        }

        /* Mobile Responsiveness */
        @media (max-width: 767.98px) {
            .modal-dialog {
                max-width: 95vw;
                margin: 0.5rem auto;
            }

            .modal-body {
                padding: 0 !important;
            }

            .nav-tabs {
                padding: 0 0.5rem;
                font-size: 0.85rem;
            }

            .nav-tabs .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }

            .documents-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .document-preview {
                padding: 0.5rem;
            }

            .file-icon {
                width: 40px;
                height: 40px;
            }

            .file-icon i {
                font-size: 1.25rem;
            }

            .document-name {
                font-size: 0.8rem;
            }

            .action-btn {
                width: 28px;
                height: 28px;
            }

            .date-time-container {
                padding: 0.75rem;
            }

            .detail-item label {
                font-size: 0.85rem;
            }

            .detail-item p,
            .detail-item .form-control-plaintext {
                font-size: 0.85rem;
                padding: 0.4rem;
            }

            .modal-header {
                padding: 0.75rem 1rem;
            }

            .modal-footer {
                padding: 0.75rem;
            }

            .google-calendar-pane {
                padding: 1rem;
            }

            .google-calendar-panel {
                align-items: stretch;
                flex-direction: column;
            }

            .google-calendar-actions .btn,
            .google-calendar-actions form {
                width: 100%;
            }

            .google-calendar-helper {
                grid-template-columns: 1fr;
            }
        }
        .google-calendar-pane {
            padding: 1.5rem;
            background: #f5f7fb;
        }

        /* Main Card */
        .google-calendar-panel {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid rgba(67, 89, 113, 0.08);
            box-shadow:
                0 2px 6px rgba(67, 89, 113, 0.04),
                0 8px 24px rgba(67, 89, 113, 0.06);
            padding: 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            transition: all 0.25s ease;
        }

        .google-calendar-panel:hover {
            transform: translateY(-2px);
            box-shadow:
                0 4px 12px rgba(67, 89, 113, 0.08),
                0 14px 32px rgba(67, 89, 113, 0.10);
        }

        /* Left Content */
        .google-calendar-status {
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            flex: 1;
            min-width: 0;
        }

        /* Icon */
        .google-calendar-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
        }

        .google-calendar-icon i {
            font-size: 2rem;
        }

        .google-calendar-icon.is-connected {
            background: rgba(113, 221, 55, 0.12);
            color: #71dd37;
        }

        .google-calendar-icon.is-disconnected {
            background: rgba(105, 108, 255, 0.12);
            color: #696cff;
        }

        /* Typography */
        .google-calendar-content h5 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #566a7f;
            margin-bottom: 0.45rem;
        }

        .google-calendar-content p {
            font-size: 0.92rem;
            line-height: 1.65;
            color: #8592a3;
            margin-bottom: 0;
            max-width: 640px;
        }

        /* Status Badge */
        .connection-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .connection-badge.connected {
            background: rgba(113, 221, 55, 0.16);
            color: #5aa52a;
        }

        .connection-badge.disconnected {
            background: rgba(105, 108, 255, 0.14);
            color: #696cff;
        }

        /* Button Area */
        .google-calendar-actions {
            flex-shrink: 0;
        }

        .google-calendar-actions .btn {
            min-width: 220px;
            height: 46px;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.2s ease;
        }

        .google-calendar-actions .btn-primary {
            background: #696cff;
            border-color: #696cff;
            box-shadow: 0 4px 14px rgba(105, 108, 255, 0.25);
        }

        .google-calendar-actions .btn-primary:hover {
            background: #5f62e8;
            border-color: #5f62e8;
            transform: translateY(-1px);
        }

        .google-calendar-actions .btn-outline-danger {
            border-width: 1.5px;
        }

        /* Helper Grid */
        .google-calendar-helper {
            margin-top: 1.25rem;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .helper-item {
            background: #fff;
            border: 1px solid rgba(67, 89, 113, 0.08);
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            transition: all 0.2s ease;
        }

        .helper-item:hover {
            border-color: rgba(105, 108, 255, 0.25);
            background: rgba(105, 108, 255, 0.02);
        }

        .helper-item i {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(105, 108, 255, 0.12);
            color: #696cff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .helper-item span {
            color: #6b7a8c;
            font-size: 0.88rem;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .google-calendar-panel {
                flex-direction: column;
                align-items: stretch;
            }

            .google-calendar-actions {
                width: 100%;
            }

            .google-calendar-actions .btn,
            .google-calendar-actions form {
                width: 100%;
            }

            .google-calendar-helper {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .google-calendar-pane {
                padding: 1rem;
            }

            .google-calendar-panel {
                padding: 1.25rem;
                border-radius: 1rem;
            }

            .google-calendar-status {
                flex-direction: column;
                align-items: flex-start;
            }

            .google-calendar-icon {
                width: 56px;
                height: 56px;
                border-radius: 16px;
            }

            .google-calendar-content h5 {
                font-size: 1rem;
            }

            .google-calendar-content p {
                font-size: 0.86rem;
            }
        }

        @media (max-width: 575.98px) {
            .modal-dialog {
                margin: 0;
                max-width: 100vw;
                height: 100vh;
            }

            .modal-content {
                border-radius: 0;
                height: 100%;
            }

            .documents-grid {
                padding: 0.25rem;
            }
        }

    </style>
@endsection

@php
    $locale = Session::get('locale');
    App::setLocale($locale);
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
                                'upcomingMeetings' => $dashboardData['upcomingMeetings'],
                            ])
                        </div>

                        <!-- Tab Content -->
                        <div class="card-body p-0 tab-content" id="myTabContent">
                            @include('pages.dashboard.tabs.meetings-tab', [
                                'upcomingMeetings' => $dashboardData['upcomingMeetings'],
                            ])
                            @include('pages.dashboard.tabs.dashboard-tab', $dashboardData)
                            @include('pages.dashboard.tabs.calendar-tab')

                            <div class="tab-pane fade" id="google-calendar" role="tabpanel"
                                 aria-labelledby="google-calendar-tab">

                                <div class="google-calendar-pane">
                                    <!-- Main Integration Card -->
                                    <div class="google-calendar-panel">
                                        <div class="google-calendar-status">
                                            <!-- Google Icon -->
                                            <span class="google-calendar-icon {{ auth()->user()->google_access_token ? 'is-connected' : 'is-disconnected' }}">
                                              <i class="bx bxl-google"></i>
                                             </span>

                                            <!-- Content -->
                                            <div class="google-calendar-content">
                                                <span class="connection-badge {{ auth()->user()->google_access_token ? 'connected' : 'disconnected' }}">
                                                    <i class="bx {{ auth()->user()->google_access_token ? 'bx-check-circle' : 'bx-link-alt' }}"></i>
                                                    {{ auth()->user()->google_access_token ? __('Connected') : __('Not connected') }}
                                                </span>

                                                <h5 class="mt-3 mb-2">
                                                    {{ __('Google Calendar Integration') }}
                                                </h5>

                                                <p>
                                                    {{ __('Connect your Google Calendar to automatically sync meetings, schedules, reminders, and updates directly with your workspace calendar.') }}
                                                </p>

                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="google-calendar-actions">

                                            @if(auth()->user()->google_access_token)
                                                <form method="POST" action="{{ route('google.disconnect') }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        <i class="bx bx-unlink"></i>
                                                        {{ __('Disconnect Calendar') }}
                                                    </button>
                                                </form>

                                            @else

                                                <a href="{{ route('google.auth') }}" class="btn btn-primary">
                                                    <i class="bx bx-link"></i>
                                                    {{ __('Connect Google Calendar') }}
                                                </a>

                                            @endif

                                        </div>

                                    </div>

                                    <!-- Helper Cards -->
                                    <div class="google-calendar-helper">

                                        <div class="helper-item">
                                            <i class="bx bx-sync"></i>

                                            <span>
                    {{ __('Meetings and schedules are automatically synced with your Google Calendar after connection.') }}
                </span>
                                        </div>

                                        <div class="helper-item">
                                            <i class="bx bx-bell"></i>

                                            <span>
                    {{ __('Receive Google Calendar reminders and notifications for upcoming meetings.') }}
                </span>
                                        </div>

                                        <div class="helper-item">
                                            <i class="bx bx-shield-quarter"></i>

                                            <span>
                    {{ __('Your calendar access remains secure and can be disconnected anytime.') }}
                </span>
                                        </div>

                                        <div class="helper-item">
                                            <i class="bx bx-devices"></i>

                                            <span>
                    {{ __('Access your synchronized meetings seamlessly across mobile and desktop devices.') }}
                </span>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Meeting Details -->
            <div class="modal fade" id="meetingModal" tabindex="-1" aria-labelledby="meetingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title" id="meetingModalLabel">{{ __('Meeting Details') }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs" id="meetingTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab"
                                        data-bs-target="#info" type="button" role="tab" aria-controls="info"
                                        aria-selected="true">{{ __('field.meeting_information') }}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab"
                                        data-bs-target="#documents" type="button" role="tab" aria-controls="documents"
                                        aria-selected="false">{{ __('field.meeting_documents') }}</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="meetingTabContent">
                                <!-- Meeting Information Tab -->
                                <div class="tab-pane fade show active" id="info" role="tabpanel"
                                    aria-labelledby="info-tab">
                                    <div class="p-3 p-md-4">
                                        <div class="row gy-3 nepali_td">
                                            <x-resource.detail-items label="{{ __('field.title') }}" id="meeting-title"
                                                class="col-12">
                                                <i class="bx bx-calendar me-2 detail-icon"></i>
                                            </x-resource.detail-items>
                                            <x-resource.detail-items label="{{ __('field.meeting_location') }}"
                                                id="meeting-location" class="col-12">
                                                <i class="bx bx-map me-2 detail-icon"></i>
                                            </x-resource.detail-items>


                                            <x-resource.detail-items label="{{ __('field.meeting_date') }}"
                                                id="meeting-date" class="col-12 col-md-12">
                                                <i class="bx bx-calendar me-2 detail-icon"></i>
                                            </x-resource.detail-items>

                                            <x-resource.detail-items label="{{ __('field.start_time') }}" id="start-time"
                                                class="col-12 col-md-12">
                                                <i class="bx bx-time me-2 detail-icon"></i>
                                            </x-resource.detail-items>
                                            <x-resource.detail-items label="{{ __('field.end_time') }}" id="end-time"
                                                class="col-12 col-md-12">
                                                <i class="bx bx-time-five me-2 detail-icon"></i>
                                            </x-resource.detail-items>

                                            <x-resource.detail-items label="{{ __('field.meeting_room_id') }}"
                                                id="meeting-room" class="col-12 col-md-6">
                                                <i class="bx bx-door-open me-2 detail-icon"></i>
                                            </x-resource.detail-items>
                                            <x-resource.detail-items label="{{ __('field.meeting_type') }}"
                                                id="meeting-type" class="col-12 col-md-6">
                                                <i class="bx bx-category me-2 detail-icon"></i>
                                            </x-resource.detail-items>

                                            <x-resource.detail-items label="{{ __('field.is_virtual_meeting') }}"
                                                id="is-virtual-meeting" class="col-12 col-md-6" />

                                            <x-resource.detail-items label="{{ __('field.virtual_meeting_link') }}"
                                                id="virtual-meeting-link" class="col-12 col-md-6">
                                                <i class="bx bx-link me-2 detail-icon"></i>

                                                <a href="#" id="virtual-meeting-link" target="_blank"
                                                    class="text-primary text-decoration-underline">
                                                    <i class="bx bx-link me-2 detail-icon"></i>
                                                </a>

                                            </x-resource.detail-items>

                                            <x-resource.detail-items label="{{ __('field.is_external') }}" id="is-external"
                                                class="col-12 col-md-6" />
                                            <x-resource.detail-items label="{{ __('field.status') }}" id="status"
                                                class="col-12 col-md-6" />
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                    <div class="p-3 p-md-4">
                                        <div class="documents-grid" id="documents-grid">
                                            <!-- Images will be placed here dynamically -->
                                        </div>
                                        <div class="text-center mt-3" id="view-all-documents" style="display: none;">
                                            <a href="#" class="btn btn-primary btn-sm view-all-btn">
                                                <i class="bx bx-folder-open me-1"></i>
                                                {{ __('common.view_all_files') }} (<span id="document-count"></span>)
                                            </a>
                                        </div>
                                        <div class="empty-documents" id="empty-documents">
                                            <i class="bx bx-file-blank"></i>
                                            <p>{{ __('field.no_documents') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
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

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">


@push('scripts')
    <script type="module">
        window.meetingsPerMonthLabels = @json($dashboardData['meetingsPerMonthLabels'] ?? []);
        window.meetingsPerMonthData = @json($dashboardData['meetingsPerMonthData'] ?? []);
        window.statusLabels = @json($dashboardData['statusLabels'] ?? ['No Data']);
        window.statusData = @json($dashboardData['statusData'] ?? [0]);
        window.userMeetingsPerDayLabels = @json($dashboardData['userMeetingsPerDayLabels'] ?? []);
        window.userMeetingsPerDayData = @json($dashboardData['userMeetingsPerDayData'] ?? []);

                // Example JavaScript to load documents and handle images properly
            function loadDocuments(documentsList) {
                const documentsGrid = document.getElementById('documents-grid');
                const emptyDocuments = document.getElementById('empty-documents');
                const viewAllDocuments = document.getElementById('view-all-documents');
                const documentCount = document.getElementById('document-count');

                // Clear existing content
                documentsGrid.innerHTML = '';

                if (documentsList && documentsList.length > 0) {
                    // We have documents to display
                    emptyDocuments.style.display = 'none';
                    documentsGrid.style.display = 'grid';

                    documentsList.forEach(document => {
                        const documentElement = document.createElement('div');
                        documentElement.className = 'document-item';

                        alert('test');


                        // Check if document is an image
                        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(
                            document.extension?.toLowerCase()
                        );

                                    // In your loadDocuments function, update the image handling:
                            if (isImage) {
                                // Create image container with much smaller dimensions
                                documentElement.innerHTML = `
                                    <div class="image-container">
                                        <img src="${document.url}" alt="${document.name}" class="document-image" width="100" height="100">
                                    </div>
                                    <div class="document-name">${document.name}</div>
                                `;
                            }else {
                            // Regular document display
                            documentElement.innerHTML = `
                                <div class="document-container">
                                    <i class="bx ${getDocumentIcon(document.extension)}"></i>
                                    <div class="document-name">${document.name}</div>
                                </div>
                            `;
                        }

                        documentsGrid.appendChild(documentElement);
                    });

                    // Update count and show view all button if there are many documents
                    documentCount.textContent = documentsList.length;
                    viewAllDocuments.style.display = documentsList.length > 6 ? 'block' : 'none';
                } else {
                    // No documents to display
                    emptyDocuments.style.display = 'flex';
                    documentsGrid.style.display = 'none';
                    viewAllDocuments.style.display = 'none';
                }
            }

            // Helper function to get appropriate icon based on file extension
            function getDocumentIcon(extension) {
                const extensionMap = {
                    'pdf': 'bx-file-pdf',
                    'doc': 'bx-file-doc',
                    'docx': 'bx-file-doc',
                    'xls': 'bx-file-excel',
                    'xlsx': 'bx-file-excel',
                    'ppt': 'bx-file-ppt',
                    'pptx': 'bx-file-ppt',
                    // Add more mappings as needed
                };

                return extensionMap[extension?.toLowerCase()] || 'bx-file';
            }
    </script>
    <script src="{{ asset('js/dashboard.js') }}" type="module"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-meeting');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const meetingId = this.getAttribute('data-meeting-id');
                    fetchMeetingDetails(meetingId);
                });
            });
        });

        function fetchMeetingDetails(meetingId) {
            fetch(`view/${meetingId}`, {
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
                    document.getElementById('meeting-room').querySelector('p').textContent = data.meeting_rooms ||
                        'N/A';
                    document.getElementById('meeting-type').querySelector('p').textContent = data.meeting_type || 'N/A';
                    document.getElementById('is-external').querySelector('p').textContent = data.is_external || 'N/A';
                    document.getElementById('status').querySelector('p').textContent = data.status || 'N/A';
                    document.getElementById('is-virtual-meeting').querySelector('p').textContent = data
                        .is_virtual_meeting || 'N/A';
                    document.getElementById('virtual-meeting-link').querySelector('p').textContent = data
                        .virtual_meeting_link || 'N/A';

                    // In your fetchMeetingDetails function:
                    const linkElement = document.getElementById('virtual-meeting-link');
                    if (data.virtual_meeting_link) {
                        // Create link element with blue styling
                        const a = document.createElement('a');
                        a.href = data.virtual_meeting_link;
                        a.target = '_blank';
                        a.textContent = data.virtual_meeting_link.length > 40 ?
                            data.virtual_meeting_link.substring(0, 40) + '...' :
                            data.virtual_meeting_link;
                        a.className = 'text-primary text-decoration-underline'; // Adding blue color and underline

                        // Replace content
                        const containerElement = linkElement.querySelector('p') || linkElement.lastElementChild;
                        containerElement.innerHTML = '';
                        containerElement.appendChild(a);
                    } else {
                        const containerElement = linkElement.querySelector('p') || linkElement.lastElementChild;
                        containerElement.textContent = 'N/A';
                        containerElement.className = 'mb-0'; // Reset any previous styling
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
        document.querySelectorAll('.cancel-meeting').forEach(button => {
            button.addEventListener('click', function() {
                const meetingId = this.getAttribute('data-meeting-id');
                // alert(meetingId);
                console.log('Is Swal defined?', typeof Swal);
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to cancel this meeting?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, cancel it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX request to cancel the meeting
                        fetch('/meetings/cancel/' + meetingId, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        'Cancelled!',
                                        'The meeting has been cancelled.',
                                        'success'
                                    ).then(() => {
                                        // Reload the page to reflect changes
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message || 'Failed to cancel the meeting.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while cancelling the meeting.',
                                    'error'
                                );
                            });
                    }
                });
            });
        });

    </script>
@endpush
