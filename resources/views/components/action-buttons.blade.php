<div class="action-btn-container dropdown">
    <!-- View Button -->
    @if ($viewRoute && Auth::user()->can($viewPermission))
        <a href="{{ $viewRoute }}" class="btn btn-outline-dark item-edit" title="View">
            <i class="bx bx-show"></i>
        </a>
    @endif

    <!-- Edit Button -->
    @if ($editRoute && Auth::user()->can($editPermission))
        @if ($formType === 'modal')
            <button type="button" class="btn btn-outline-dark edit-btn" data-id="{{ $data->id }}" title="Edit">
                <i class="bx bx-edit"></i>
            </button>
        @else
            <a href="{{ $editRoute }}" class="btn btn-outline-dark item-edit" title="Edit">
                <i class="bx bx-edit"></i>
            </a>
        @endif
    @endif

    <!-- Delete Button -->
    @if ($deleteRoute && Auth::user()->can($deletePermission))
        <a href="{{ $deleteRoute }}" class="btn btn-outline-danger" id="deleteBtn-{{ $id }}" title="Delete">
            <i class="bx bx-trash"></i>
        </a>
    @endif
</div>

@if ($deleteRoute && Auth::user()->can($deletePermission))
    <script type="module">
        document.getElementById('deleteBtn-{{ $id }}').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                preConfirm: () => {
                    return new Promise((resolve, reject) => {
                        fetch("{{ $deleteRoute }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Network response was not ok. Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the record.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                'Something went wrong: ' + error.message,
                                'error'
                            );
                        });
                    });
                }
            });
        });
    </script>
@endif