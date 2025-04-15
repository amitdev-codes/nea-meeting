<!-- resources/views/components/forms/input-time.blade.php -->
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input id="{{ $id }}" name="{{ $name }}" type="text" value="{{ old($name, $value) }}"
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
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K", // 12-hour format with AM/PM (e.g., 01:30 PM)
                minuteIncrement: 5, // Adjust as needed
                // Remove time_24hr: true to enable 12-hour format
                // Add other Flatpickr options here if needed
            });
        });
    </script>
@endpush