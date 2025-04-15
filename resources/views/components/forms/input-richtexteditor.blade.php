<div>
    <label for="{{ $attributes['name'] }}" class="form-label">{{ __('field.' . $attributes['name']) }}</label>
    <div id="{{ $attributes['name'] }}"
        class="form-control rich-text-editor @error($attributes['name']) is-invalid @enderror" style="min-height: 200px;">
        {{ $slot }}
    </div>
    <input type="hidden" name="{{ $attributes['name'] }}" value="">
    <span class="error invalid-feedback">{{ $errors->first($attributes['name']) }}</span>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var quill = new Quill('#{{ $attributes['name'] }}', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{
                            'font': []
                        }, {
                            'size': []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'script': 'sub'
                        }, {
                            'script': 'super'
                        }],
                        [{
                            'header': '1'
                        }, {
                            'header': '2'
                        }, 'blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }, {
                            'align': []
                        }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }
            });

            var hiddenInput = document.querySelector('input[name="{{ $attributes['name'] }}"]');
            hiddenInput.value = quill.root.innerHTML;

            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
            });

            @if ($errors->has($attributes['name']))
                var editor = document.querySelector('#{{ $attributes['name'] }}');
                var toolbar = editor.previousElementSibling;
                // editor.classList.add('is-invalid-quill');
                toolbar.classList.add('is-invalid-quill');
            @endif
        });
    </script>
@endpush
