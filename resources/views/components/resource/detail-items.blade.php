@props([
    'label',
    'value' => null,
    'id' => null,
    'class' => '',
    'type' => 'text' // Supports 'text', 'badge', 'boolean', or 'link'
])

<div {{ $attributes->merge(['class' => "detail-item $class"]) }} @if($id) id="{{ $id }}" @endif>
    <label class="form-label">{{ $label }}</label>
    @if($type === 'badge')
        <span class="badge bg-primary {{ $value === __('field.active') ? 'bg-success' : 'bg-secondary' }}">
            {{ $value ?? $slot }}
        </span>
    @elseif($type === 'boolean')
        <p class="form-control-plaintext">
            {{ is_bool($value) ? ($value ? __('Yes') : __('No')) : ($value ?? $slot) }}
        </p>
    @elseif($type === 'link')
        <p class="form-control-plaintext">
            @if($value && $value !== 'N/A')
                <a href="{{ $value }}" id="{{ $id }}-link" target="_blank" class="text-primary">{{ $value }}</a>
            @else
                <span id="{{ $id }}-text">{{ $value ?? __('N/A') }}</span>
            @endif
        </p>
    @else
        <p class="form-control-plaintext">
            {{ $value ?? $slot }}
        </p>
    @endif
</div>