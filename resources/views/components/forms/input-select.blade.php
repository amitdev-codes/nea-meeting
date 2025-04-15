<div>
    <label for="{{ $name }}" class="form-label">
        {{ __($label ?? 'field.' . $name) }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <select 
        id="{{ $name }}"
        name="{{  $name }}"
        class="form-select @error($name) is-invalid @enderror"
       >

        
        <!-- Options -->
        @foreach ($options as $option)
            @if (!$multiple)
                <option 
                    value="{{ $option[0] }}"
                    {{ old($name, $value) === (string) $option[0] ? 'selected' : '' }}>
                    {{ $option[1] }}
                </option>
            @else
                <option 
                    value="{{ $option[0] }}"
                    {{ in_array((string) $option[0], old($name, $value) ?? []) ? 'selected' : '' }}>
                    {{ $option[1] }}
                </option>
            @endif
        @endforeach
    </select>

    @error($multiple ? $name . '[]' : $name)
        <span class="invalid-feedback" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>