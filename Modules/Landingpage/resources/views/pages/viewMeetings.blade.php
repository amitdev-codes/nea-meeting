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
            <div class="row g-4">
                {{-- Main Information Card --}}
                <div class="col-xl-8 col-lg-7">
                    <x-resource.detail-card title="{{ __('field.meeting_information') }}" icon="bx-group"
                        class="card h-100 shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-body p-4 nepali_td">
                            <div class="row gy-3">
                                <x-resource.detail-item label="{{ __('field.title') }}"
                                    :value="$resource->title" class="col-12">
                                    <i class="bx bx-text me-2"></i>
                                </x-resource.detail-item>
                                <x-resource.detail-item label="{{ __('field.meeting_location') }}"
                                    :value="$resource->meeting_location" class="col-12">
                                    <i class="bx bx-map me-2"></i>
                                </x-resource.detail-item>
                                <x-resource.detail-item label="{{ __('field.meeting_date') }}"
                                    :value="$resource->meeting_date . ' (' . $resource->meeting_date_ad . ')' ?? 'N/A'"
                                    class="col-md-6">
                                    <i class="bx bx-calendar me-2"></i>
                                </x-resource.detail-item>
                                <x-resource.detail-item label="{{ __('field.start_time') }}"
                                    :value="$resource->start_time ?? ''" class="col-md-3">
                                    <i class="bx bx-time me-2"></i>
                                </x-resource.detail-item>
                                <x-resource.detail-item label="{{ __('field.end_time') }}"
                                    :value="$resource->end_time ?? ''" class="col-md-3">
                                    <i class="bx bx-time-five me-2"></i>
                                </x-resource.detail-item>
                                <x-resource.detail-item label="{{ __('field.meeting_room_id') }}"
                                    :value="$resource->meetingRoom->name ?? 'N/A'" class="col-md-6">
                                    <i class="bx bx-door-open me-2"></i>
                                </x-resource.detail-item>
                                <x-resource.detail-item label="{{ __('field.meeting_type') }}"
                                    :value="$resource->meeting_type ?? ''" class="col-md-6">
                                    <i class="bx bx-category me-2"></i>
                                </x-resource.detail-item>
                            </div>
                        </div>
                    </x-resource.detail-card>
                </div>

                {{-- Documents Sidebar --}}
                <div class="col-xl-4 col-lg-5">
                    <x-resource.detail-card title="{{ __('field.meeting_documents') }}" icon="bx-file"
                        class="card h-100 shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            @if ($resource->media->isNotEmpty())
                                <div class="row g-3">
                                    @foreach ($resource->media->take(6) as $media)
                                        <div class="col-6">
                                            <div class="document-preview text-center">
                                                @if (in_array($media->mime_type, ['application/pdf']))
                                                    <div class="file-preview position-relative">
                                                        <div class="file-cover pdf mb-2">
                                                            <i class="bx bxs-file-pdf text-danger"></i>
                                                        </div>
                                                        <small class="d-block text-muted text-truncate"
                                                            title="{{ $media->name ?? $media->file_name }}">
                                                            {{ $media->name ?? $media->file_name }}
                                                        </small>
                                                        <div class="action-buttons mt-2">
                                                            <a href="{{ $media->getUrl() }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="bx bx-show me-1"></i> View
                                                            </a>
                                                            <a href="{{ $media->getUrl() }}"
                                                                download="{{ $media->file_name }}"
                                                                class="btn btn-sm btn-outline-secondary ms-1">
                                                                <i class="bx bx-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @elseif (in_array($media->mime_type, [
                                                    'text/plain',
                                                    'application/msword',
                                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                ]))
                                                    <div class="file-preview position-relative">
                                                        <div class="file-cover text mb-2">
                                                            <i class="bx bxs-file-doc text-primary"></i>
                                                        </div>
                                                        <small class="d-block text-muted text-truncate"
                                                            title="{{ $media->name ?? $media->file_name }}">
                                                            {{ $media->name ?? $media->file_name }}
                                                        </small>
                                                        <div class="action-buttons mt-2">
                                                            <a href="{{ $media->getUrl() }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="bx bx-show me-1"></i> View
                                                            </a>
                                                            <a href="{{ $media->getUrl() }}"
                                                                download="{{ $media->file_name }}"
                                                                class="btn btn-sm btn-outline-secondary ms-1">
                                                                <i class="bx bx-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @elseif (str_starts_with($media->mime_type, 'image/'))
                                                    <div class="file-preview position-relative">
                                                        <div class="image-cover mb-2">
                                                            <img src="{{ $media->getUrl() }}"
                                                                alt="{{ $media->name ?? $media->file_name }}"
                                                                class="img-fluid rounded" loading="lazy">
                                                        </div>
                                                        <small class="d-block text-muted text-truncate"
                                                            title="{{ $media->name ?? $media->file_name }}">
                                                            {{ $media->name ?? $media->file_name }}
                                                        </small>
                                                        <div class="action-buttons mt-2">
                                                            <a href="{{ $media->getUrl() }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="bx bx-show me-1"></i> View
                                                            </a>
                                                            <a href="{{ $media->getUrl() }}"
                                                                download="{{ $media->file_name }}"
                                                                class="btn btn-sm btn-outline-secondary ms-1">
                                                                <i class="bx bx-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="file-preview position-relative">
                                                        <div class="file-cover generic mb-2">
                                                            <i class="bx bxs-file text-secondary"></i>
                                                        </div>
                                                        <small class="d-block text-muted text-truncate"
                                                            title="{{ $media->name ?? $media->file_name }}">
                                                            {{ $media->name ?? $media->file_name }}
                                                        </small>
                                                        <div class="action-buttons mt-2">
                                                            <a href="{{ $media->getUrl() }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="bx bx-show me-1"></i> View
                                                            </a>
                                                            <a href="{{ $media->getUrl() }}"
                                                                download="{{ $media->file_name }}"
                                                                class="btn btn-sm btn-outline-secondary ms-1">
                                                                <i class="bx bx-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($resource->media->count() > 6)
                                        <div class="col-12 text-center mt-3">
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                View All Files ({{ $resource->media->count() }})
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-file-blank text-secondary mb-3" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-0">{{ __('field.no_documents') }}</p>
                                </div>
                            @endif
                        </div>
                    </x-resource.detail-card>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer')
    @include('landingpage::partials.footer')
