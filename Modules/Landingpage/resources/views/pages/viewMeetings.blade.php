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
                <img src="{{ asset('assets/img/nea-logo.png') }}" alt="Government Logo" width="full" class="img-fluid" style="
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
        <div class="flex-grow-1 container-p-y pt-4 m-4">
            <div class="row g-4">
                {{-- Main Information Card --}}
                <div class="col-xl-8 col-lg-7">
                    <x-resource.detail-card title="{{ __('field.meeting_information') }}" icon="bx-group"
                        class="card h-100 shadow-sm border-0">


                        <div class="card-body">
                            <div class="row gy-3">
                                <x-resource.detail-item label="{{ __('field.title') }}" :value="$resource->title" class="col-12" />
                                <x-resource.detail-item label="{{ __('field.meeting_location') }}" :value="$resource->meeting_location"
                                    class="col-12" />
                                <x-resource.detail-item label="{{ __('field.meeting_date') }}" :value="$resource->meeting_date . ' (' . $resource->meeting_date_ad . ')' ?? 'N/A'"
                                    class="col-md-6" />
                                <x-resource.detail-item label="{{ __('field.start_time') }}" :value="$resource->start_time ?? ''"
                                    class="col-md-3" />
                                <x-resource.detail-item label="{{ __('field.end_time') }}" :value="$resource->end_time ?? ''"
                                    class="col-md-3" />
                                <x-resource.detail-item label="{{ __('field.meeting_room_id') }}" :value="$resource->meetingRoom->name ?? 'N/A'"
                                    class="col-md-6" />
                                <x-resource.detail-item label="{{ __('field.meeting_type') }}" :value="$resource->meeting_type ?? ''"
                                    class="col-md-6" />

                                <x-resource.detail-item label="{{ __('field.created_at') }}" :value="$resource->created_at"
                                    type="datetime" class="col-md-6" />
                                <x-resource.detail-item label="{{ __('field.updated_at') }}" :value="$resource->updated_at"
                                    type="datetime" class="col-md-6" />
                            </div>
                        </div>
                    </x-resource.detail-card>
                </div>

                {{-- Documents Sidebar --}}
                <div class="col-xl-4 col-lg-5">
                    <x-resource.detail-card title="Meeting Documents" icon="bx-file" class="card h-100 shadow-sm border-0">
                        <div class="card-body">
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
                                    <p class="text-muted mb-0">No documents available</p>
                                </div>
                            @endif
                        </div>
                    </x-resource.detail-card>
                </div>
            </div>
        </div>
    @endsection

<style>
    .document-preview {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
        height: 100%;
    }

    .document-preview:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .text-truncate {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-cover {
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 6px;
        border: 1px solid #eee;
    }

    .file-cover i {
        font-size: 3rem;
    }

    .image-cover {
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #fff;
        border-radius: 6px;
        border: 1px solid #eee;
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
        background-color: rgba(255, 230, 230, 0.3);
    }

    .file-cover.text {
        background-color: rgba(230, 240, 255, 0.3);
    }

    .file-cover.generic {
        background-color: rgba(240, 240, 240, 0.3);
    }
</style>
