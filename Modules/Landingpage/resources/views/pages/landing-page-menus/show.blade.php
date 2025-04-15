<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.parent_id') }}" :value="$resource->parent_id" />
    <x-resource.detail-item label="{{ __('field.name') }}" :value="$resource->name" />
    <x-resource.detail-item label="{{ __('field.icon') }}" :value="$resource->icon" />
    <x-resource.detail-item label="{{ __('field.url') }}" :value="$resource->url" />
    <x-resource.detail-item label="{{ __('field.is_active') }}" :value="$resource->is_active ? 'Yes' : 'No'" />
    <x-resource.detail-item label="{{ __('field.order_id') }}" :value="$resource->order_id" />
</x-resource.detail-page>