<div class="mb-3 col-md-3">
    @if ($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <input type="text" name="{{ $name }}" id="{{ $id }}"  class="form-control @error($name) is-invalid @enderror"
           placeholder="{{ $placeholder }}" value="{{ old($name, $value) }}" 
           readonly @if ($required) required @endif {{ $attributes }}>
        @error($name)
           <span class="error invalid-feedback">{{ $message }}</span>
       @enderror
</div>

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            new NepaliDatePicker('{{ $id }}', {
                currentYear: {{ $currentNepaliYear }},
                currentMonth: {{ $currentNepaliMonth }},
                csrfToken: '{{ csrf_token() }}',
                nepaliMonths: @json(\App\Helpers\NepaliDateConverter::$nepaliMonths),
                toNepaliDigits: function(number) {
                    const digits = @json(\App\Helpers\NepaliDateConverter::$nepaliDigits);
                    return String(number).split('').map(d => digits[d] || d).join('');
                }
            });
        });
    </script>
@endpush