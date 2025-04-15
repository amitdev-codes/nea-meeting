@extends('layouts/contentNavbarLayout')

@section('content')

    <x-breadcrumb
        title="{{ isset($slider) ? 'Edit Slider' : 'Create Slider' }}"
        :items="[
            ['label' => 'Sliders', 'route' => 'admin.sliders.index'],
            ['label' => "{{ isset($slider) ? 'Edit Slider' : 'Create Slider' }}"]]"
    />

    <form id="sliderForm" class="mb-3"
        action="{{ isset($slider) ? route('admin.sliders.update', $slider->id) : route('admin.sliders.store')  }}"
        enctype="multipart/form-data"
        method="POST">

        @csrf
        @if (isset($slider)) @method('PUT') @endif

        @php
            $text_size = [
                ['value' => 'p', 'label' => 'p'],
                ['value' => 'h1', 'label' => 'h1'],
                ['value' => 'h2', 'label' => 'h2'],
                ['value' => 'h3', 'label' => 'h3'],
                ['value' => 'h4', 'label' => 'h4'],
                ['value' => 'h5', 'label' => 'h5'],
            ];

            $text_case = [
                ['value' => 'uppercase', 'label' => 'Uppercase'],
                ['value' => 'capitalize', 'label' => 'Capitalize'],
                ['value' => 'lowercase', 'label' => 'Lowercase'],
            ];

            $content_align = [
                ['value' => 'left', 'label' => 'Left'],
                ['value' => 'center', 'label' => 'Center'],
                ['value' => 'right', 'label' => 'Right'],
            ];

            $link_type = [
                ['value' => 'Internal', 'label' => 'Internal'],
                ['value' => 'External', 'label' => 'External'],
            ];
        @endphp

        <div class="slider-form-section row g-6">
            <div class="col-lg-8 card-deck">
                <div class="card mb-6">
                    <div class="card-header mb-3">
                        <h6 class="card-title">Title Details</h6>
                    </div>
                    <div class="card-body">
                        @include('pages.sliders.partials.slider-title')
                    </div>
                </div>
                <div class="card">
                    <div class="card-header mb-3">
                        <h6 class="card-title">Subtitle Details</h6>
                    </div>
                    <div class="card-body">
                        @include('pages.sliders.partials.slider-subtitle')
                </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                <div class="card-header mb-3">
                    <h6 class="card-title">Link and Content Alignment</h6>
                </div>
                <div class="card-body">
                    @include('pages.sliders.partials.link-and-alignment')
                </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">{{ __('button.submit') }}</button>
            <button type="reset" class="btn btn-outline-secondary">{{ __('button.reset') }}</button>
        </div>
    </form>

@endsection
