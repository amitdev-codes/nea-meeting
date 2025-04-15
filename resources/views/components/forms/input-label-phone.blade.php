<div>
    <label for="{{ $attributes['label'] }}" class="form-label">
        {{ __('field.' . $attributes['label']) }}@if ($attributes['required'])
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group input-group-merge @error($attributes['name']) is-invalid @enderror">
        <span class="input-group-text">
            <img src="https://flagcdn.com/16x12/np.png" alt="Nepal" class="me-1" width="16" height="12">
            +977
        </span>
        <input type="tel" class="form-control @error($attributes['name']) is-invalid @enderror"
            placeholder="{{ __('label.enter_field', ['field' => __('field.' . $attributes['name'])]) }}"
            id="{{ $attributes['name'] }}" name="{{ $attributes['name'] }}"
            value="{{ old($attributes['name'], $attributes['value']) }}" pattern="[0-9]{10}" maxlength="10"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
            @if ($attributes['required']) required @endif>
    </div>
    <small class="text-muted">Enter 10 digits mobile number</small>
    <span class="error invalid-feedback">{{ $errors->first($attributes['name']) }}</span>
</div>
<script>
    document.querySelector('input[name="{{ $attributes['name'] }}"]').addEventListener('input', function(e) {
        let value = e.target.value;

        // Remove any non-numeric characters
        value = value.replace(/[^0-9]/g, '');

        // Limit to 10 digits
        if (value.length > 10) {
            value = value.slice(0, 10);
        }

        e.target.value = value;
    });
</script>
