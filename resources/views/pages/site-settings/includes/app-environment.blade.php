<div class="row">
    <div class="col-md-12 mb-3">
        <div class="alert @if (env('APP_ENV') == 'production') alert-warning @else alert-info @endif text-center" role="alert">
            You are in a {{ env('APP_ENV') }} environment.
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <h5 class="card-header is-invalid">Send email from</h5>
        <span class="error invalid-feedback">{{ $errors->first('email_env') }}</span>
        <div class="card-body">
            <div class="row">
                <div class="col-md mb-md-0 mb-5">
                    <div class=" form-check custom-option custom-option-basic {{ old('email_env', $settings->settings['email_env'] ?? '') === 'test' ? 'checked' : '' }}">
                        <label class="form-check-label custom-option-content" for="email_env_test">
                            <input name="email_env" class="form-check-input @error('email_env') is-invalid @enderror" type="radio" id="email_env_test" value="test" {{ old('email_env', $settings->settings['email_env'] ?? '') === 'test' ? 'checked' : '' }}>
                            <span class="custom-option-header">
                                <span class="h6 mb-0">Test Email</span>
                            </span>
                            <span class="custom-option-body">
                                <input type="text" class="form-control @error('test_email') is-invalid @enderror" placeholder="Test Email" name="test_email" value="{{ old('test_email', $settings->settings['test_email'] ?? '') }}">
                                <span class="error invalid-feedback">{{ $errors->first('test_email') }}</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-md">
                    <div class=" form-check custom-option custom-option-basic {{ old('email_env', $settings->settings['email_env'] ?? '') === 'live' ? 'checked' : '' }}">
                        <label class="form-check-label custom-option-content" for="email_env_live">
                            <input name="email_env" class="form-check-input @error('email_env') is-invalid @enderror" type="radio" id="email_env_live" value="live" {{ old('email_env', $settings->settings['email_env'] ?? '') === 'live' ? 'checked' : '' }}>
                            <span class="custom-option-header">
                                <span class="h6 mb-0">Live Email</span>
                            </span>
                            <span class="custom-option-body">
                                <input type="text" class="form-control @error('live_email') is-invalid @enderror" placeholder="Live Email" name="live_email" value="{{ old('live_email', $settings->settings['live_email'] ?? '') }}">
                                <span class="error invalid-feedback">{{ $errors->first('live_email') }}</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
