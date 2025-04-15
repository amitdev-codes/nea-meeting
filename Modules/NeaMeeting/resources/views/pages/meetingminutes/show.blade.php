<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.meeting_id') }}" :value="$resource->meeting_id" />
    <x-resource.detail-item label="{{ __('field.content') }}" :value="$resource->content" />
    <x-resource.detail-item label="{{ __('field.recorded_by') }}" :value="$resource->recorded_by" />
    <x-resource.detail-item label="{{ __('field.approved') }}" :value="$resource->approved" />
    <x-resource.detail-item label="{{ __('field.approved_by') }}" :value="$resource->approved_by" />
    <x-resource.detail-item label="{{ __('field.approved_at') }}" :value="$resource->approved_at" />
</x-resource.detail-page>