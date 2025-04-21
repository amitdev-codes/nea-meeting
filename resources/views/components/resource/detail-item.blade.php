@props(['label', 'value', 'type' => 'text'])

<div class="row mb-3 align-items-center">
    <div class="col-lg-3 col-md-4 col-sm-12">
        <label class="fw-bold d-flex align-items-center">
            @if (isset($slot))
                {{ $slot }}
            @else
                <i class="bx bx-info-circle me-2"></i>
            @endif
            {{ $label }}:
        </label>
    </div>
    <div class="col-lg-9 col-md-8 col-sm-12">
        @switch($type)
            @case('date')
                <div class="form-control-plaintext">{{ $value ? \Carbon\Carbon::parse($value)->format('M d, Y') : '-' }}</div>
            @break

            @case('datetime')
                <div class="form-control-plaintext">{{ $value ? \Carbon\Carbon::parse($value)->format('M d, Y H:i') : '-' }}</div>
            @break

            @case('boolean')
                @if ($value)
                    <span class="badge bg-success">Yes</span>
                @else
                    <span class="badge bg-danger">No</span>
                @endif
            @break

            @case('html')
                {!! $value !!}
            @break

            @case('badge')
                <span class="badge bg-{{ $value['color'] ?? 'primary' }}">
                    {{ $value['text'] ?? $value }}
                </span>
            @break

            @case('image')
                @if ($value)
                    <img src="{{ $value }}" alt="Image" class="img-fluid rounded" style="max-height: 100px;">
                @else
                    -
                @endif
            @break

            @default
                <div class="form-control-plaintext">{{ $value ?? '-' }}</div>
        @endswitch
    </div>
</div>