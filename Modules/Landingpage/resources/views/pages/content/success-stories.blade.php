<div data-bs-spy="scroll" class="scrollspy-example" style="padding-top: 9rem">
    <div class="container-xl py-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent border-0 py-2">
                        <h4 class="mb-0 fw-bold fs-5">Success Stories</h4>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0 fs-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="ps-3 py-2 fw-medium">#</th>
                                        <th scope="col" class="py-2 fw-medium">Thumbnail</th>
                                        <th scope="col" class="py-2 fw-medium">Story ID</th>
                                        <th scope="col" class="py-2 fw-medium">Title</th>
                                        <th scope="col" class="py-2 fw-medium">Description</th>
                                        <th scope="col" class="text-end pe-3 py-2 fw-medium">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($stories as $index => $story)
                                        <tr>
                                            <td class="ps-3 py-1">{{ $stories->firstItem() + $index }}</td>
                                            <td class="py-1">
                                                @if ($story->hasMedia('stories'))
                                                    <img src="{{ $story->getFirstMediaUrl('stories', 'thumb') }}"
                                                        alt="{{ $story->title }}" class="rounded-circle"
                                                        style="width: 30px; height: 30px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td class="py-1">{{ $story->story_id }}</td>
                                            <td class="py-1">{{ $story->title }}</td>
                                            <td class="py-1">{{ Str::limit(strip_tags($story->description), 100) }}
                                            </td>
                                            <td class="text-end pe-3 py-1">
                                                <a href="{{ route('landing.successStories.show', $story->story_id) }}"
                                                    class="btn btn-icon btn-outline-primary btn-sm" title="View Story">
                                                    <i class="bx bx-show fs-5"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-2">
                                                <div class="alert alert-info mb-0 fs-sm">No success stories available
                                                    yet.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($stories->hasPages())
                        <div class="card-footer bg-transparent border-0 py-2 d-flex justify-content-center">
                            {{ $stories->links('vendor.pagination.sneat') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@section('styles')
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rounded-circle {
            border: 1px solid #e0e0e0;
        }

        .fs-sm {
            font-size: 0.875rem;
        }

        .card-header {
            padding: 0.75rem 1rem;
        }

        .card-body {
            padding: 0.5rem;
        }

        .content-wrapper {
            min-height: calc(100vh - 100px);
        }

        .footer-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
