<!-- File: resources/views/meetings/partials/meeting-list.blade.php -->
@if(count($meetings) > 0)
    <div class="list-group">
        @foreach($meetings as $meeting)
            @php
                $startTime = \Carbon\Carbon::parse($meeting->start_time)->format('g:i A');
                $endTime = \Carbon\Carbon::parse($meeting->end_time)->format('g:i A');
                
                $statusClass = 'bg-primary';
                switch($meeting->status) {
                    case 'completed':
                        $statusClass = 'bg-success';
                        break;
                    case 'cancelled':
                        $statusClass = 'bg-danger';
                        break;
                    case 'postponed':
                        $statusClass = 'bg-warning';
                        break;
                    case 'ongoing':
                        $statusClass = 'bg-info';
                        break;
                }
            @endphp
            
            <a href="{{ route('admin.meetings.show', $meeting->id) }}" class="list-group-item list-group-item-action meeting-list-item {{ $meeting->status }}">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">{{ $meeting->title }}</h6>
                    <span class="badge {{ $statusClass }}">{{ ucfirst($meeting->status) }}</span>
                </div>
                <div class="mb-1 meeting-time">
                    <i class="bx bx-time-five me-1"></i> {{ $startTime }} - {{ $endTime }}
                </div>
                <div class="meeting-location">
                    @if($meeting->is_virtual)
                        <i class="bx bx-video me-1"></i> Virtual Meeting
                    @else
                        <i class="bx bx-map me-1"></i> 
                        @if($meeting->meeting_location)
                            {{ $meeting->meeting_location }}
                        @elseif($meeting->meetingRoom)
                            {{ $meeting->meetingRoom->name }}
                        @else
                            Location not specified
                        @endif
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="no-meetings">
        <i class="bx bx-calendar-x fs-1 mb-2"></i>
        <h6>No meetings scheduled for this date</h6>
        <p class="mb-0">There are no meetings scheduled for the selected date.</p>
        <a href="{{ route('admin.meetings.create') }}" class="btn btn-primary btn-sm mt-3">
            <i class="bx bx-plus me-1"></i> Schedule New Meeting
        </a>
    </div>
@endif