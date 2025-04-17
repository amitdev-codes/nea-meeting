<div class="bg-white shadow-sm py-2">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <!-- Empty div for balance on left -->
            <div class="order-1 d-none d-md-block" style="width: 80px;"></div>
            
            <!-- Centered Logo + Text Group -->
            <div class="order-2 order-md-2 d-flex align-items-center justify-content-center gap-3 mx-auto">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <img src="{{ asset('assets/img/nea-logo.png') }}" alt="NEA Logo" class="img-fluid" style="max-width: 80px;">
                </div>
                
                <!-- Text Content -->
                <div class="d-flex flex-column justify-content-center gap-1">
                    <span class="custom-kalimati text-uppercase fw-bold text-danger fs-5 mb-0">{{ __('landing.government of nepal') }}</span>
                    <span class="fw-bold text-danger fs-4 lh-1 mb-0">{{ __('landing.nepal electricity authority') }}</span>
                    <span class="fw-bold text-success fs-5 mb-0">{{ __('landing.meeting management system') }}</span>
                </div>
            </div>
            
            <!-- Login Button on right -->
            <div class="order-3 order-md-3 flex-shrink-0">
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3">
                    <i class="bx bx-log-in me-1"></i> {{ __('Login') }}
                </a>
            </div>
        </div>
    </div>
</div>