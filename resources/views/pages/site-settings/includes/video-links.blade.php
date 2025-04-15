<div class="row">
    <div class="col-md-12 mb-3">
        <label for="video_links" class="form-label">{{ __('field.video_links') }}</label>
        <div class="table-responsive text-nowrap">
            <table class="table table-sm table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th class="py-2">Platform</th>
                        <th class="py-2">Video ID</th>
                        <th class="py-2">Link Type</th>
                        <th class="py-2 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="video_links_container">
                    @if(empty(old('video_links', $settings->settings['video_links'] ?? [])))
                        <tr class="no-links-row">
                            <td colspan="4" class="text-center">
                                <span>Click "Add New Link" to add a video link.</span>
                            </td>
                        </tr>
                    @else
                        @foreach(old('video_links', $settings->settings['video_links'] ?? []) as $index => $link)
                        <tr class="video-link-row">
                            <td class="ps-2">
                                <select class="form-control" name="video_links[{{ $index }}][platform]" 
                                    {{ $errors->has('video_links.'.$index.'.platform') ? 'is-invalid' : '' }}>
                                    <option value="">{{ __('Select Platform') }}</option>
                                    <option value="youtube" {{ $link['platform'] == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                    <option value="facebook" {{ $link['platform'] == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="tiktok" {{ $link['platform'] == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                    <option value="vimeo" {{ $link['platform'] == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                                </select>
                                @error('video_links.'.$index.'.platform')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td class="ps-2">
                                <input type="text" class="form-control" placeholder="Video ID" 
                                    name="video_links[{{ $index }}][videoId]" 
                                    value="{{ $link['videoId'] }}" 
                                    {{ $errors->has('video_links.'.$index.'.videoId') ? 'is-invalid' : '' }}>
                                @error('video_links.'.$index.'.videoId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td class="ps-2">
                                <select class="form-control" name="video_links[{{ $index }}][linkType]"
                                    {{ $errors->has('video_links.'.$index.'.linkType') ? 'is-invalid' : '' }}>
                                    <option value="">{{ __('Select link type') }}</option>
                                    <option value="Internal" {{ $link['linkType'] == 'Internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="External" {{ $link['linkType'] == 'External' ? 'selected' : '' }}>External</option>
                                </select>
                                @error('video_links.'.$index.'.linkType')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td class="text-end col-1">
                                <button type="button" class="btn btn-icon btn-outline-danger remove-video-link">
                                    <i class='bx bx-trash-alt'></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot class="table-border-bottom-0">
                    <tr>
                        <td class="text-center ps-2" colspan="4">
                            <button type="button" class="btn btn-link" id="add_video_link">
                                <i class="bx bx-plus-circle me-1"></i>Add New Link
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('video_links_container');
        const addButton = document.getElementById('add_video_link');
        let linkIndex = {{ count(old('video_links', $settings->settings['video_links'] ?? [])) }};

        // Add new video link
        addButton.addEventListener('click', function() {
            // Remove the "no links" row if it exists
            const noLinksRow = container.querySelector('.no-links-row');
            if (noLinksRow) {
                noLinksRow.remove();
            }

            const newRow = document.createElement('tr');
            newRow.className = 'video-link-row';
            newRow.innerHTML = `
                <td class="ps-2">
                    <select class="form-control" name="video_links[${linkIndex}][platform]">
                        <option value="">{{ __('Select Platform') }}</option>
                        <option value="youtube">YouTube</option>
                        <option value="facebook">Facebook</option>
                        <option value="tiktok">TikTok</option>
                        <option value="vimeo">Vimeo</option>
                    </select>
                </td>
                <td class="ps-2">
                    <input type="text" class="form-control" placeholder="Video ID" name="video_links[${linkIndex}][videoId]">
                </td>
                <td class="ps-2">
                    <select class="form-control" name="video_links[${linkIndex}][linkType]">
                        <option value="">{{ __('Select link type') }}</option>
                        <option value="Internal">Internal</option>
                        <option value="External">External</option>
                    </select>
                </td>
                <td class="text-end col-1">
                    <button type="button" class="btn btn-icon btn-outline-danger remove-video-link">
                        <i class='bx bx-trash-alt'></i>
                    </button>
                </td>
            `;
            container.appendChild(newRow);
            linkIndex++;

            // Add event listener to the new remove button
            const removeButton = newRow.querySelector('.remove-video-link');
            removeButton.addEventListener('click', removeVideoLink);
        });

        // Function to remove video link
        function removeVideoLink() {
            this.closest('tr').remove();
            
            // If no links left, add the "no links" row
            if (container.querySelectorAll('.video-link-row').length === 0) {
                const noLinksRow = document.createElement('tr');
                noLinksRow.className = 'no-links-row';
                noLinksRow.innerHTML = `
                    <td colspan="4" class="text-center">
                        <span>Click "Add New Link" to add a video link.</span>
                    </td>
                `;
                container.appendChild(noLinksRow);
            }
        }

        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-video-link').forEach(button => {
            button.addEventListener('click', removeVideoLink);
        });
    });
</script>
@endpush