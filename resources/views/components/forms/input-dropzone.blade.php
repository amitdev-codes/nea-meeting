@php
    $maxFiles = $maxFiles ?? 1;
    $maxFileSize = $maxFileSize ?? 5;
    $mediaName = $mediaName ?? 'images';
@endphp

<div class="col-md-12 mb-3">
    <div class="form-group">
        <label class="form-label" for="{{ $name }}">
            {{ __('field.' . $attributes['name']) }}@if ($attributes['required'])
                <span class="text-danger">*</span>
            @endif
            <i class="bi bi-question-circle-fill text-info" data-toggle="tooltip" data-placement="top"
                title="Max Files: {{ $maxFiles }}, Max File Size: {{ $maxFileSize }} MB">
            </i>
        </label>
        <div class="form-control dropzone d-flex flex-wrap align-items-center justify-content-center"
            id="{{ $name }}-dropzone">
            <div class="dz-message" data-dz-message>
                <i class='bx bx-cloud-upload bx-lg'></i>
                <h5>Drop files here or click to upload</h5>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="module">
        Dropzone.autoDiscover = false;
        const {{ $name }}UploadedDocumentMap = {};
        let {{ $name }}TotalUploadedFiles = 0;

        const {{ $name }}Dropzone = new Dropzone("#{{ $name }}-dropzone", {
            url: '{{ route('admin.dropzone.upload') }}',
            maxFilesize: {{ $maxFileSize }}, // Max file size in MB
            acceptedFiles: '.jpg, .jpeg, .png, .pdf, .txt',
            maxFiles: {{ $maxFiles }}, // Max files allowed
            uploadMultiple: false,
            addRemoveLinks: true,
            dictRemoveFile: "<i class='bi bi-x-circle text-danger'></i> remove",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            thumbnailWidth: 120,
            thumbnailHeight: 120,
            init: function() {
                const dropzone = this;
                const {{ $name }}UpdateTotalFiles = (delta) => {
                    {{ $name }}TotalUploadedFiles += delta;
                    {{ $name }}TotalUploadedFiles >= this.options.maxFiles ? this.disable() : this.enable();
                };

                // Get appropriate file icon based on file extension
                const getFileIcon = (fileName) => {
                    const extension = fileName.split('.').pop().toLowerCase();
                    switch(extension) {
                        case 'pdf':
                            return '/assets/img/pdf-icon.png';
                        case 'txt':
                            return '/assets/img/txt-icon.png';
                        case 'doc':
                        case 'docx':
                            return '/assets/img/doc-icon.png';
                        case 'xls':
                        case 'xlsx':
                            return '/assets/img/xls-icon.png';
                        default:
                            return '/assets/img/file-icon.png';
                    }
                };

                // Handle existing files for edit mode
                @if (isset($model) && $model->getMedia($mediaName)->count() > 0)
                    var existingFiles = {!! json_encode($model->getMedia($mediaName)->map(function($media) {
                        return [
                            'name' => $media->file_name,
                            'size' => $media->size,
                            'type' => $media->mime_type,
                            'url' => $media->getUrl(),
                            'preview_url' => $media->getUrl('preview'),
                            'thumb_url' => $media->getUrl('thumb'),
                            'file_name' => $media->file_name,
                            'id' => $media->id
                        ];
                    })) !!};
                    
                    for (var i = 0; i < existingFiles.length; i++) {
                        var existingFile = existingFiles[i];
                        
                        // Create mock file
                        var mockFile = {
                            name: existingFile.name,
                            size: existingFile.size,
                            accepted: true,
                            kind: existingFile.type.startsWith('image/') ? 'image' : 'file',
                            file_name: existingFile.file_name,
                            media_id: existingFile.id
                        };
                        
                        // Call the default addedfile event handler
                        dropzone.emit("addedfile", mockFile);
                        
                        // Use thumbnail URL if it's an image, otherwise use appropriate icon
                        if (existingFile.type.startsWith('image/')) {
                            dropzone.emit("thumbnail", mockFile, existingFile.thumb_url);
                        } else {
                            const iconPath = getFileIcon(existingFile.name);
                            dropzone.emit("thumbnail", mockFile, iconPath);
                            
                            // Add file type indicator class
                            if (mockFile.previewElement) {
                                mockFile.previewElement.classList.add('dz-file-preview');
                                mockFile.previewElement.classList.add('dz-' + existingFile.name.split('.').pop().toLowerCase());
                            }
                        }
                        
                        // Mark as complete
                        dropzone.emit("complete", mockFile);
                        
                        // Hidden input for existing files
                        $('form').append('<input type="hidden" name="{{ $name }}_existing[]" value="' + existingFile.id + '">');
                        
                        {{ $name }}UpdateTotalFiles(1);
                    }
                @endif

                // File added event - set appropriate icon for non-image files
                this.on("addedfile", function(file) {
                    if (!file.type.startsWith('image/')) {
                        const iconPath = getFileIcon(file.name);
                        
                        // We need to wait a bit for the thumbnail element to be created
                        setTimeout(() => {
                            const thumbElement = file.previewElement.querySelector('.dz-image img');
                            if (thumbElement) {
                                thumbElement.src = iconPath;
                                
                                // Add file type class
                                file.previewElement.classList.add('dz-file-preview');
                                file.previewElement.classList.add('dz-' + file.name.split('.').pop().toLowerCase());
                            }
                        }, 100);
                    }
                });

                // Success event
                this.on("success", (file, response) => {
                    if (response?.name) {
                        $('form').append('<input type="hidden" name="{{ $name }}[]" value="' + response.name + '">');
                        {{ $name }}UploadedDocumentMap[file.name] = response.name;
                        {{ $name }}UpdateTotalFiles(1);
                    } else {
                        console.error("Failed to upload the file.");
                    }
                });

                // Remove file event
                this.on("removedfile", (file) => {
                    if (file.status === "error" || file.status === "canceled") {
                        file.previewElement.remove();
                        return;
                    }

                    // For existing media files
                    if (file.media_id) {
                        $('form').find('input[name="{{ $name }}_existing[]"][value="' + file.media_id + '"]').remove();
                        $('form').append('<input type="hidden" name="{{ $name }}_remove[]" value="' + file.media_id + '">');
                        {{ $name }}UpdateTotalFiles(-1);
                    } 
                    // For newly uploaded files
                    else {
                        var name = {{ $name }}UploadedDocumentMap[file.name];
                        $('form').find('input[name="{{ $name }}[]"][value="' + name + '"]').remove();
                        
                        // Delete from temp storage
                        $.post("{{ route('admin.dropzone.delete') }}", {
                            '_token': "{{ csrf_token() }}",
                            'file_name': name
                        }).done(() => {
                            {{ $name }}UpdateTotalFiles(-1);
                        }).fail((error) => {
                            console.error("Failed to delete the file:", error);
                        });
                    }
                });

                // Error handling
                this.on("error", (file, message) => {
                    console.error(message);
                    const errorElement = file.previewElement.querySelector('.dz-error-message span');
                    if (errorElement) {
                        errorElement.textContent = typeof message === 'string' ? message : 'Upload failed';
                    }
                });
            }
        });
    </script>
@endpush

@push('styles')
<style>
/* Style file preview thumbnails better */
.dropzone .dz-preview .dz-image {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 8px;
}

/* Add specific styling for file types */
.dropzone .dz-preview.dz-pdf .dz-image {
    background-color: rgba(255, 230, 230, 0.3);
}
.dropzone .dz-preview.dz-txt .dz-image {
    background-color: rgba(230, 230, 255, 0.3);
}
.dropzone .dz-preview.dz-doc .dz-image,
.dropzone .dz-preview.dz-docx .dz-image {
    background-color: rgba(230, 240, 255, 0.3);
}
.dropzone .dz-preview.dz-xls .dz-image,
.dropzone .dz-preview.dz-xlsx .dz-image {
    background-color: rgba(230, 255, 230, 0.3);
}

/* Ensure icon size is appropriate */
.dropzone .dz-preview.dz-file-preview .dz-image img {
    max-width: 75%;
    max-height: 75%;
    object-fit: contain;
}
</style>
@endpush