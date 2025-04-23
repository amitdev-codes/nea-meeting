@extends('landingpage::layouts/frontMaster')

@php
    $locale = Session::get('locale');
    App::setLocale('np');
@endphp

@section('navbar')
    @include('landingpage::partials.header')
@endsection

@section('content')
    <main class="main-content py-5" style="min-height: calc(100vh - 140px);">
        <div class="container-fluid px-4">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary back-btn">
                    <i class="bx bx-arrow-back me-2"></i>{{ __('Back') }}
                </a>
            </div>
            
            <!-- Main Content Card -->
            <div class="card main-card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                <!-- Gradient Header -->
                <div class="card-header gradient-header py-4 d-flex align-items-center">
                    <i class="bx bx-calendar-check header-icon me-3"></i>
                    <h4 class="mb-0 text-white">{{ $resource->title }}</h4>
                </div>
                
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Meeting Information Section -->
                        <div class="col-lg-8 p-4 meeting-info-section">
                            <h5 class="section-title mb-4">
                                <i class="bx bx-group me-2"></i>{{ __('field.meeting_information') }}
                            </h5>
                            
                            <div class="row gy-4 nepali_td">
                                <x-resource.detail-item label="{{ __('field.meeting_location') }}"
                                    :value="$resource->meeting_location" class="col-12">
                                    <i class="bx bx-map me-2 detail-icon"></i>
                                </x-resource.detail-item>
                                
                                <div class="col-12">
                                    <div class="date-time-container p-3">
                                        <div class="row g-3">
                                            <x-resource.detail-item label="{{ __('field.meeting_date') }}"
                                                :value="$resource->meeting_date . ' (' . $resource->meeting_date_ad . ')' ?? 'N/A'"
                                                class="col-md-6">
                                                <i class="bx bx-calendar me-2 detail-icon"></i>
                                            </x-resource.detail-item>
                                            
                                            <x-resource.detail-item label="{{ __('field.start_time') }}"
                                                :value="$resource->start_time ?? ''" class="col-md-3">
                                                <i class="bx bx-time me-2 detail-icon"></i>
                                            </x-resource.detail-item>
                                            
                                            <x-resource.detail-item label="{{ __('field.end_time') }}"
                                                :value="$resource->end_time ?? ''" class="col-md-3">
                                                <i class="bx bx-time-five me-2 detail-icon"></i>
                                            </x-resource.detail-item>
                                        </div>
                                    </div>
                                </div>
                                
                                <x-resource.detail-item label="{{ __('field.meeting_room_id') }}"
                                    :value="$resource->meetingRoom->name ?? 'N/A'" class="col-md-6">
                                    <i class="bx bx-door-open me-2 detail-icon"></i>
                                </x-resource.detail-item>
                                
                                <x-resource.detail-item label="{{ __('field.meeting_type') }}"
                                    :value="$resource->meeting_type ?? ''" class="col-md-6">
                                    <i class="bx bx-category me-2 detail-icon"></i>
                                </x-resource.detail-item>
                            </div>
                        </div>
                        
                        <!-- Documents Sidebar -->
                        <div class="col-lg-4 documents-sidebar">
                            <div class="p-4 h-100 border-start">
                                <h5 class="section-title mb-4">
                                    <i class="bx bx-file me-2"></i>{{ __('field.meeting_documents') }}
                                </h5>
                                
                                @if ($resource->media->isNotEmpty())
                                    <div class="documents-grid">
                                        @foreach ($resource->media->take(6) as $media)
                                            <div class="document-item">
                                                @if (in_array($media->mime_type, ['application/pdf']))
                                                    <div class="document-preview">
                                                        <div class="file-icon pdf-icon">
                                                            <i class="bx bxs-file-pdf"></i>
                                                        </div>
                                                        <div class="document-info">
                                                            <div class="document-name text-truncate" title="{{ $media->name ?? $media->file_name }}">
                                                                {{ $media->name ?? $media->file_name }}
                                                            </div>
                                                            <div class="document-actions">
                                                                <a href="{{ $media->getUrl() }}" target="_blank" class="action-btn view-btn" title="View">
                                                                    <i class="bx bx-show"></i>
                                                                </a>
                                                                <a href="{{ $media->getUrl() }}" download="{{ $media->file_name }}" class="action-btn download-btn" title="Download">
                                                                    <i class="bx bx-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif (in_array($media->mime_type, [
                                                    'text/plain',
                                                    'application/msword',
                                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                ]))
                                                    <div class="document-preview">
                                                        <div class="file-icon doc-icon">
                                                            <i class="bx bxs-file-doc"></i>
                                                        </div>
                                                        <div class="document-info">
                                                            <div class="document-name text-truncate" title="{{ $media->name ?? $media->file_name }}">
                                                                {{ $media->name ?? $media->file_name }}
                                                            </div>
                                                            <div class="document-actions">
                                                                <a href="{{ $media->getUrl() }}" target="_blank" class="action-btn view-btn" title="View">
                                                                    <i class="bx bx-show"></i>
                                                                </a>
                                                                <a href="{{ $media->getUrl() }}" download="{{ $media->file_name }}" class="action-btn download-btn" title="Download">
                                                                    <i class="bx bx-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif (str_starts_with($media->mime_type, 'image/'))
                                                    <div class="document-preview">
                                                        <div class="file-icon img-icon">
                                                            <img src="{{ $media->getUrl() }}" alt="{{ $media->name ?? $media->file_name }}" class="thumbnail">
                                                        </div>
                                                        <div class="document-info">
                                                            <div class="document-name text-truncate" title="{{ $media->name ?? $media->file_name }}">
                                                                {{ $media->name ?? $media->file_name }}
                                                            </div>
                                                            <div class="document-actions">
                                                                <a href="{{ $media->getUrl() }}" target="_blank" class="action-btn view-btn" title="View">
                                                                    <i class="bx bx-show"></i>
                                                                </a>
                                                                <a href="{{ $media->getUrl() }}" download="{{ $media->file_name }}" class="action-btn download-btn" title="Download">
                                                                    <i class="bx bx-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="document-preview">
                                                        <div class="file-icon generic-icon">
                                                            <i class="bx bxs-file"></i>
                                                        </div>
                                                        <div class="document-info">
                                                            <div class="document-name text-truncate" title="{{ $media->name ?? $media->file_name }}">
                                                                {{ $media->name ?? $media->file_name }}
                                                            </div>
                                                            <div class="document-actions">
                                                                <a href="{{ $media->getUrl() }}" target="_blank" class="action-btn view-btn" title="View">
                                                                    <i class="bx bx-show"></i>
                                                                </a>
                                                                <a href="{{ $media->getUrl() }}" download="{{ $media->file_name }}" class="action-btn download-btn" title="Download">
                                                                    <i class="bx bx-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if ($resource->media->count() > 6)
                                        <div class="text-center mt-4">
                                            <a href="#" class="btn btn-primary btn-sm view-all-btn">
                                                <i class="bx bx-folder-open me-1"></i>
                                                {{ __('common.view_all_files') }} ({{ $resource->media->count() }})
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="empty-documents">
                                        <i class="bx bx-file-blank"></i>
                                        <p>{{ __('field.no_documents') }}</p>
                                    </div>
                                @endif
                            </div>
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


    <style>
        /* General Styling */
        body {
            background-color: #f5f6fa;
        }
        
        .main-content {
            background-color: #f5f6fa;
            padding: 30px 15px;
        }
        
        /* Back Button */
        .back-btn {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border-width: 2px;
        }
        
        .back-btn:hover {
            transform: translateX(-5px);
        }
        
        .back-btn i {
            font-size: 1.2rem;
        }
        
        /* Main Card */
        .main-card {
            transition: box-shadow 0.3s ease;
            background-color: #fff;
        }
        
        .main-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        }
        
        /* Gradient Header */
        .gradient-header {
            background: linear-gradient(135deg, #0062cc 0%, #0096ff 100%);
            border-bottom: none;
            padding: 1.5rem;
        }
        
        .header-icon {
            font-size: 2rem;
            color: #ffffff;
        }
        
        /* Section Titles */
        .section-title {
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .section-title i {
            font-size: 1.4rem;
            color: #0074d9;
        }
        
        /* Detail Items */
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
        
        .detail-item p, .detail-item .form-control-plaintext {
            padding: 0.5rem 0.75rem;
            background-color: #f8f9fa;
            border-radius: 6px;
            color: #333;
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }
        
        /* Date-Time Container */
        .date-time-container {
            background-color: #f0f7ff;
            border-radius: 10px;
            border-left: 4px solid #0074d9;
        }
        
        /* Documents Sidebar */
        .documents-sidebar {
            background-color: #fcfcfc;
        }
        
        .documents-grid {
            display: grid;
            grid-template-columns: 1fr;
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
        
        /* Empty Documents State */
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
        
        /* View All Button */
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
        
        /* Responsive Adjustments */
        @media (min-width: 992px) {
            .documents-grid {
                grid-template-columns: 1fr;
            }
            
            .documents-sidebar {
                border-left: 1px solid #e0e0e0;
            }
        }
        
        @media (max-width: 991.98px) {
            .documents-sidebar {
                border-top: 1px solid #e0e0e0;
            }
            
            .documents-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 767.98px) {
            .gradient-header {
                padding: 1rem;
            }
            
            .header-icon {
                font-size: 1.5rem;
            }
            
            .gradient-header h4 {
                font-size: 1.2rem;
            }
            
            .documents-grid {
                grid-template-columns: 1fr;
            }
            
            .date-time-container {
                padding: 0.75rem !important;
            }
        }
    </style>
