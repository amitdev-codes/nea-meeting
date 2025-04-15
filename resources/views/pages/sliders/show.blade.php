@extends('layouts/contentNavbarLayout')

@section('content')
    <x-breadcrumb title="Slider Preview" :items="[['label' => 'All Sliders', 'route' => 'admin.sliders.index'], ['label' => 'Slider Preview']]" />

    @php
        if ($slider->content_alignment == 'left' || $slider->content_alignment == '') {
            $align_class = 'align-self-start text-start';
        } elseif ($slider->content_alignment == 'center') {
            $align_class = 'align-self-center text-center';
        } else {
            $align_class = 'align-self-end text-end';
        }
    @endphp

    <div class="container-xxl">

    <div class="slider-section card mb-6">
        <img class="card-img" src="{{ $slider->preview_url }}" alt="{{ $slider->title }}">

        <div class="slider-img-overlay">
            <div class="slider-content col-12">
                <div class="row align-items-end">
                    <div class="{{ $align_class }}">
                        <div>
                            <<?= $slider->title_size ?>
                                style="color: {{ $slider->title_color }}; text-transform: {{ $slider->title_case }};">
                                {{ $slider->title }}
                            </<?= $slider->title_size ?>>
                        </div>

                        @if ($slider->subtitle)
                            <div class="mt-3">
                                <<?= $slider->subtitle_size ?? 'h3' ?>
                                    style="color: {{ $slider->subtitle_color }}; text-transform: {{ $slider->subtitle_case }};">
                                    {{ $slider->subtitle }}
                                </<?= $slider->subtitle_size ?? 'h3' ?>>
                            </div>
                        @endif

                        @if ($slider->url)
                            <div class="mt-3">
                                <a href="{{ $slider->url }}" class="slider-link-button">{{ $slider->url_text }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <div>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary me-2 text-white">
            <i class='bx bx-left-arrow me-2'></i>Back
        </a>
    </div>
@endsection
