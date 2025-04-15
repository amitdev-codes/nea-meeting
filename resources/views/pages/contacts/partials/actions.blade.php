<div>
    <a href="{{ route('admin.contacts.show', $data->id) }}" title="View"
        class="btn btn-icon btn-outline-info btn-sm show-btn @cannot('view contacts') disabled @endcannot"
        @cannot('view contacts') onclick="return false;" @endcannot>
        <i class="bx bx-show"></i>
    </a>
    <button type="button" class="btn btn-icon btn-outline-primary btn-sm edit-btn" title="Edit"
        data-id="{{ $data->id }}"
        @cannot('edit contacts') disabled @endcannot>
        <i class="bx bx-edit-alt"></i>
    </button>
    <button type="button" class="btn btn-icon btn-outline-danger btn-sm delete-btn" title="Delete"
        data-id="{{ $data->id }}"
        @cannot('delete contacts') disabled @endcannot>
        <i class="bx bx-trash"></i>
    </button>
</div>
