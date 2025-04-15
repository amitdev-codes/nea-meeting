<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.meeting_id') }}" :value="$resource->meeting_id" />
    <x-resource.detail-item label="{{ __('field.user_id') }}" :value="$resource->user_id" />
    <x-resource.detail-item label="{{ __('field.is_required') }}" :value="$resource->is_required" />
    <x-resource.detail-item label="{{ __('field.attendance_status') }}" :value="$resource->attendance_status" />
    <x-resource.detail-item label="{{ __('field.invitation_sent_at') }}" :value="$resource->invitation_sent_at" />
    <x-resource.detail-item label="{{ __('field.response_at') }}" :value="$resource->response_at" />
    <x-resource.detail-item label="{{ __('field.notes') }}" :value="$resource->notes" />
</x-resource.detail-page>