<div class="col-md-6">
    <div class="my-4">
        <div class="intro">{{ $title ?? 'Meet Our Key Officials' }}</div>
    </div>
    <div class="row officials-container justify-content-center">
        @foreach($officials as $official)
        <div class="col-md-4 official-item position-relative">
            <img src="{{ asset('img/officials/' . $official['img']) }}" alt="{{ $official['name'] }}" class="rounded me-3 official-img w-100">
            <div class="text-start position-absolute bottom-0 bg-white w-80 mx-4 px-3 py-1 mb-2 rounded-2">
                <h6 class="mb-0">{{ $official['name'] }}</h6>
                <p class="text-muted small mb-0">{{ $official['title'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>