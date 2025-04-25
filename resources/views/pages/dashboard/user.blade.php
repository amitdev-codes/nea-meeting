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

            <!-- Modal for Meeting Details -->
            <div class="modal fade" id="meetingModal" tabindex="-1" aria-labelledby="meetingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title" id="meetingModalLabel">{{ __('Meeting Details') }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs" id="meetingTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">{{ __('field.meeting_information') }}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">{{ __('field.meeting_documents') }}</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="meetingTabContent">
                                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                    <div class="p-4">
                                        <div class="row gy-4 nepali_td">
                                            <x-resource.detail-items label="{{ __('field.title') }}" id="meeting-title" class="col-12">
                                                <i class="bx bx-calendar me-2 detail-icon"></i>
                                            </x-resource.detail-items>
                                            <x-resource.detail-items label="{{ __('field.meeting_location') }}" id="meeting-location" class="col-12">
                                                <i class="bx bx-map me-2 detail-icon"></i>
                                            </x-resource.detail-items>
                                            <div class="col-12">
                                                <div class="date-time-container p-3">
                                                    <div class="row g-3">
                                                        <x-resource.detail-items label="{{ __('field.meeting_date') }}" id="meeting-date" class="col-md-6">
                                                            <i class="bx bx-calendar me-2 detail-icon"></i>
                                                        </x-resource.detail-items>
                                                        <x-resource.detail-items label="{{ __('field.start_time') }}" id="start-time" class="col-md-3">
                                                            <i class="bx bx-time me-2 detail-icon"></i>
                                                        </x-resource.detail-items>
                                                        <x-resource.detail-items label="{{ __('field.end_time') }}" id="end-time" class="col-md-3">
                                                            <i class="bx bx-time-five me-2 detail-icon"></i>
                                                        </x-resource.detail-items>
                                                    </div>
                                                </div>
                                            </div>
                                            <x-resource.detail-items label="{{ __('field.meeting_room_id') }}" id="meeting-room" class="col-md-6">
                                                <i class="bx bx-door-open me-2 detail-icon"></i>
                                            </x-resource.detail-items>
                                            <x-resource.detail-items label="{{ __('field.meeting_type') }}" id="meeting-type" class="col-md-6">
                                                <i class="bx bx-category me-2 detail-icon"></i>
                                            </x-resource.detail-items>
                                            {{-- //virtual meeting link --}}

                                  
                                        <x-resource.detail-items label="{{ __('field.is_virtual_meeting') }}" id="is-virtual-meeting" class="col-md-6" />
                                        <x-resource.detail-items label="{{ __('field.virtual_meeting_link') }}" id="virtual-meeting-link" class="col-md-6">
                                            <i class="bx bx-link me-2 detail-icon"></i>
                                        </x-resource.detail-items>
                                        <x-resource.detail-items label="{{ __('field.is_external') }}" id="is-external"class="col-md-6" />
                                        <x-resource.detail-items label="{{ __('field.status') }}" id="status"  class="col-md-6" />
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                    <div class="p-4">
                                        <div class="documents-grid" id="documents-grid"></div>
                                        <div class="text-center mt-4" id="view-all-documents" style="display: none;">
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
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
@push('styles')
<style>
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
        }

        .nav-tabs {
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 1rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            padding: 0.75rem 1.5rem;
            color: #333;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            background-color: transparent;
            border-bottom: 3px solid #0074d9;
            color: #0074d9;
        }

        .nav-tabs .nav-link:hover {
            color: #0074d9;
        }

        /* Reuse styles from your previous code */
        .main-content {
            background-color: #f5f6fa;
            padding: 30px 15px;
        }

        .meeting-card {
            transition: box-shadow 0.3s ease;
            background-color: #fff;
        }

        .meeting-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0062cc 0%, #0096ff 100%);
        }

        .detail-item label {
            font-weight: 600 !important;
            color: #444;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .detail-icon {
            font-size: 1.2rem;
            color: #0074d9;
        }

        .detail-item p,
        .detail-item .form-control-plaintext {
            padding: 0.5rem 0.75rem;
            background-color: #f8f9fa;
            border-radius: 6px;
            color: #333;
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }

        .date-time-container {
            background-color: #f0f7ff;
            border-radius: 10px;
            border-left: 4px solid #0074d9;
        }

        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }

        .document-item {
            position: relative;
        }

        .document-preview {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .document-preview:hover {
            border-color: #0074d9;
            box-shadow: 0 5px 15px rgba(0, 116, 217, 0.1);
            transform: translateY(-2px);
        }

        .file-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
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
            overflow: hidden;
        }

        .thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            margin-bottom: 5px;
        }

        .document-actions {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            width: 28px;
            height: 28px;
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
            font-size: 3.5rem;
            color: #ccc;
            margin-bottom: 1rem;
            display: block;
        }

        .empty-documents p {
            color: #888;
            margin: 0;
        }

        .view-all-btn {
            background: #0074d9;
            border-color: #0074d9;
            transition: all 0.2s ease;
        }

        .view-all-btn:hover {
            background: #005bb5;
            border-color: #005bb5;
            transform: translateY(-2px);
        }
        .detail-item a.text-primary {
            text-decoration: none;
        }
        .detail-item a.text-primary:hover {
            text-decoration: underline;
        }

        @media (max-width: 767.98px) {
            .documents-grid {
                grid-template-columns: 1fr;
            }

            .date-time-container {
                padding: 0.75rem !important;
            }
        }
</style>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const viewButtons = document.querySelectorAll('.view-meeting');
            viewButtons.forEach(button => {
                button.addEventListener('click', function () {
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
                document.getElementById('meeting-location').querySelector('p').textContent = data.is_virtual ? '{{ __('Virtual') }}' : (data.meeting_location || data.meeting_room?.name || 'N/A');
                document.getElementById('meeting-date').querySelector('p').textContent = `${data.meeting_date} (${data.meeting_date_ad})` || 'N/A';
                document.getElementById('start-time').querySelector('p').textContent = data.start_time || 'N/A';
                document.getElementById('end-time').querySelector('p').textContent = data.end_time || 'N/A';
                document.getElementById('meeting-room').querySelector('p').textContent = data.meeting_room?.name || 'N/A';
                document.getElementById('meeting-type').querySelector('p').textContent = data.meeting_type || 'N/A';
                document.getElementById('is-external').querySelector('p').textContent = data.is_external || 'N/A';
                document.getElementById('is-virtual-meeting').querySelector('p').textContent = data.is_virtual_meeting || 'N/A';
                document.getElementById('virtual-meeting-link').querySelector('p').textContent = data.virtual_meeting_link || 'N/A';

                        // Handle virtual meeting link
                    const virtualMeetingLinkP = document.getElementById('virtual-meeting-link').querySelector('p');
                    const isValidUrl = url => /^https?:\/\//.test(url); // Simple URL validation
                    if (data.virtual_meeting_link && isValidUrl(data.virtual_meeting_link)) {
                        virtualMeetingLinkP.innerHTML = `<a href="${data.virtual_meeting_link}" target="_blank" class="text-primary"><i class="bx bx-link me-2 detail-icon"></i>${data.virtual_meeting_link}</a>`;
                    } else {
                        virtualMeetingLinkP.innerHTML = `<i class="bx bx-link me-2 detail-icon"></i>{{ __('N/A') }}`;
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
                        } else if (['text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'].includes(media.mime_type)) {
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
            button.addEventListener('click', function () {
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