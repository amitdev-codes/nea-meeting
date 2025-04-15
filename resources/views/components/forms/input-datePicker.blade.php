<div class="mb-3">
    @if ($type !== 'hidden')
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
        class="form-control @error($name) is-invalid @enderror" @if ($required) required @endif
        placeholder="{{ $placeholder }}" {{ $attributes }}>
    @error($name)
        <span class="error invalid-feedback">{{ $message }}</span>
    @enderror
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#{{ $id }}", {
                dateFormat: "Y-m-d",
                // Add other Flatpickr options here
            });
        });
    </script>
@endpush
