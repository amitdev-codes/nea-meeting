@props([
    'route',
    'collection' => 'staffs',
    'maxFiles' => 1,
    'maxFilesize' => 2,
    'acceptedFiles' => 'image/*',
    'existingMedia' => null,
])

<div class="card mb-4">
    <h5 class="card-header">Upload Image</h5>
    <div class="card-body">
        <form action="{{ $route }}" class="dropzone" id="mediaDropzone">
            @csrf
            <div class="dz-message needsclick">
                <i class="bx bx-cloud-upload text-secondary mb-2" style="font-size: 3rem;"></i>
                <h5 class="mb-0">Drop files here or click to upload</h5>
                <p class="text-muted mb-0">Allowed {{ str_replace('/*', ' files', $acceptedFiles) }}</p>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        new Dropzone("#mediaDropzone", {
            url: "{{ $route }}",
            maxFiles: {{ $maxFiles }},
            maxFilesize: {{ $maxFilesize }}, // MB
            acceptedFiles: "{{ $acceptedFiles }}",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            init: function() {
                @if ($existingMedia)
                    let mockFile = {
                        name: "Existing Image",
                        size: {{ $existingMedia->size ?? 0 }}
                    };
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, "{{ $existingMedia->getUrl('thumb') }}");
                    this.emit("complete", mockFile);
                @endif
            },
            success: function(file, response) {
                if (response.success) {
                    toastr.success('Image uploaded successfully');
                    // Optional: Reload preview area
                    if (typeof updatePreview === 'function') {
                        updatePreview();
                    }
                }
            },
            error: function(file, message) {
                toastr.error(message);
                this.removeFile(file);
            }
        });
    </script>
@endpush
