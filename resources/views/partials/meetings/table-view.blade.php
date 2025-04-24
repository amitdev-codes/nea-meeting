<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th class="ps-4 fw-semibold nepali_td">{{ __('field.title') }}</th>
            <th class="fw-semibold nepali_td">{{ __('field.date') }}</th>
            <th class="fw-semibold nepali_td">{{ __('field.time') }}</th>
            <th class="fw-semibold nepali_td">{{ __('field.location') }}</th>
            <th class="fw-semibold nepali_td d-none d-lg-table-cell">{{ __('field.description') }}</th>
            <th class="fw-semibold nepali_td">{{ __('field.meeting_type') }}</th>
            <th class="fw-semibold nepali_td d-none d-xl-table-cell">{{ __('field.meeting_status') }}</th>
            <th class="pe-4 text-end">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($meetings as $meeting)
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-xs">
                            <div class="avatar-title bg-primary text-white rounded-circle">
                                <i class="bx bx-calendar fs-5"></i>
                            </div>
                        </div>
                        <h6 class="mb-0 text-primary fw-medium">{{ $meeting->title }}</h6>
                    </div>
                </td>
                <td>{{ $meeting->meeting_date }}</td>
                <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</td>
                <td>
                    @if ($meeting->is_virtual)
                        <span class="badge bg-purple text-white rounded-pill px-2 py-1">{{ __('Virtual') }}</span>
                    @else
                        {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                    @endif
                </td>
                <td class="d-none d-lg-table-cell">
                    <p class="text-muted mb-0 text-truncate" style="max-width: 250px;">
                        {{ $meeting->description }}
                    </p>
                </td>
                <td>
                    <span class="badge bg-{{ $meeting->meeting_type === 'Board' ? 'info' : 'success' }} text-white rounded-pill px-2 py-1">
                        {{ $meeting->meeting_type }}
                    </span>
                </td>
                <td class="d-none d-xl-table-cell">
                    <span class="badge {{ $meeting->status === 'Ongoing' ? 'bg-success' : ($meeting->status === 'Scheduled' ? 'bg-warning' : 'bg-secondary') }} text-white rounded-pill px-2 py-1">
                        {{ $meeting->status }}
                    </span>
                </td>
                <td class="pe-4 text-end">
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 view-meeting"
                            data-meeting-id="{{ $meeting->id }}"
                            data-bs-toggle="modal"
                            data-bs-target="#meetingModal"
                            title="{{ __('View Details') }}">
                        <i class="bx bx-show-alt fs-5"></i>
                    </button>
                    @role('md')
                        @if ($meeting->status !== 'Cancelled') <!-- Prevent showing Cancel button for already cancelled meetings -->
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3 cancel-meeting"
                                    data-meeting-id="{{ $meeting->id }}"
                                    title="{{ __('Cancel Meeting') }}">
                                <i class="bx bx-x-circle fs-5"></i>
                            </button>
                        @endif
                    @endrole
                </td>
                
            </tr>
        @endforeach
    </tbody>
</table>