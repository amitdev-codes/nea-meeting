@extends('layouts/contentNavbarLayout')
@section('content')
    <div class="flex-grow-1">
        {{-- Breadcrumb --}}
        <x-breadcrumb title="Cumulative Progress Information" :items="[['label' => 'Group Information', 'route' => 'admin.cumulative-progress.index']]" />


        <div class="row">
            {{-- Main Information --}}
            <div class="col-xl-8 col-lg-7">
                <x-resource.detail-card title="{{ __('field.group_information') }}" icon="bx-group">
                    <x-slot name="actions">
                        @can('edit cumulative-progress')
                            <a href="{{ route('admin.cumulative-progress.edit', $resource) }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-edit me-1"></i> Edit
                            </a>
                        @endcan
                    </x-slot>
                    <x-resource.detail-item label="{{ __('field.fiscal_year') }}" :value="$resource->fiscal_year->code" />
                    <x-resource.detail-item label="{{ __('field.project_start_date') }}" :value="$resource->project_start_date" />
                    <x-resource.detail-item label="{{ __('field.project_end_date') }}" :value="$resource->project_end_date" />
                    <x-resource.detail-item label="{{ __('field.total_estimated_expenditure') }}" :value="$resource->total_estimated_expenditure" />
                    <x-resource.detail-item label="{{ __('field.total_given_expenditure') }}" :value="$resource->total_given_expenditure" />
                    <x-resource.detail-item label="{{ __('field.total_budget') }}" :value="$resource->total_budget" />
                    <x-resource.detail-item label="{{ __('field.total_disbursed') }}" :value="$resource->total_disbursed" />
                    <x-resource.detail-item label="{{ __('field.total_group_formed_target') }}" :value="$resource->total_group_formed_target" />
                </x-resource.detail-card>
            </div>
        </div>
    </div>
@endsection
