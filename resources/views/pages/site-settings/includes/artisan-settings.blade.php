<div class="row">
    <!-- Artisan Command Buttons -->
    <div class="col-md-12 mb-3">
        <h5 class="card-header">Artisan Commands</h5>
        <div class="card-body">
            <div class="row">
                <!-- Optimize Clear -->
                <div class="col-md-4 mb-3">
                    <form action="{{ route('admin.artisan.optimize-clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info w-100">Optimize:Clear</button>
                    </form>
                </div>

                <!-- Config Cache -->
                <div class="col-md-4 mb-3">
                    <form action="{{ route('admin.artisan.config-cache') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info w-100">Config:Cache</button>
                    </form>
                </div>

                <!-- Maintenance Mode Toggle -->
                <div class="col-md-4 mb-3">
                    <form action="{{ route('admin.artisan.maintenance-toggle') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info w-100">
                            {{ app()->isDownForMaintenance() ? 'Maintenance Mode Off' : 'Maintenance Mode On' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
