@foreach ($meetings as $meeting)
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="avatar-xs">
                    <div class="avatar-title bg-primary text-white rounded-circle">
                        <i class="bx bx-calendar fs-5"></i>
                    </div>
                </div>
                <h6 class="mb-0 text-primary fw-medium">{{ $meeting->title }}</h6>
            </div>
            <div class="row gy-2 nepali_td">
                <div class="col-6">
                    <small><strong>{{ __('field.date') }}:</strong> {{ $meeting->meeting_date }}</small>
                </div>
                <div class="col-6">
                    <small><strong>{{ __('field.time') }}:</strong> {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</small>
                </div>
                <div class="col-12">
                    <small><strong>{{ __('field.location') }}:</strong>
                        @if ($meeting->is_virtual)
                            <span class="badge bg-purple text-white rounded-pill px-2 py-1">{{ __('Virtual') }}</span>
                        @else
                            {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                        @endif
                    </small>
                </div>
                <div class="col-12">
                    <small><strong>{{ __('field.meeting_type') }}:</strong>
                        <span class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white rounded-pill px-2 py-1">
                            {{ $meeting->meeting_type }}
                        </span>
                    </small>
                </div>
                <div class="col-12">
                    <small><strong>{{ __('field.status') }}:</strong>
                        <span class="badge {{ $meeting->status === 'Ongoing' ? 'bg-success' : ($meeting->status === 'Scheduled' ? 'bg-warning' : 'bg-secondary') }} text-white rounded-pill px-2 py-1">
                            {{ $meeting->status }}
                        </span>
                    </small>
                </div>
            </div>
            <div class="text-end mt-3">
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 view-meeting"
                        data-meeting-id="{{ $meeting->id }}"
                        data-bs-toggle="modal"
                        data-bs-target="#meetingModal"
                        title="{{ __('View Details') }}">
                    <i class="bx bx-show-alt fs-5"></i>
                </button>
                @role('md')
                @if ($meeting->status !== 'Cancelled')
                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3 cancel-meeting"
                            data-meeting-id="{{ $meeting->id }}"
                            title="{{ __('Cancel Meeting') }}">
                        <i class="bx bx-x-circle fs-5"></i>
                    </button>
                @endif
            @endrole
            </div>
        </div>
    </div>
@endforeach