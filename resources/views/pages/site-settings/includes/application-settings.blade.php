<div class="row">
    <!-- Password Lock Settings -->
    <div class="col-md-12 mb-3">
        <h5 class="card-header">Password Lock Settings</h5>
        <div class="card-body">

            <div class="row">
                <!-- Failed Attempts Before Lock -->
                <div class="col-md-6 mb-3">
                    <label for="failed_attempts" class="form-label">Failed Attempts Before Lock</label>
                    <input type="number" class="form-control @error('failed_attempts') is-invalid @enderror"
                        id="failed_attempts" name="failed_attempts"
                        value="{{ old('failed_attempts', $settings->settings['failed_attempts'] ?? 5) }}">
                    @error('failed_attempts')
                        <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Force Password Change Days -->
                <div class="col-md-6 mb-3">
                    <label for="force_password_change_days" class="form-label">Force Password Change (Days)</label>
                    <input type="number" class="form-control @error('force_password_change_days') is-invalid @enderror"
                        id="force_password_change_days" name="force_password_change_days"
                        value="{{ old('force_password_change_days', $settings->settings['force_password_change_days'] ?? 90) }}">
                    @error('force_password_change_days')
                        <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                                <!-- Session idle timout  -->
                <div class="col-md-6 mb-3">
                    <label for="session_lifetime" class="form-label">Session LifeTime(mins)</label>
                    <input type="number" class="form-control @error('session_lifetime') is-invalid @enderror"
                        id="session_lifetime" name="session_lifetime"
                        value="{{ old('session_lifetime', $settings->settings['session_lifetime'] ?? 120) }}">
                    @error('session_lifetime')
                        <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>


            </div>
        </div>
    </div>
</div>
