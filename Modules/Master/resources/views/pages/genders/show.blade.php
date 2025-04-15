<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('master::gender.code') }}" :value="$resource->code" />
    <x-resource.detail-item label="{{ __('master::gender.name') }}" :value="$resource->name" />
    <x-resource.detail-item label=" {{ __('master::gender.name_np') }}" :value="$resource->name_np" />
</x-resource.detail-page>
