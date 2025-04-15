<div>
    <label for="{{ $attributes['name'] }}" class="form-label">
        {{ __('field.' . $attributes['name']) }}@if($attributes['required'])<span class="text-danger">*</span> @endif
    </label>
    <textarea
        class="form-control @error($attributes['name']) is-invalid @enderror"
        id="{{ $attributes['name'] }}"
        name="{{ $attributes['name'] }}"
        @if($attributes['required']) required @endif
        placeholder="{{ __('label.enter_field', ['field' => __('field.' . $attributes['name'])]) }}"
        rows="3">{!! old($attributes['name'], $attributes['value']) !!}</textarea>
    <span class="error invalid-feedback">{{ $errors->first($attributes['name']) }}</span>
</div>
