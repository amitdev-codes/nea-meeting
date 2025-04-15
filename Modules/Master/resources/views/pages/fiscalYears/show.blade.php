<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('master::fiscalYear.code') }}" :value="$resource->code" />
    <x-resource.detail-item label="{{ __('master::fiscalYear.date_from_bs') }}" :value="$resource->date_from_bs" />
    <x-resource.detail-item label=" {{ __('master::fiscalYear.date_to_bs') }}" :value="$resource->date_to_bs" />
</x-resource.detail-page>
