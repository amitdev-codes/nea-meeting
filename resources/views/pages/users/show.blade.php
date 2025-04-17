@extends('layouts/contentNavbarLayout')
@section('content')
    <div class="flex-grow-1 p-4">
        {{-- Breadcrumb --}}
        <x-breadcrumb title="User Information" :items="[['label' => 'User Information', 'route' => 'admin.users.index']]" />

        <div class="row">
            {{-- Main Information --}}
            <div class="col-xl-8 col-lg-7">
                <x-resource.detail-card title="{{ __('field.user_information') }}" icon="bx-user">
                    <x-slot name="actions">
                        @can('edit users')
                            <a href="{{ route('admin.users.edit', $resource) }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-edit me-1"></i> Edit
                            </a>
                        @endcan
                    </x-slot>

                    <x-resource.detail-item label="{{ __('field.username') }}" :value="$resource->username" />
                    <x-resource.detail-item label="{{ __('field.email') }}" :value="$resource->email" />
                    <x-resource.detail-item label="{{ __('field.mobile_no') }}" :value="$resource->mobile_no" />
                    <x-resource.detail-item label="{{ __('field.organization_id') }}" :value="$resource->organizations->name" />
                    <x-resource.detail-item label="Role" :value="$resource->roles->pluck('name')->first()" type="badge" />
                    <x-resource.detail-item label="{{ __('field.status') }}" :value="$resource->status ? __('field.active') : __('field.inactive')" type="badge" />
                    <x-resource.detail-item label="{{ __('field.created_at') }}" :value="$resource->created_at" type="datetime" />
                    <x-resource.detail-item label="{{ __('field.updated_at') }}" :value="$resource->updated_at" type="datetime" />
                </x-resource.detail-card>
            </div>

            {{-- Sidebar Information --}}
            <div class="col-xl-4 col-lg-5">
                <x-resource.detail-card title="Profile Picture" icon="bx-image">
                    <div class="text-center">
                        <img src="{{ $resource->avatar_url ?? asset('assets/img/avatars/default.png') }}"
                            alt="Profile Picture" class="rounded-circle img-fluid mb-3" style="max-width: 150px">
                    </div>
                </x-resource.detail-card>
                <x-resource.detail-card title="Activity Log" icon="bx-history">
                    <div class="timeline">
                        @forelse($activities as $activity)
                            <div class="timeline-item mb-3">
                                <div class="timeline-point timeline-point-primary">
                                    <i class="bx bx-right-arrow-circle"></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="timeline-body">
                                        {{ $activity->description }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center mb-0">No recent activity</p>
                        @endforelse
                    </div>
                </x-resource.detail-card>
            </div>
        </div>
    </div>
@endsection
