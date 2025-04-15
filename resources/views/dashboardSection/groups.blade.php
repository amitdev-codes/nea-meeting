<div class="col-12">
    <h6 class="fw-semibold py-2 mb-2" data-bs-toggle="collapse" href="#totalGroupsFormed" role="button" aria-expanded="true" aria-controls="totalGroupsFormed">
        Total Groups Formed <i class="bx bx-chevron-down"></i>
    </h6>
    <div class="collapse show" id="totalGroupsFormed">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">Overall</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $groupsTargetVsAchievedChart->container() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By SectorSubSector</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $groupBySectorSubsectorChart->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
