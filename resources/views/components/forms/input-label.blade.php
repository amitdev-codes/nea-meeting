<div>
    @if ($attributes['type'] !== 'hidden')
        <label for="{{ $attributes['label'] }}" class="form-label">
            {{ __('field.' . $attributes['label']) }}
            @if ($attributes['required'])
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <input class="form-control @error($attributes['name']) is-invalid @enderror"
        type="{{ $attributes['type'] ?? 'text' }}" id="{{ $attributes['name'] }}" name="{{ $attributes['name'] }}"
        value="{!! old($attributes['name'], $attributes['value']) !!}" @if ($attributes['required']) required @endif
        placeholder="{{ __('label.enter_field', ['field' => __('field.' . $attributes['name'])]) }}"
        @if ($attributes['type'] === 'url') pattern="[Hh][Tt][Tt][Pp][Ss]?:\/\/(?:(?:[a-zA-Z\u00a1-\uffff0-9]+-?)*[a-zA-Z\u00a1-\uffff0-9]+)(?:\.(?:[a-zA-Z\u00a1-\uffff0-9]+-?)*[a-zA-Z\u00a1-\uffff0-9]+)*(?:\.(?:[a-zA-Z\u00a1-\uffff]{2,}))(?::\d{2,5})?(?:\/[^\s]*)?"
               oninvalid="this.setCustomValidity('{{ __('Please enter a valid URL') }}')"
               oninput="setCustomValidity('')" @endif />
    <span class="error invalid-feedback">{{ $errors->first($attributes['name']) }}</span>
</div>
