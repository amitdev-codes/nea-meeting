{{-- @dump(old($attributes['name'])) --}}

{{-- @dump($errors) --}}

<div data-select2-id>
    <label for="{{ $attributes['name'] }}" class="form-label">{{ __('field.' . $attributes['name']) }}</label>
    <select id="{{ $attributes['name'] }}" name="{{ $attributes['name'] . '[]' }}" multiple
        class="form-select select2
        @error($attributes['name']) is-invalid @enderror
        @error($attributes['name'].'[]') is-invalid @enderror">
        @foreach ($attributes['options'] as $option)
            <option value="{{ $option }}" selected> {{ $option }} </option>
        @endforeach
    </select>

    <span
        class="error invalid-feedback">{{ $errors->first($attributes['name'])}}
    </span>

    @foreach ((old($attributes['name'])??[]) as $index => $phone)
        <span class="error is-invalid">{{ $errors->first($attributes['name'].'.'.$index) }}</span>
    @endforeach

</div>

    {{-- @dump($errors->first($attributes['name'])) --}}
    {{-- @dump($errors->first($attributes['name'].'[]')) --}}



    {{-- @if ($attributes['multiple'])
        @foreach (old($attributes['name'], $attributes['value']) ?? [] as $index => $attributes['name'])
            @error($attributes['name'] . '.' . $index)
                <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        @endforeach
    @endif --}}



{{-- @push('script')
<script>
 $(document).ready(function() {
        $('.select2').select2({
            placeholder: function() {
                console.log($(this).data('placeholder'));
                return $(this).data('placeholder');
            },
            allowClear: true,
            tags: {{ $attributes['multiple'] ? 'true' : 'false' }}, // Add tags if it's a multiple selection field
        });
    });
</script>
@endpush --}}
