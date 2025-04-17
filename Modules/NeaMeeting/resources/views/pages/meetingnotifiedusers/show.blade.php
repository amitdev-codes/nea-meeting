<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.meeting_id') }}" :value="$resource->meeting_id" />
    <x-resource.detail-item label="{{ __('field.user_id') }}" :value="$resource->user_id" />
    <x-resource.detail-item label="{{ __('field.notified_at') }}" :value="$resource->notified_at" />
    <x-resource.detail-item label="{{ __('field.notification_type') }}" :value="$resource->notification_type" />
    <x-resource.detail-item label="{{ __('field.notification_status') }}" :value="$resource->notification_status" />
</x-resource.detail-page>