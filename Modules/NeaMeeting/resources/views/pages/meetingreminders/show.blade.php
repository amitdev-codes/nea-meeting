<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.meeting_id') }}" :value="$resource->meeting_id" />
    <x-resource.detail-item label="{{ __('field.user_id') }}" :value="$resource->user_id" />
    <x-resource.detail-item label="{{ __('field.reminder_time') }}" :value="$resource->reminder_time" />
    <x-resource.detail-item label="{{ __('field.sent') }}" :value="$resource->sent" />
    <x-resource.detail-item label="{{ __('field.sent_at') }}" :value="$resource->sent_at" />
</x-resource.detail-page>