@endsection

@section('styles')
    <style>
        /* Full-Screen Layout */
        .main-content {
            background-color: #f5f6fa;
            padding: 40px 20px;
        }

        /* Card Styling */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .card-header {
            border-bottom: none;
            font-size: 1.25rem;
        }

        .card-body {
            font-size: 0.95rem;
        }

        /* Bold Labels with Icons */
        .detail-item label {
            font-weight: 700 !important;
            color: #333;
            display: flex;
            align-items: center;
        }

        .detail-item .bx {
            font-size: 1.2rem;
            color: #555;
        }

        .detail-item p, .detail-item .form-control-plaintext {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        /* Document Preview */
        .document-preview {
            padding: 1rem;
            background: #ffffff;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e9ecef;
        }

        .document-preview:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        .file-cover {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .file-cover i {
            font-size: 3.5rem;
        }

        .image-cover {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .image-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-preview {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .action-buttons {
            margin-top: auto;
        }

        .file-cover.pdf {
            background-color: rgba(255, 230, 230, 0.2);
        }

        .file-cover.text {
            background-color: rgba(230, 240, 255, 0.2);
        }

        .file-cover.generic {
            background-color: rgba(240, 240, 240, 0.2);
        }

        .text-truncate {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Button Styling */
        .btn-outline-primary, .btn-outline-secondary {
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: #fff;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card-header h5 {
                font-size: 1.1rem;
            }
            .file-cover, .image-cover {
                height: 100px;
            }
            .file-cover i {
                font-size: 2.5rem;
            }
            .col-lg-3, .col-lg-9 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@endsection