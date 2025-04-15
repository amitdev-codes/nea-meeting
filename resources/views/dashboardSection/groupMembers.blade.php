<div class="col-12">
    <h6 class="fw-semibold py-2 mb-2" data-bs-toggle="collapse" href="#totalGroupMembers" role="button" aria-expanded="true"
        aria-controls="totalGroupMembers">
        Total Group Members <i class="bx bx-chevron-down"></i>
    </h6>


    <div class="collapse show" id="totalGroupMembers">
        <div class="row">
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By Sector (Gender)</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $groupMembersBySectorGenderChart->container() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By SubSector (Gender)</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $groupMembersBySubSectorGenderChart->container() !!}
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By Gender (Pie)</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $groupMembershipByGenderChart->container() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By Caste (Bar)</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $groupMembershipByCasteChart->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
