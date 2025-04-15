<div class="container-xxl">
    <div class="row">
        @if($showWelcome)
            <!-- Welcome Message -->
            <div class="col-lg-8 mb-4 order-0">
                <div class="card bg-primary text-white h-100">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-10">
                            <div class="card-body">
                                <h5 class="card-title text-white">Welcome to PMIS Dashboard! 🎉</h5>
                                <p class="mb-4">Monitor and manage your project activities efficiently.</p>
                                <a href="javascript:;" class="btn btn-sm btn-outline-light">View Project Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($showUserLog && auth()->check())
            <!-- User Log Details -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">{{ __('field.user_log_details') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center d-flex gap-2 mb-2">
                            <div><i class='bx bx-log-in-circle bx-xs'></i> {{ __('field.last_login') }}</div>
                            <p class="card-text fw-semibold">
                                @if (auth()->user()->last_login_at)
                                    <span class="text-primary">{{ auth()->user()->last_login_at }}</span>
                                @else
                                    <span class="text-danger">{{ __('field.never_logged_in') }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="text-center d-flex gap-2">
                            <div><i class='bx bx-log-out-circle bx-xs'></i> {{ __('field.last_logout') }}</div>
                            <p class="card-text fw-semibold">
                                @if (auth()->user()->last_logout_at)
                                    <span class="text-primary">{{ auth()->user()->last_logout_at }}</span>
                                @else
                                    <span class="text-danger">{{ __('field.never_logged_out') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($showProgressForm && auth()->check())
            <!-- Cumulative Progress Form and Update Button -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0">Set Project Duration</h5>
                        <a href="{{ route('admin.cumulative-progress.edit', ['cumulative_progress' => $progress]) }}" class="btn btn-primary">Update Progress</a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Cumulative Progress Section -->
        <div class="col-12">
            <h6 class="fw-semibold py-2 mb-2" data-bs-toggle="collapse" href="#cumulativeProgress" role="button" aria-expanded="true" aria-controls="cumulativeProgress">
                Cumulative Progress <i class="bx bx-chevron-down"></i>
            </h6>
            <div class="collapse show" id="cumulativeProgress">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header"><h5 class="card-title m-0">Total Time Elapsed</h5></div>
                            <div class="card-body">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $timeElapsedPercentage }}%;" 
                                         aria-valuenow="{{ $timeElapsedPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $timeElapsedPercentage }}%
                                    </div>
                                </div>
                                <p class="mt-2">Elapsed: {{ $elapsedDays }} days / Total: {{ $totalDays }} days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header"><h5 class="card-title m-0">Total Financial Expenditure</h5></div>
                            <div class="card-body">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $expenditurePercentage }}%;" 
                                         aria-valuenow="{{ $expenditurePercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $expenditurePercentage }}%
                                    </div>
                                </div>
                                <p class="mt-2">Spent: Rs.{{ number_format($progress->total_given_expenditure) }} / Total: Rs.{{ number_format($progress->total_estimated_expenditure) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header"><h5 class="card-title m-0">Total Disbursement</h5></div>
                            <div class="card-body">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $disbursementPercentage }}%;" 
                                         aria-valuenow="{{ $disbursementPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $disbursementPercentage }}%
                                    </div>
                                </div>
                                <p class="mt-2">Disbursed: Rs.{{ number_format($progress->total_disbursed) }} / Total: Rs.{{ number_format($progress->total_budget) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Groups Formed -->
        @include('dashboardSection.groups')
        @include('dashboardSection.groupMembers')
        @include('dashboardSection.beneficiariesReached')
        @include('dashboardSection.groupsReached')


    </div>
</div>
@push('scripts')
    <script type="module">
        {!! $totalGroupsChart->script() !!}
        {!! $groupsTargetVsAchievedChart->script() !!}
        {!! $groupBySectorSubsectorChart->script() !!}
        {!! $groupMembersBySectorGenderChart->script() !!}
        {!! $groupMembersBySubSectorGenderChart->script() !!}
        {!! $groupMembershipByGenderChart->script() !!}
        {!! $groupMembershipByCasteChart->script() !!}

        {!! $beneficiariesReachedBySectorGender->script() !!}
        {!! $beneficiariesReachedBySubSectorGender->script() !!}
        {!! $beneficiariesReachedByGender->script() !!}
        {!! $beneficiariesReachedByCaste->script() !!}

        {!! $groupsReachedBySectorAndSubSector->script() !!}
    </script>
@endpush
