{{-- resources/views/components/image-display.blade.php --}}
@props([
    'model',
    'imageField' => 'image',
    'title' => 'Profile Image',
    'defaultImage' => 'assets/img/avatars/default.png',
    'dimension' => 'lg', // sm, md, lg, xl
    'rounded' => true,
    'withDownload' => false,
])

@php
    $dimensions = [
        'sm' => ['width' => '100', 'height' => '100'],
        'md' => ['width' => '150', 'height' => '150'],
        'lg' => ['width' => '200', 'height' => '200'],
        'xl' => ['width' => '300', 'height' => '300'],
    ];

    $size = $dimensions[$dimension] ?? $dimensions['md'];
    $imageUrl = $model->{$imageField . '_url'} ?? asset($defaultImage);
    $roundedClass = $rounded ? 'rounded-circle' : 'rounded';
@endphp

<div class="card mb-4">
    <h5 class="card-header">{{ $title }}</h5>
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
            <img src="{{ $imageUrl }}" alt="{{ $title }}" class="d-block {{ $roundedClass }}"
                height="{{ $size['height'] }}" width="{{ $size['width'] }}" />
            @if ($withDownload && $model->{$imageField})
                <div class="button-wrapper">
                    <a href="{{ $imageUrl }}" class="btn btn-primary me-2 mb-3"
                        download="{{ Str::slug($title) }}.{{ pathinfo($model->{$imageField}, PATHINFO_EXTENSION) }}">
                        <i class="bx bx-download d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Download</span>
                    </a>
                    @if ($slot->isNotEmpty())
                        {{ $slot }}
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
