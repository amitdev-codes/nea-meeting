<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.name') }}" :value="$resource->name" />
    <x-resource.detail-item label="{{ __('field.location') }}" :value="$resource->location" />
    <x-resource.detail-item label="{{ __('field.capacity') }}" :value="$resource->capacity" />
    <x-resource.detail-item label="{{ __('field.has_projector') }}" :value="$resource->has_projector" />
    <x-resource.detail-item label="{{ __('field.has_video_conference') }}" :value="$resource->has_video_conference" />
    <x-resource.detail-item label="{{ __('field.notes') }}" :value="$resource->notes" />
</x-resource.detail-page>