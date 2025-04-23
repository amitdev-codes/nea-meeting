@foreach ($meetings as $meeting)
    <div class="meeting-card-mobile p-4 border-bottom bg-white hover-bg-light">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-xs">
                    <div class="avatar-title bg-primary text-white rounded-circle">
                        <i class="bx bx-calendar fs-5"></i>
                    </div>
                </div>
                <h6 class="mb-0 text-primary fw-medium">{{ $meeting->title }}</h6>
            </div>
            <span class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white rounded-pill px-2 py-1">
                {{ $meeting->meeting_type }}
            </span>
        </div>
        <div class="meeting-details ps-4">
            <div class="row g-3 mb-2">
                <div class="col-5">
                    <small class="text-muted nepali_td fw-medium">{{ __('field.date') }}:</small>
                </div>
                <div class="col-7">
                    <small>{{ $meeting->meeting_date }}</small>
                </div>
            </div>
            <div class="row g-3 mb-2">
                <div class="col-5">
                    <small class="text-muted nepali_td fw-medium">{{ __('field.time') }}:</small>
                </div>
                <div class="col-7">
                    <small>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</small>
                </div>
            </div>
            <div class="row g-3 mb-2">
                <div class="col-5">
                    <small class="text-muted nepali_td fw-medium">{{ __('field.location') }}:</small>
                </div>
                <div class="col-7">
                    <small>
                        @if ($meeting->is_virtual)
                            <span class="badge bg-purple text-white rounded-pill px-2 py-1">{{ __('Virtual') }}</span>
                        @else
                            {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                        @endif
                    </small>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-5">
                    <small class="text-muted nepali_td fw-medium">{{ __('field.status') }}:</small>
                </div>
                <div class="col-7">
                    <small>
                        <span class="badge {{ $meeting->status === 'Ongoing' ? 'bg-success' : ($meeting->status === 'Scheduled' ? 'bg-warning' : 'bg-secondary') }} text-white rounded-pill px-2 py-1">
                            {{ $meeting->status }}
                        </span>
                    </small>
                </div>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('admin.landingPage.view', $meeting->id) }}"
                    class="btn btn-sm btn-outline-primary rounded-pill px-4">
                    <i class="bx bx-show-alt me-1"></i> {{ __('View') }}
                </a>
            </div>
        </div>
    </div>
@endforeach