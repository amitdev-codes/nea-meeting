@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="flex-grow-1 container-p-4">
        {{-- Breadcrumb --}}
        <x-breadcrumb title="Meeting Information" 
            :items="[['label' => 'Meeting Information', 'route' => 'admin.meetings.index']]" 
            class="mb-4" />

        <div class="row g-4">
            {{-- Main Information Card --}}
            <div class="col-xl-8 col-lg-7">
                <x-resource.detail-card title="{{ __('field.meeting_information') }}" icon="bx-group" 
                    class="card h-100 shadow-sm border-0">
                    <x-slot name="actions">
                        @can('edit meetings')
                            <a href="{{ route('admin.meetings.edit', $resource) }}" 
                               class="btn btn-primary btn-sm waves-effect waves-light" 
                               aria-label="Edit meeting">
                                <i class="bx bx-edit me-1"></i> Edit
                            </a>
                        @endcan
                    </x-slot>

                    <div class="card-body">
                        <div class="row gy-3">
                            <x-resource.detail-item label="{{ __('field.title') }}" 
                                :value="$resource->title" 
                                class="col-12" />
                            <x-resource.detail-item label="{{ __('field.meeting_location') }}" 
                                :value="$resource->meeting_location" 
                                class="col-12" />
                            <x-resource.detail-item label="{{ __('field.meeting_date') }}" 
                                :value="$resource->meeting_date . ' (' . $resource->meeting_date_ad . ')' ?? 'N/A'" 
                                class="col-md-6" />
                            <x-resource.detail-item label="{{ __('field.start_time') }}" 
                                :value="$resource->start_time ?? ''" 
                                class="col-md-3" />
                            <x-resource.detail-item label="{{ __('field.end_time') }}" 
                                :value="$resource->end_time ?? ''" 
                                class="col-md-3" />
                            <x-resource.detail-item label="{{ __('field.meeting_room_id') }}" 
                                :value="$resource->meetingRoom->name ?? 'N/A'" 
                                class="col-md-6" />
                            <x-resource.detail-item label="{{ __('field.meeting_type') }}" 
                                :value="$resource->meeting_type ?? ''" 
                                class="col-md-6" />
                            <x-resource.detail-item label="{{ __('field.created_at') }}" 
                                :value="$resource->created_at" 
                                type="datetime" 
                                class="col-md-6" />
                            <x-resource.detail-item label="{{ __('field.updated_at') }}" 
                                :value="$resource->updated_at" 
                                type="datetime" 
                                class="col-md-6" />
                        </div>
                    </div>
                </x-resource.detail-card>
            </div>

            {{-- Documents Sidebar --}}
            <div class="col-xl-4 col-lg-5">
                <x-resource.detail-card title="Meeting Documents" 
                    icon="bx-file" 
                    class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        @if ($resource->media->isNotEmpty())
                            <div class="row g-3">
                                @foreach ($resource->media->take(6) as $media)
                                    <div class="col-6">
                                        <div class="document-preview text-center">
                                            @php
                                                $mimeType = $media->mime_type;
                                                $fileName = $media->name ?? $media->file_name;
                                            @endphp
                                            <div class="file-preview">
                                                @if (in_array($mimeType, ['application/pdf']))
                                                    <div class="file-cover pdf mb-2">
                                                        <i class="bx bxs-file-pdf text-danger"></i>
                                                    </div>
                                                @elseif (in_array($mimeType, ['text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                                                    <div class="file-cover doc mb-2">
                                                        <i class="bx bxs-file-doc text-primary"></i>
                                                    </div>
                                                @elseif (str_starts_with($mimeType, 'image/'))
                                                    <div class="image-cover mb-2">
                                                        <img src="{{ $media->getUrl() }}" 
                                                             alt="{{ $fileName }}"
                                                             class="img-fluid rounded"
                                                             loading="lazy">
                                                    </div>
                                                @else
                                                    <div class="file-cover generic mb-2">
                                                        <i class="bx bxs-file text-secondary"></i>
                                                    </div>
                                                @endif
                                                <small class="d-block text-muted text-truncate" 
                                                       title="{{ $fileName }}">
                                                    {{ $fileName }}
                                                </small>
                                                <div class="action-buttons mt-2">
                                                    <a href="{{ $media->getUrl() }}" 
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-primary"
                                                       aria-label="View {{ $fileName }}">
                                                        <i class="bx bx-show me-1"></i> View
                                                    </a>
                                                    <a href="{{ $media->getUrl() }}" 
                                                       download="{{ $fileName }}"
                                                       class="btn btn-sm btn-outline-secondary ms-1"
                                                       aria-label="Download {{ $fileName }}">
                                                        <i class="bx bx-download"></i>
                                                    </a>
                                                </div>
                                            </div>
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

    <style>
        .container-p-4 {
            padding: 1rem;
        }

        .document-preview {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .document-preview:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .text-truncate {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 0.875rem;
        }

        .file-cover {
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .file-cover i {
            font-size: 2.5rem;
        }

        .image-cover {
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fff;
            border-radius: 6px;
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
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .file-cover.pdf {
            background-color: rgba(255, 230, 230, 0.2);
        }

        .file-cover.doc {
            background-color: rgba(230, 240, 255, 0.2);
        }

        .file-cover.generic {
            background-color: rgba(240, 240, 240, 0.2);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8125rem;
        }

        @media (max-width: 576px) {
            .document-preview {
                padding: 0.75rem;
            }

            .file-cover, .image-cover {
                height: 90px;
            }

            .file-cover i {
                font-size: 2rem;
            }
        }
    </style>
@endsection