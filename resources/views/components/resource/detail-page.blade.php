@props([
'resource',
'title' => null,
'icon' => 'bx-cog',
'editRoute' => null,
'editPermission' => null,
'showTimestamps' => true,
])

@php
$modelName = class_basename($resource);
$resourceName = Str::plural(Str::kebab($modelName));

// Auto-generate title if not provided
$title = $title ?? "{$modelName} Information";

// Auto-generate edit route if not provided
$editRoute = $editRoute ?? "admin.{$resourceName}.edit";

// Auto-generate permission if not provided
$editPermission = $editPermission ?? "edit {$resourceName}";
@endphp

@extends('layouts/contentNavbarLayout')

@section('content')
<div class="flex-grow-1">
    <x-breadcrumb :title="$title" :model="get_class($resource)" action="show" />
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12 col-lg-7">
                <x-resource.detail-card :title="$title" :icon="$icon">
                    {{ $slot }}
                    @if ($showTimestamps)
                    <x-resource.detail-item label="{{ __('field.Created At') }}" :value="$resource->created_at" type="datetime" />
                    <x-resource.detail-item label="{{ __('field.Last Updated') }}" :value="$resource->updated_at" type="datetime" />
                    @endif
                </x-resource.detail-card>
            </div>
        </div>
    </div>
</div>
@endsection