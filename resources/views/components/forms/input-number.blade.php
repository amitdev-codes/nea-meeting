<div>
    <label for="{{ $attributes['name'] }}" class="form-label">
        {{ __('field.' . $attributes['name']) }}@if ($attributes['required'])
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group input-group-merge @error($attributes['name']) is-invalid @enderror">
        <input type="number" class="form-control @error($attributes['name']) is-invalid @enderror"
            placeholder="{{ __('label.enter_field', ['field' => __('field.' . $attributes['name'])]) }}" min="0"
            id="{{ $attributes['name'] }}" name="{{ $attributes['name'] }}"
            value="{{ old($attributes['name'], $attributes['value']) }}"
            @if ($attributes['required']) required @endif aria-label="Amount (to the nearest rupiah)">
    </div>
    <span class="error invalid-feedback">{{ $errors->first($attributes['name']) }}</span>
</div>
