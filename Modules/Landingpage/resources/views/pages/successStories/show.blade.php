@extends('landingpage::layouts.frontMaster')

@section('navbar')
    @php
        $locale = Session::get('locale');
        App::setLocale($locale);
    @endphp
    @include('landingpage::layouts.navbar-front')
@endsection

@section('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="content-wrapper">
            <div data-bs-spy="scroll" class="scrollspy-example" style="padding-top: 8rem"> <!-- Reduced padding -->
                <div class="container-xl py-4"> <!-- Adjusted padding -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm"> <!-- Sneat’s card with subtle shadow -->
                                <div class="card-header bg-transparent border-0 py-3 px-4"> <!-- Clean header -->
                                    <h3 class="mb-0 fw-bold fs-4">{{ $story->title }}</h3> <!-- Bold, slightly smaller -->
                                </div>
                                <div class="card-body px-4 py-3"> <!-- Adjusted padding -->
                                    <!-- Media Section -->
                                    @if ($story->thumbnail)
                                        <div class="mb-4 text-center">
                                            <img src="{{ asset('storage/' . $story->thumbnail) }}" 
                                                 class="img-fluid rounded" 
                                                 alt="{{ $story->title }}" 
                                                 style="max-height: 300px; object-fit: cover;">
                                        </div>
                                    @endif

                                    <!-- Story Details -->
                                    <div class="row g-3"> <!-- Grid for details -->
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong class="fw-medium">Type:</strong> 
                                                <span class="text-muted">{{ $story->type_content }}</span>
                                            </p>
                                            @if ($story->address)
                                                <p class="mb-2">
                                                    <strong class="fw-medium">Address:</strong> 
                                                    <span class="text-muted">{{ $story->address }}</span>
                                                </p>
                                            @endif
                                            <p class="mb-2">
                                                <strong class="fw-medium">Published on:</strong> 
                                                <small class="text-muted">{{ $story->created_at->format('F j, Y') }}</small>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong class="fw-medium">Description:</strong>
                                            </p>
                                            <div class="text-muted">{!! $story->description !!}</div>
                                        </div>
                                    </div>

                                    <!-- Back Button -->
                                    <div class="mt-4">
                                        <a href="{{ route('landing.successStories.index') }}" 
                                           class="btn btn-outline-secondary btn-sm">
                                            <i class="bx bx-arrow-back me-1"></i> Back to Stories
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div class="footer-wrapper" style="margin-top: 17%">
        @include('landingpage::layouts.footer-front')
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .card-header {
            padding: 1rem 1.5rem; /* Consistent with Sneat */
        }
        .card-body {
            padding: 1.5rem; /* Adjusted for balance */
        }
        .img-fluid.rounded {
            border: 1px solid #e0e0e0; /* Subtle border */
            transition: transform 0.2s;
        }
        .img-fluid.rounded:hover {
            transform: scale(1.02); /* Slight zoom on hover */
        }
        .text-muted {
            font-size: 0.95rem; /* Slightly smaller text */
        }
        .fw-medium {
            font-weight: 500; /* Sneat’s medium weight */
        }
    </style>
@endsection