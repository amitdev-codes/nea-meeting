@props(['title', 'icon' => 'bx-info-circle'])

<div {{ $attributes->merge(['class' => 'card mb-4']) }}>
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>{{ $title }}</div>
        {{ $actions ?? '' }}
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>