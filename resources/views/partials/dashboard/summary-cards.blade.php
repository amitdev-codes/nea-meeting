<?php use App\Helpers\NepaliDateConverter; ?>
<div class="row g-4 mb-4">
    <!-- Today's Meetings -->
    <div class="col-md">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body d-flex align-items-center">
                <i class="bx bx-calendar-minus bx-md me-3"></i>
                <div>
                    <h5 class="card-title text-white mb-1">हिजोका बैठकहरू</h5>
                    <h2 class="mb-0 text-white">{{ NepaliDateConverter::toNepaliDigits($yesterdaysMeetings) }}</h2>
                </div>
            </div>
        </div>
    </div>
        <!-- Today's Meetings -->
        <div class="col-md">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body d-flex align-items-center">
                <i class="bx bx-calendar-event bx-md me-3"></i>
                <div>
                    <h5 class="card-title text-white mb-1">आजका बैठकहरू</h5>
                    <h2 class="mb-0 text-white">{{ NepaliDateConverter::toNepaliDigits($todaysMeetings) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Upcoming Meetings -->
    <div class="col-md">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body d-flex align-items-center">
                <i class="bx bx-time-five bx-md me-3"></i>
                <div>
                    <h5 class="card-title text-white mb-1">आगामी बैठकहरू</h5>
                    <h2 class="mb-0 text-white">{{ NepaliDateConverter::toNepaliDigits($comingMeetings) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- This Month Meetings -->
    <div class="col-md">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body d-flex align-items-center">
                <i class="bx bx-calendar-month bx-md me-3"></i>
                <div>
                    <h5 class="card-title text-white mb-1">यो महिनाका बैठकहरू</h5>
                    <h2 class="mb-0 text-white">{{ NepaliDateConverter::toNepaliDigits($thisMonthMeetings) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Total Meetings -->
    <div class="col-md">
        <div class="card bg-warning text-white shadow-sm">
            <div class="card-body d-flex align-items-center">
                <i class="bx bx-list-ul bx-md me-3"></i>
                <div>
                    <h5 class="card-title text-white mb-1">कुल बैठकहरू</h5>
                    <h2 class="mb-0 text-white">{{ NepaliDateConverter::toNepaliDigits($totalMeetings) }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>