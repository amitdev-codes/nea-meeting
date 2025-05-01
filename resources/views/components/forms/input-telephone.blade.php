<div>
    <label for="{{ $attributes['name'] }}" class="form-label">
        {{ __('field.' . $attributes['name']) }}@if ($attributes['required'])
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group input-group-merge @error($attributes['name']) is-invalid @enderror">
        <span class="input-group-text">
            <img src="https://flagcdn.com/16x12/np.png" alt="Nepal" class="me-1" width="16" height="12">
            +977 1
        </span>
        <input type="tel" class="form-control @error($attributes['name']) is-invalid @enderror"
            placeholder="{{ __('label.enter_field', ['field' => __('field.' . $attributes['name'])]) }}"
            id="{{ $attributes['name'] }}" name="{{ $attributes['name'] }}"
            value="{{ old($attributes['name'], $attributes['value']) }}"
            pattern="[1-9][0-9]{6,8}" maxlength="9"
            oninput="formatTelephone(this)"
            @if ($attributes['required']) required @endif>
    </div>
    <small class="text-muted">Enter 7 to 9-digit telephone number (e.g., 1234567)</small>
    <span class="error invalid-feedback">{{ $errors->first($attributes['name']) }}</span>
</div>
@push('scripts')
<script type="module">
    function formatTelephone(input) {
        // Remove non-numeric characters
        let value = input.value.replace(/[^0-9]/g, '');

        // Limit to 9 digits
        if (value.length > 9) {
            value = value.slice(0, 9);
        }

        // Ensure first digit is 1-9
        if (value.length > 0 && !/^[1-9]/.test(value)) {
            value = '';
        }

        // Set input value to clean numeric value
        input.value = value;

        // Store clean value in a hidden input or data attribute for submission
        input.setAttribute('data-clean-value', value);
    }
    const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function () {
                const telInput = document.querySelector('input[name="{{ $attributes['name'] }}"]');
                if (telInput && telInput.getAttribute('data-clean-value')) {
                    telInput.value = telInput.getAttribute('data-clean-value');
                }
            });
    }

</script>
@endpush