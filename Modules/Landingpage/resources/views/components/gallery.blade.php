<!-- File: components/gallery.blade.php -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{ $title ?? 'Image Gallery' }}
            </div>
            <div class="card-body position-relative p-0">
                <!-- Left Arrow -->
                <button
                    class="btn position-absolute start-0 top-50 translate-middle-y z-1 bg-transparent border-0 p-0"
                    onclick="scrollGallery('-{{ $galleryId ?? 'gallery' }}')">
                    <span class="fs-1 text-muted">&larr;</span>
                </button>

                <!-- Gallery Container -->
                <div id="{{ $galleryId ?? 'gallery' }}" class="gallery-container d-flex overflow-hidden scroll-smooth gap-2 p-2"
                    style="scroll-behavior: smooth;">
                    @foreach ($images as $image)
                        <div class="flex-shrink-0 rounded" style="width: calc((100% - 1rem) / {{ $imagesPerRow ?? '6' }});">
                            <div class="card h-100">
                                <img src="{{ asset($image['path']) }}" alt="{{ $image['alt'] ?? 'Gallery Image' }}"
                                    class="card-img-top img-fluid rounded-top"
                                    style="height: {{ $imageHeight ?? '120px' }}; object-fit: cover;">
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Arrow -->
                <button
                    class="btn position-absolute end-0 top-50 translate-middle-y z-1 bg-transparent border-0 p-0"
                    onclick="scrollGallery('+{{ $galleryId ?? 'gallery' }}')">
                    <span class="fs-1 text-muted">&rarr;</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function scrollGallery(param) {
        const direction = param.charAt(0) === '+' ? 1 : -1;
        const galleryId = param.substring(1);
        const gallery = document.getElementById(galleryId);
        const scrollAmount = gallery.offsetWidth / 2 * direction;
        gallery.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
</script>