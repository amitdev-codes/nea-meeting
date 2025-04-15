    <!-- Government Header -->
    <div class="container-fluid py-2 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Left: Government Logo -->
            <div class="d-flex align-items-center">
                <img src="{{ asset('img/gov.png') }}" alt="Government Logo" class="img-fluid" style="max-height: 80px;">
            </div>

            <!-- Middle: Project Title -->
            <div class="text-center flex-grow-1">
                <h3 class="mb-0 fs-5">{{ __('landing.government of nepal') }}</h3>
                <h4 class="mb-0 fs-6">{{ __('landing.ministry of agriculture and livestock development') }}</h4>
                <h5 class="mb-0 fs-6 fw-normal">{{ __('landing.food and nutrition security enhancement project') }}</h5>
                <p class="mb-0 small">{{ __('landing.hariharbhawan') }}, {{ __('landing.lalitpur') }}</p>
            </div>

            <!-- Right: Nepal Flag -->
            <div class="d-flex align-items-center">
                <img src="{{ asset('img/nepalflag.gif') }}" alt="Nepal Flag" class="img-fluid"
                    style="max-height: 80px;">
            </div>
        </div>
    </div>
