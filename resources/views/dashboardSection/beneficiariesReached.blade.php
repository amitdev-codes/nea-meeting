<div class="col-12">
    <h6 class="fw-semibold py-2 mb-2" data-bs-toggle="collapse" href="#totalBeneficiariesReached" role="button" aria-expanded="true"
        aria-controls="totalBeneficiariesReached">
        Total Beneficiaries Reached <i class="bx bx-chevron-down"></i>
    </h6>


    <div class="collapse show" id="totalBeneficiariesReached">
        <div class="row">
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By Sector (Gender)</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $beneficiariesReachedBySectorGender->container() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By SubSector (Gender)</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $beneficiariesReachedBySubSectorGender->container() !!}
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By Gender (Pie)</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $beneficiariesReachedByGender->container() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-header"><h5 class="card-title m-0">By Caste (Bar)</h5></div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                        {!! $beneficiariesReachedByCaste->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
