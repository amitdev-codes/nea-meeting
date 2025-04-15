<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('master::lengthUnit.code') }}" :value="$resource->code" />
    <x-resource.detail-item label="{{ __('master::lengthUnit.name') }}" :value="$resource->name" />
    <x-resource.detail-item label=" {{ __('master::lengthUnit.name_np') }}" :value="$resource->name_np" />
</x-resource.detail-page>
