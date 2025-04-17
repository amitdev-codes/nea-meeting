@extends('layouts/contentNavbarLayout')

@section('content')
    <div class="container-xxl py-2 mb-4 border-bottom breadcrumb-div">
        <nav aria-label="breadcrumb" class="d-flex justify-content-between align-middle">
            <div class="d-flex align-items-center">
                <a href="javascript:history.back()" class="me-2"><i class="bx bx-left-arrow-alt"></i></a>
                <h6 class="my-0 fw-bold">{{ __('field.dashboard') }}</h6>
            </div>
            <ol class="breadcrumb breadcrumb-style1 mb-0 mt-1 me-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('field.home') }}</a></li>
            </ol>
        </nav>
    </div>

    <!-- Total Counts Section -->
    <div class="container-xxl py-4">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title text-white">Today's Meetings</h5>
                        <h2 class="mb-0 text-white">{{ $todaysMeetings }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title text-white">Upcoming Meetings</h5>
                        <h2 class="mb-0 text-white">{{ $upcomingMeetings }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title text-white">This Month Meetings</h5>
                        <h2 class="mb-0 text-white">{{ $thisMonthMeetings }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Meetings</h5>
                        <h2 class="mb-0 text-white">{{ $totalMeetings }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4">
            <!-- Meetings Per Month Bar Chart -->
            <div class="col-md-6">
                <div class="card chart-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Meetings Per Month</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="meetingsChart" class="chart-canvas" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Meeting Status Pie Chart -->
            <div class="col-md-6">
                <div class="card chart-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Meeting Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" class="chart-canvas" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .chart-card {
            height: 400px; /* Fixed height for both cards */
            display: flex;
            flex-direction: column;
        }
        .chart-card .card-body {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        .chart-canvas {
            width: 100% !important;
            height: 300px !important;
            max-width: 400px; /* Match canvas width */
            max-height: 300px; /* Match canvas height */
        }
        @media (max-width: 768px) {
            .chart-canvas {
                max-width: 100%;
                height: 250px !important; /* Slightly smaller for mobile */
            }
            .chart-card {
                height: 350px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script type="module">
        // Meetings Per Month Chart
        const meetingsChart = new Chart(document.getElementById('meetingsChart'), {
            type: 'bar',
            data: {
                labels: @json($meetingsPerMonthLabels),
                datasets: [{
                    label: 'Number of Meetings',
                    data: @json($meetingsPerMonthData),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                maintainAspectRatio: false, // Disable aspect ratio to respect canvas size
                responsive: true
            }
        });

        // Meeting Status Chart
        const statusChart = new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: @json($statusLabels),
                datasets: [{
                    data: @json($statusData),
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false, // Disable aspect ratio to respect canvas size
                responsive: true
            }
        });
    </script>
@endpush