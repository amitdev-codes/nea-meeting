<x-resource.detail-page :resource="$resource">
    <x-resource.detail-item label="{{ __('field.code') }}" :value="$resource->code ?? 'N/A'" />
    <x-resource.detail-item label="{{ __('field.name') }}" :value="$resource->name" />
    <x-resource.detail-item label="{{ __('field.name_np') }}" :value="$resource->name_np ?? 'N/A'" />

    <!-- Permissions Section -->
    <div class="mt-4">
        <h5 class="mb-3">{{ __('field.permissions') }}</h5>
        @if (!empty($permissions))
            <div class="d-flex flex-wrap gap-4">
                <!-- View Permissions -->
                <div class="flex-fill">
           
                    <ul class="list-unstyled mt-2">
                        @foreach ($permissions as $permission)
                            @if (Str::startsWith($permission, 'view-'))
                                <li>{{ $permission }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <!-- Create Permissions -->
                <div class="flex-fill">
                 
                    <ul class="list-unstyled mt-2">
                        @foreach ($permissions as $permission)
                            @if (Str::startsWith($permission, 'create-'))
                                <li>{{ $permission }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <!-- Edit Permissions -->
                <div class="flex-fill">
             
                    <ul class="list-unstyled mt-2">
                        @foreach ($permissions as $permission)
                            @if (Str::startsWith($permission, 'edit-'))
                                <li>{{ $permission }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <!-- Delete Permissions -->
                <div class="flex-fill">
      
                    <ul class="list-unstyled mt-2">
                        @foreach ($permissions as $permission)
                            @if (Str::startsWith($permission, 'delete-'))
                                <li>{{ $permission }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <p class="text-muted">{{ __('field.permissions.none') }}</p>
        @endif
    </div>
</x-resource.detail-page>

@push('styles')
    <style>
        .flex-fill {
            min-width: 0; /* Prevents overflow */
            flex: 1; /* Equal width for all columns */
        }
        .list-unstyled li {
            padding: 0.3rem 0;
            font-size: 0.9rem;
            color: #555;
        }
        .d-flex {
            gap: 1.5rem; /* Space between columns */
        }
        @media (max-width: 768px) {
            .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
@endpush