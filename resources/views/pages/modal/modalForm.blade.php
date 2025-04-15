<form id="{{ $resourceName }}Form" class="mb-3"
    action="{{ isset($model) ? route('admin.' . $resourceName . '.update', $model->id) : route('admin.' . $resourceName . '.store') }}"
    method="POST">
    @csrf
    @if (isset($model))
        @method('PUT')
    @endif
    <div class="row">
        @yield('form-fields')
        <div class="col-md-12 d-flex gap-2 mt-5">
            <button type="submit" class="btn btn-primary">
                {{ isset($model) ? __('button.update') : __('button.submit') }}
            </button>
            <button type="reset" class="btn btn-outline-danger me-2">
                {{ __('button.reset') }}
            </button>
        </div>
    </div>
</form>
