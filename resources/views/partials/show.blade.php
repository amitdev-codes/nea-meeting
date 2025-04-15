@extends('layouts.contentNavbarLayout')
@section('content')
    <div class="flex-grow-1">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">{{ $title }} /</span> Details
        </h4>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $title }} Details</h5>
                        <div>
                            <a href="{{ route("admin.{$routeName}.edit", $model->id) }}" class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route("admin.{$routeName}.index") }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        @if (property_exists($model, 'image') || property_exists($model, 'avatar'))
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        <img src="{{ $model->image_url }}" alt="user-avatar" class="d-block rounded"
                                            height="100" width="100" />
                                    </div>
                                </div>
                            </div>
                        @endif

                        @yield('show-fields')

                        <!-- Additional Information Card common in all as seen-->
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-header">Additional Information</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Created At:</label>
                                            {{ $model->created_at ? \Carbon\Carbon::parse($model->created_at)->format('d M Y H:i:s') : 'N/A' }}

                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Last Updated:</label>
                                            {{ $model->updated_at ? \Carbon\Carbon::parse($model->created_at)->format('d M Y H:i:s') : 'N/A' }}
                                        </div>
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
