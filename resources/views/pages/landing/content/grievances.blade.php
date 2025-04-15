
<div class="col-lg-8 col-md-8">
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header text-white d-flex align-items-center justify-content-between" style="background-color: #696cff;">
            <div class="d-flex align-items-center">
                <i class="bx bx-error bx-md me-2"></i>
                <h5 class="mb-0">Submit a Grievance</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Search Field -->
            <div class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="ticketSearch" placeholder="Enter Ticket ID">
                    <button class="btn btn-primary" type="button" onclick="searchTicket()">Search</button>
                </div>
                <div id="ticketDetails" class="mt-2" style="display: none;"></div>
            </div>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" id="successAlert">
                    <i class="bx bx-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.grievances.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="mb-3 col-6">
                        <x-forms.input name="name" :label="__('field.name')" :value="old('name', $model->name ?? '')" required />
                    </div>
                    <div class="mb-3 col-6">
                        <x-forms.input name="email" type="email" label="{{ __('Email') }}" :value="old('email', $model->email ?? '')"  />
                    </div>
                    <div class="mb-3 col-6">
                        <x-forms.input-phone name="mobile_no" label="{{ __('Mobile No') }}" :value="old('mobile_no', $model->mobile_no ?? '')"  />
                    </div>
                    <div class="mb-3 col-6">
                        <x-forms.input-select2 name="cluster_id" :options="$clusters->map(function ($cluster) { return [$cluster->id, $cluster->name . ' (' . $cluster->name_np . ')']; })->toArray()" :value="old('cluster_id', isset($model) ? $model->cluster_id : '')" placeholder="{{ __('Select a Cluster') }}" required />
                    </div>
                    <div class="mb-3 col-6">
                        <x-forms.input-select2 name="sector_id" :options="$sectors->map(function ($sector) { return [$sector->id, $sector->name . ' (' . $sector->name_np . ')']; })->toArray()" :value="old('sector_id', isset($model) ? $model->sector_id : '')" placeholder="{{ __('Select a Sector') }}" required />
                    </div>
                    <div class="mb-3 col-6">
                        <x-forms.input name="subject" :label="__('field.subject')" :value="old('subject', $model->subject ?? '')" required />
                    </div>
                    <div class="mb-3 col-12">
                        <x-forms.input-textarea name="description" :label="__('field.description')" :value="old('description', $model->description ?? '')" />
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bx bx-send me-1"></i> Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
    }

    .card-header {
        padding: 1rem 1.25rem;
    }

    .form-label {
        font-size: 0.9rem;
        color: #333;
    }

    .form-control,
    .form-select {
        border: 1px solid #e0e0e0;
        padding: 0.6rem 0.9rem;
        font-size: 0.9rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.25);
    }

    .btn-primary {
        background-color: #696cff;
        border-color: #696cff;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
    }

    .btn-primary:hover {
        background-color: #5a5cff;
        border-color: #5a5cff;
    }

    .alert-success {
        border-radius: 0.375rem;
        padding: 0.75rem;
        margin-bottom: 1rem;
    }
</style>

<script >
    async function searchTicket() {
        const ticketId = document.getElementById('ticketSearch').value;
        const ticketDetails = document.getElementById('ticketDetails');
        
        if (!ticketId) {
            ticketDetails.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`{{ route('admin.grievances.search') }}?ticket_id=${ticketId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Ticket not found');
            }

            const data = await response.json();

            ticketDetails.style.display = 'block';
            ticketDetails.innerHTML = `
                <div class="alert alert-info position-relative">
                    <button type="button" class="btn-close position-absolute" style="top: 10px; right: 10px;" onclick="closeTicketDetails()"></button>
                    <h6>Ticket #${data.id}</h6>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Subject:</strong> ${data.subject}</p>
                    <p><strong>Date:</strong> ${data.created_at}</p>
                </div>
            `;
        } catch (error) {
            ticketDetails.style.display = 'block';
            ticketDetails.innerHTML = `
                <div class="alert alert-danger">
                    <p>Ticket not found. Please check your Ticket ID and try again.</p>
                </div>
            `;
        }
    }
    function closeTicketDetails() {
        const ticketDetails = document.getElementById('ticketDetails');
        ticketDetails.style.display = 'none';
        document.getElementById('ticketSearch').value = ''; // Clear the search input
    }
    // Add event listener for search on Enter key
    document.getElementById('ticketSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchTicket();
        }
    });

    // Success message handling
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.remove('show');
            }, 5000);
        }
    });
</script>