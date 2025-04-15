<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.permission.name') }}" :value="$resource->name" />
    <x-resource.detail-item label=" {{ __('field.permission.name_np') }}" :value="$resource->name_np" />
</x-resource.detail-page>
