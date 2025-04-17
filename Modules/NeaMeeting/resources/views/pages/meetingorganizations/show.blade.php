<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.meeting_id') }}" :value="$resource->meeting_id" />
    <x-resource.detail-item label="{{ __('field.organization_id') }}" :value="$resource->organization_id" />
    <x-resource.detail-item label="{{ __('field.status') }}" :value="$resource->status ? 'Yes' : 'No'" />
</x-resource.detail-page>