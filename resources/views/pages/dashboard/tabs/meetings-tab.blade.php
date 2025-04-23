<div class="tab-pane fade show active" id="meetings" role="tabpanel" aria-labelledby="meetings-tab">
    @if ($upcomingMeetings->isNotEmpty())
        <!-- Desktop Table View -->
        <div class="table-responsive d-none d-md-block">
            @include('partials.meetings.table-view', ['meetings' => $upcomingMeetings])
        </div>

        <!-- Mobile Card View -->
        <div class="d-md-none">
            @include('partials.meetings.card-view', ['meetings' => $upcomingMeetings])
        </div>

        <!-- Pagination -->
        @if ($upcomingMeetings->hasPages())
            <div class="card-footer bg-light py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <small class="text-center text-md-start text-muted">
                        {{ __('Showing') }} {{ $upcomingMeetings->firstItem() }} {{ __('to') }}
                        {{ $upcomingMeetings->lastItem() }} {{ __('of') }}
                        {{ $upcomingMeetings->total() }} {{ __('entries') }}
                    </small>
                    <div class="pagination-container">
                        {{ $upcomingMeetings->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    @else
        @include('partials.meetings.no-meetings')
    @endif
</div>