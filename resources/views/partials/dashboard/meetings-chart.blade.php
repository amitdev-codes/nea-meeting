<div class="col-md-6">
    <div class="card chart-card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $title }}</h5>
        </div>
        <div class="card-body">
            @if (!empty($labels))
                <canvas id="{{ $chartId }}" class="chart-canvas" width="400" height="300"></canvas>
            @else
                <p class="text-center text-muted">No meeting data available for this period.</p>
            @endif
        </div>
    </div>
</div>