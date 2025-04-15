@extends('layouts/contentNavbarLayout')
@section('content')
    <div class="container-xxl py-2 mb-4 border-bottom breadcrumb-div">
        <nav aria-label="breadcrumb" class="d-flex justify-content-between align-middle">
            <div class="d-flex align-items-center">
                <a href="javascript:history.back()" class="me-2"><i class="bx bx-left-arrow-alt"></i></a>
                <h6 class="my-0 font-weight-bold">{{ __('field.dashboard') }}</h6>
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
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Meetings</h5>
                        <h2 class="mb-0">45</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Active Members</h5>
                        <h2 class="mb-0">28</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Actions</h5>
                        <h2 class="mb-0">12</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Completed Tasks</h5>
                        <h2 class="mb-0">87</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4">
            <!-- Meetings Per Month Bar Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Meetings Per Month</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="meetingsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Meeting Status Pie Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Meeting Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script>
        // Meetings Per Month Chart
        const meetingsChart = new Chart(document.getElementById('meetingsChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Number of Meetings',
                    data: [5, 8, 6, 7, 4, 9],
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
                }
            }
        });

        // Meeting Status Chart
        const statusChart = new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Completed', 'In Progress', 'Scheduled', 'Cancelled'],
                datasets: [{
                    data: [20, 10, 12, 3],
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
            }
        });
    </script>
@endsection