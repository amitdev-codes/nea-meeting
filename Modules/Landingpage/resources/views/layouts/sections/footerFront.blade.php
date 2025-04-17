<div class="container">
    <div class="row">
        <div class="col-md-6">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <ul class="list-inline mb-0">
                <li class="list-inline-item">
                    <a href="#" class="text-muted">{{ __('landing.privacy_policy') }}</a>
                </li>
                <li class="list-inline-item ms-3">
                    <a href="#" class="text-muted">{{ __('landing.terms_of_service') }}</a>
                </li>
            </ul>
        </div>
    </div>
</div>