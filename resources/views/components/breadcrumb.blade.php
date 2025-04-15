@php
    $modelName = class_basename($modelInstance);
    $routePrefix = $resourceName ?? Str::plural(Str::kebab($modelName));

    // dd($modelName, $routePrefix);

    if (empty($items)) {
        $items = [
            [
                'label' => Str::plural($modelName),
                'route' => "admin.{$routePrefix}.index",
            ],
        ];

        if ($action === 'create') {
            $items[] = ['label' => "Add New {$modelName}"];
            $title = $title ?? __("field.add_new_{$routePrefix}");
        } elseif ($action === 'edit') {
            $items[] = ['label' => "Update {$modelName}"];
            $title = $title ?? __("field.update_{$routePrefix}");
        } elseif ($action === 'show') {
            $items[] = ['label' => "details"];
            // $title = $title ?? __("field.{$routePrefix}_details");
            // dd($modelName);
            $title = $title ??__('field.field') . ' ' . __('field.' . ucfirst($modelName)) . ' ' . __('field.details');
            // dd($title);

        } else {
            $title = $title ?? __("field.all_{$routePrefix}");
            // $items[] = 'dashboard';
        }
    }
    if (empty($items)) {
        $items = [];
        $title = $title ?? '';
    }
@endphp

<div class="container-xxl mb-4 border-bottom breadcrumb-div">
    <nav aria-label="breadcrumb" class="d-flex justify-content-between align-middle">
         <div class="d-flex align-items-center">
            <a href="javascript:history.back()" class=" me-2">
                <i class="bx bx-left-arrow-alt"></i>
            </a>
            <div class="my-0 font-weight-bold">{{ $title }}</div>
        </div> 
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">{{ __('field.home') }}</a>
            </li>
            @foreach ($items as $item)
                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                    @if (!$loop->last && isset($item['route']))
                        <a href="{{ route($item['route']) }}">
                            {{ isset($item['label']) ? __("field.{$item['label']}") : $item['label'] }}
                        </a>
                    @else
                    
                        {{ isset($item['label']) ? __("field.{$item['label']}") : $item['label'] }}
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</div>
