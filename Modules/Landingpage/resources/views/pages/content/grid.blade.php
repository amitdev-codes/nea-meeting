<!-- resources/views/landingpage/components/grid.blade.php -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Meetings</h5>
        <a href="{{ route('meetings.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Add Meeting
        </a>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($meetings as $meeting)
                    <tr>
                        <td><strong>{{ $meeting->title }}</strong></td>
                        <td>{{ $meeting->meeting_type }}</td>
                        <td>{{ $meeting->meeting_date }}</td>
                        <td>{{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}</td>
                        <td>
                            @if($meeting->is_virtual)
                                <span class="badge bg-label-info">Virtual</span>
                            @else
                                {{ $meeting->meeting_location ?? $meeting->meetingRoom->name ?? 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'scheduled' => 'bg-label-primary',
                                    'completed' => 'bg-label-success',
                                    'cancelled' => 'bg-label-danger',
                                    'postponed' => 'bg-label-warning',
                                ][$meeting->status] ?? 'bg-label-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($meeting->status) }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('meetings.show', $meeting->id) }}">
                                        <i class="bx bx-show-alt me-1"></i> View
                                    </a>
                                    <a class="dropdown-item" href="{{ route('meetings.edit', $meeting->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this meeting?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No meetings found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>