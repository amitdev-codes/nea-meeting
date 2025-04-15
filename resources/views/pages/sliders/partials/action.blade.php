<div>
    <a href="{{ route('admin.sliders.show', $data->id) }}" title="Preview"
        class="btn btn-icon btn-outline-info btn-sm show-btn @cannot('view sliders') disabled @endcannot"
        @cannot('view sliders') onclick="return false;" @endcannot>
        <i class="bx bx-show"></i>
    </a>
    <a href="{{ route('admin.sliders.edit', $data->id) }}" title="Edit"
        class="btn btn-icon btn-outline-primary btn-sm show-btn @cannot('edit sliders') disabled @endcannot"
        @cannot('edit sliders') onclick="return false;" @endcannot>
        <i class="bx bx-edit-alt"></i>
    </a>
    <button type="button" class="btn btn-icon btn-outline-danger btn-sm delete-btn"
        data-id="{{ $data->id }}" title="Delete"
        @cannot('delete sliders') disabled @endcannot>
        <i class="bx bx-trash"></i>
    </button>
</div>
