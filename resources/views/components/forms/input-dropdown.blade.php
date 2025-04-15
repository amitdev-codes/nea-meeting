<div>
    <label for="{{ $attributes['name'] }}" class="form-label">
        {{ __('field.' . $attributes['name']) }}@if($attributes['required'])<span class="text-danger">*</span> @endif
    </label>
    <select class="form-select @error($attributes['name']) is-invalid @enderror"
           id="{{ $attributes['name'] }}"
           name="{{ $attributes['name'] }}"
           @if($attributes['required']) required @endif>

           <option class="text-muted" value="" {{ old($attributes['name']) === null ? 'selected' : '' }}>
                Select {{ __('field.' . $attributes['name']) }}
            </option>

            @foreach($options as $option)
                <option
                    value="{{ $option[$attributes['option-value']] }}"
                    {{ old($attributes['name'], $attributes['value']) == $option[$attributes['option-value']] ? 'selected' : '' }}>
                    {{ $option[$attributes['option-label']] }}
                </option>
            @endforeach
    </select>
    <span class="error invalid-feedback">{{ $errors->first($attributes['name']) }}</span>
</div>

