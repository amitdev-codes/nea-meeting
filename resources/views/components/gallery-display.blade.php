{{-- resources/views/components/gallery-display.blade.php --}}
@props([
    'model',
    'imageField' => 'images',
    'title' => 'Gallery',
    'dimension' => 'md', // sm, md, lg, xl
    'rounded' => false,
    'withDownload' => false,
])

@php
    $dimensions = [
        'sm' => ['width' => '150', 'height' => '150'],
        'md' => ['width' => '200', 'height' => '200'],
        'lg' => ['width' => '250', 'height' => '250'],
        'xl' => ['width' => '300', 'height' => '300'],
    ];

    $size = $dimensions[$dimension] ?? $dimensions['md'];
    $images = is_array($model->{$imageField}) ? $model->{$imageField} : json_decode($model->{$imageField}, true);
    $roundedClass = $rounded ? 'rounded-circle' : 'rounded';
@endphp

<div class="card mb-4">
    <h5 class="card-header">{{ $title }}</h5>
    <div class="card-body">
        <div class="row g-3">
            @if (is_array($images) && count($images) > 0)
                @foreach ($images as $index => $image)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="image-wrapper position-relative">
                            <img src="{{ Storage::url($image) }}" alt="{{ $title }} {{ $index + 1 }}"
                                class="d-block {{ $roundedClass }} img-fluid"
                                style="width: {{ $size['width'] }}px; height: {{ $size['height'] }}px; object-fit: cover;" />
                            @if ($withDownload)
                                <div
                                    class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ Storage::url($image) }}" class="btn btn-primary btn-sm"
                                        download="{{ Str::slug($title . '-' . ($index + 1)) }}.{{ pathinfo($image, PATHINFO_EXTENSION) }}">
                                        <i class="bx bx-download"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        <i class="bx bx-info-circle me-1"></i>
                        No images available
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
