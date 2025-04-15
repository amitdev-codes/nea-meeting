<div class="col-md-12 row">
    <div class="mt-4">
        @if($isDownForMaintenance)
            <div class="alert alert-warning text-center" role="alert">
                The site is currently in maintenance mode.
            </div>
        @else
            <div class="alert alert-info text-center" role="alert">
                The site is live and accessible.
            </div>
        @endif
    </div>

        <div class="col-md-12 mb-4">
            <label class="switch">
                <input type="checkbox" class="switch-input" type="checkbox"
                wire:click="confirmToggle"
                @if($isDownForMaintenance) checked @endif>
                <span class="switch-toggle-slider"></span>
                <span class="switch-label">Toggle Maintenance Mode</span>

            </label>
            <i class="fas fa-edit ms-2" role="button" data-bs-toggle="modal" data-bs-target="#maintenanceFieldsModal"></i>
        </div>

    {{-- <div class="form-check form-switch dt-flags">
        <input
            class="form-check-input"
            type="checkbox"
            wire:click="confirmToggle"
            @if($isDownForMaintenance) checked @endif>
    </div> --}}
</div>

<script>
    window.addEventListener('confirmToggle', (event) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will toggle the site's maintenance mode.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('toggleMaintenanceModeConfirmed');
            } else {
                console.log('cancelled');
                // Livewire->dispatch('$refresh');
                Livewire.dispatch('toggleMaintenanceModeCancelled');
            }
        });
    });

    window.addEventListener('redirect', event => {
        const url= event.detail[0].url;
        window.open(url , '_blank');
    });
</script>
