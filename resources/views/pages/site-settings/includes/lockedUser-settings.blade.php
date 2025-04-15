<div class="col-md-12 mb-3">
    <h5 class="card-header">Unlock User Account</h5>
    <div class="card-body">
        <form action="{{ route('admin.settings.unlock') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_identifier" class="form-label">Enter Email, Username, or Mobile Number</label>
                <input type="text" class="form-control @error('user_identifier') is-invalid @enderror"
                    id="user_identifier" name="user_identifier" placeholder="Email, Username, or Mobile Number">
                @error('user_identifier')
                    <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-warning">Unlock User</button>
        </form>
    </div>
</div>
