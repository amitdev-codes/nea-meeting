<div class="d-flex justify-content-center gap-2">
    <a href="{{ $show_route }}" class="btn btn-xs btn-info" title="View">
        <i class="bx bx-show"></i>
    </a>
    <a href="{{ $edit_route }}" class="btn btn-xs btn-primary" title="Edit">
        <i class="bx bx-edit"></i>
    </a>
    <button type="button" class="btn btn-xs btn-danger delete-item" data-id="{{ $delete_id }}" title="Delete">
        <i class="bx bx-trash"></i>
    </button>
</div>
