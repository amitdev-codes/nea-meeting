<div class="form-check form-switch">
    <label for="{{ $attributes['name'] }}" class="form-check-label">{{ __('field.' . $attributes['label']) }}</label>
    <input class="form-check-input @error($attributes['name']) is-invalid @enderror" id="{{ $attributes['name'] }}"
        type="checkbox" name="{{ $attributes['name'] }}" value="1"
        {{ old($attributes['name'], $attributes['value']) ? 'checked' : '' }}>
    <span class="error invalid-feedback">{{ $errors->first($attributes['name']) }}</span>
</div>

{{-- @props([
    'name',
    'label' => '',
    'checked' => false,
])

<div class="form-check form-switch">
    <label for="{{ $name }}" class="form-check-label">{{ __($label) }}</label>
    <input 
        class="form-check-input @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        type="checkbox" 
        name="{{ $name }}" 
        value="1" 
        {{ $checked ? 'checked' : '' }}>
    @error($name)
        <span class="error invalid-feedback">{{ $message }}</span>
    @enderror
</div> --}}
