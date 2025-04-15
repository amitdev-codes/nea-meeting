<div class="container-xxl mb-4 mt-4">
    <div class="card shadow-sm">
        <!-- Header with form name and toggle icon -->
        <div class="card-header bg-primary d-flex justify-content-between align-items-center"
             data-bs-toggle="collapse"
             data-bs-target="#formDetailsCollapse-{{ $uniqueId ?? 'default' }}"
             aria-expanded="{{ $expanded ? 'true' : 'false' }}"
             aria-controls="formDetailsCollapse-{{ $uniqueId ?? 'default' }}"
             style="cursor: pointer;">
            <h5 class="mb-0 text-white">{{ $data['forms']?->name ?? 'Form Details' }}</h5>
            <i class="fas fa-{{ $expanded ? 'minus' : 'plus' }} text-white"></i>
        </div>
        
        <!-- Collapsible content -->
        <div class="collapse {{ $expanded ? 'show' : '' }}"
             id="formDetailsCollapse-{{ $uniqueId ?? 'default' }}">
            <div class="card-body p-3">
                <div class="row g-3">
                    <!-- First row of 3 items -->
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Fiscal Year</span>
                            <span class="detail-value">{{ $data['fiscalYear'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Component</span>
                            <span class="detail-value">{{ $data['component']?->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Activity</span>
                            <span class="detail-value">{{ $data['activity']?->code.' - ' . $data['activity']?->lmbis_activity_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <!-- Second row of 3 items -->
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Rural Municipality</span>
                            <span class="detail-value">{{ $data['localLevel']?->name.' - ' . $data['localLevel']?->name_np ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Multiple Groups</span>
                            <span class="detail-value">{{ ($data['is_multiple_group'] ?? 0) ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Group</span>
                            <span class="detail-value">{{ $data['group']?->name.' - ' . $data['group']?->name_np ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .detail-item {
        display: flex;
        flex-direction: column;
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
        height: 100%;
    }
    .detail-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 4px;
    }
    .detail-value {
        font-size: 0.95rem;
        color: #212529;
        word-break: break-word;
    }
</style>