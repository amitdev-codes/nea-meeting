<!-- File: components/country-details.blade.php -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{ $title ?? 'देश भरको विवरण' }}
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    @foreach($details as $detail)
                    <div class="mb-2 me-3 flex-fill" style="flex-basis: {{ $flexBasis ?? '23%' }};">{{ $detail }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>