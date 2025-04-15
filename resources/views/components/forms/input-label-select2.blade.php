<div data-select2-id>
    <label for="{{ $attributes['label'] }}" class="form-label">
        {{ __('field.' . $attributes['label']) }}@if ($attributes['required'])
            <span class="text-danger">*</span>
        @endif
    </label>
    <select {{ $attributes['multiple'] ? 'multiple' : '' }} id="{{ $attributes['name'] }}"
        name="{{ $attributes['multiple'] ? $attributes['name'] . '[]' : $attributes['name'] }}"
        class="form-select select2 @error($attributes['name']) is-invalid @enderror"
        @if ($attributes['required']) required @endif>
        @foreach ($attributes['options'] as $option)
            @if (!isset($attributes['multiple']))
                <option value="{{ $option[0] }}" @selected(old($attributes['name'], $attributes['value']) === $option[0])>{{ $option[1] }}</option>
            @else
                <option
                    {{ in_array($option[0], old($attributes['name'], $attributes['value']) ?? []) ? 'selected' : '' }}
                    value="{{ $option[0] }}"> {{ $option[1] }}
                </option>
            @endif
        @endforeach
    </select>

    <span
        class="error invalid-feedback">{{ $errors->first($attributes['multiple'] ? $attributes['name'] . '[]' : $attributes['name']) }}
    </span>
</div>
