<div>
    <button type="button" class="btn btn-icon btn-outline-primary btn-sm edit-btn"
        data-id="{{ $data->id }}" title="Edit"
        @cannot($editPermission) disabled @endcannot>
        <i class="bx bx-edit-alt"></i>
    </button>
    <button type="button" class="btn btn-icon btn-outline-danger btn-sm delete-btn"
        data-id="{{ $data->id }}" title="Delete"
        @cannot($deletePermission) disabled @endcannot>
        <i class="bx bx-trash"></i>
    </button>
</div>
