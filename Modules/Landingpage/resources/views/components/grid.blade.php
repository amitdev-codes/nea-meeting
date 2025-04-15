<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Meetings</h5>
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
                            @if ($meeting->is_virtual)
                                <span class="badge bg-label-info">Virtual</span>
                            @else
                                {{ $meeting->meeting_location ?? ($meeting->meetingRoom->name ?? 'N/A') }}
                            @endif
                        </td>
                        <td>
                            <a class="dropdown-item" href="{{ route('admin.meetings.show', $meeting->id) }}">
                                <i class="bx bx-show-alt me-1"></i>
                            </a>
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
