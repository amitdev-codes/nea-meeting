<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.user_id') }}" :value="$resource->user_id" />
    <x-resource.detail-item label="{{ __('field.meeting_id') }}" :value="$resource->meeting_id" />
    <x-resource.detail-item label="{{ __('field.notification_type') }}" :value="$resource->notification_type" />
    <x-resource.detail-item label="{{ __('field.message') }}" :value="$resource->message" />
    <x-resource.detail-item label="{{ __('field.is_read') }}" :value="$resource->is_read" />
</x-resource.detail-page>