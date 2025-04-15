<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.name') }}" :value="$resource->name" />
    <x-resource.detail-item label=" {{ __('field.name_np') }}" :value="$resource->name_np" />
    <x-resource.detail-item label="{{ __('field.description') }}" :value="$resource->description" />
    <x-resource.detail-item label="{{ __('field.status') }}" :value="$resource->status" />
</x-resource.detail-page>