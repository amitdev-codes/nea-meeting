<div data-bs-spy="scroll" class="scrollspy-example" style="padding-top: 8.5rem">
    <div class="container">
        <div class="row">
            <!-- Left Column: Grievance Form -->
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header  text-white d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error bx-md me-2"></i>
                            <h5 class="mb-0">{{ __('landing.submit_a_grievance') }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Success Alert -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center"
                                role="alert" id="successAlert">
                                <i class="bx bx-check-circle me-2"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
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
                                    <x-forms.input name="email" type="email" label="{{ __('Email') }}"
                                        :value="old('email', $model->email ?? '')" required />
                                </div>
                                <div class="mb-3 col-6">
                                    <x-forms.input-phone name="mobile_no" label="{{ __('Mobile No') }}"
                                        :value="old('mobile_no', $model->mobile_no ?? '')" required />
                                </div>
                                <div class="mb-3 col-6">
                                    <x-forms.input-select2 name="local_level_id" :options="$cluster_local_levels
                                        ->map(function ($clusterLocalLevel) {
                                            return [
                                                $clusterLocalLevel->id,
                                                $clusterLocalLevel->name . ' (' . $clusterLocalLevel->name_np . ')',
                                            ];
                                        })
                                        ->toArray()" :value="old('local_level_id', isset($model) ? $model->local_level_id : '')"
                                        placeholder="{{ __('Select a Local level') }}" required />
                                </div>
                                <div class="mb-3 col-12">
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

            <!-- Right Column: Latest Grievances List -->
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list-ul bx-md me-2"></i>
                            <h5 class="mb-0">{{ __('landing.latest_grievances') }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Search Field -->
                        <div class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="ticketSearch"
                                    placeholder="Enter Ticket ID">
                                <button class="btn btn-primary" type="button" onclick="searchTicket()">Search</button>
                            </div>
                            <div id="ticketDetails" class="mt-2" style="display: none;"></div>
                        </div>

                        <!-- Grievances List -->
                        <div class="grievances-list" style="max-height: 400px; overflow-y: auto;">
                            <div class="list-group" id="grievancesList">
                                <!-- Grievances will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .card-header {
        padding: 1rem 1.25rem;
    }

    .form-label {
        font-size: 0.9rem;
        color: #333;
    }
    body.dark .label,
    body.dark label {
        color: #ffffff !important;
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

    .list-group-item {
        margin-bottom: 0.5rem;
        border-radius: 0.375rem;
    }
</style>

<script>
    // Function to fetch latest grievances
    async function fetchLatestGrievances() {
        try {
            const response = await fetch('{{ route('admin.grievances.latest') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch grievances');
            }

            const grievances = await response.json();
            const grievancesList = document.getElementById('grievancesList');

            // Clear existing content
            grievancesList.innerHTML = '';

            // Populate list with grievance names
            grievances.forEach(grievance => {
                const listItem = document.createElement('div');
                listItem.className = 'list-group-item';
                listItem.innerHTML = `
                    <h6 class="mb-0">${grievance.description}</h6>
                `;
                grievancesList.appendChild(listItem);
            });
        } catch (error) {
            console.error('Error fetching grievances:', error);
            const grievancesList = document.getElementById('grievancesList');
            grievancesList.innerHTML = `
                <div class="list-group-item text-danger">
                    Failed to load grievances
                </div>
            `;
        }
    }

    // Existing searchTicket function
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
                    <h6>Ticket #${data.ticket_id}</h6>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Subject:</strong> ${data.subject}</p>
                    <p><strong>Response:</strong> ${data.response}</p>
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
        document.getElementById('ticketSearch').value = '';
    }

    // Event listeners
    document.getElementById('ticketSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchTicket();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.remove('show');
            }, 5000);
        }

        // Fetch grievances when page loads
        fetchLatestGrievances();
    });
</script>
