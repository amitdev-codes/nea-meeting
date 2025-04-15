{{-- resources/views/components/media-display.blade.php --}}
@props([
    'model',
    'collection' => 'staffs',
    'conversion' => 'preview',
    'title' => 'Profile Image',
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
    $roundedClass = $rounded ? 'rounded-circle' : 'rounded';
    $media = $model->getFirstMedia($collection);
    $imageUrl = $media ? $media->getUrl($conversion) : '/assets/img/default/404.png';
@endphp

<div class="card mb-4">
    <h5 class="card-header">{{ $title }}</h5>
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
            <img src="{{ $imageUrl }}" alt="{{ $title }}" class="d-block {{ $roundedClass }}"
                height="{{ $size['height'] }}" width="{{ $size['width'] }}" />
            @if ($withDownload && $media)
                <div class="button-wrapper">
                    <a href="{{ $media->getUrl() }}" class="btn btn-primary me-2 mb-3" download>
                        <i class="bx bx-download d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Download Original</span>
                    </a>
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</div>
