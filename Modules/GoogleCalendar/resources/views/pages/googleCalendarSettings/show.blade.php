<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.is_enabled') }}" :value="$resource->is_enabled" />
    <x-resource.detail-item label="{{ __('field.google_calendar_id') }}" :value="$resource->google_calendar_id" />
    <x-resource.detail-item label="{{ __('field.client_id') }}" :value="$resource->client_id" />
    <x-resource.detail-item label="{{ __('field.client_secret') }}" :value="$resource->client_secret" />
    <x-resource.detail-item label="{{ __('field.redirect_uri') }}" :value="$resource->redirect_uri" />
    <x-resource.detail-item label="{{ __('field.service_account_json') }}" :value="$resource->service_account_json" />
    <x-resource.detail-item label="{{ __('field.auth_method') }}" :value="$resource->auth_method" />
    <x-resource.detail-item label="{{ __('field.organization_id') }}" :value="$resource->organization_id" />
</x-resource.detail-page>