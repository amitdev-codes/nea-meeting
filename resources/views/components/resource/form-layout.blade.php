@extends('layouts/contentNavbarLayout')
@props([
    'action' => '#',
    'method' => 'POST',
    'model' => null,
    'modelClass' => null
])
@section('content')
    <x-breadcrumb :model="$modelClass" :action="isset($model) ? 'edit' : 'create'" />

    <div class="row">
        <div class="col-md-12">
            <form action="{{ $action }}" method="POST">
                @csrf
                @if ($method === 'PUT')
                    @method('PUT')
                @endif
                <div class="card mb-6">
                    <div class="card-body pt-4">
                        {{-- body --}}
                        {{ $slot }}
                        {{-- footer --}}
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">
                                {{ isset($model) ? __('button.update') : __('button.submit') }}
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">{{ __('button.reset') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
