@props(['label', 'value', 'type' => 'text'])

<div class="row mb-3">
    <div class="col-lg-3">
        <label>{{ $label }}:</label>
    </div>
    <div class="col-lg-9">
        @switch($type)
            @case('date')
              <div class="form-control-plaintext"> {{ $value ? $value->format('M d, Y') : '-' }}</div>
            @break

            @case('datetime')
            <div class="form-control-plaintext">   {{ $value ? $value->format('M d, Y H:i') : '-' }}</div>

              
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
                    <img src="{{ $value }}" alt="Image" class="img-fluid rounded" style="max-height: 100px">
                @else
                    -
                @endif
            @break

            @default
            <div class="form-control-plaintext">    {{ $value ?? '-' }}</div>

              
        @endswitch
    </div>
</div>
