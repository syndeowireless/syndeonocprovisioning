@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="{{ route('network-provisioning.search') }}" class="btn btn-custom me-3">
                        <i class="fas fa-arrow-left me-2"></i>Back to Search
                    </a>
                    <h4 class="mb-0">{{ $networkManagement->property_name ?? 'Property' }} Details</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Property Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
            <div class="card-header text-white" style="background-color: #13395d;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>
                        Property Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Property Name</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->property_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Property Type</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->property_type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Property Address</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->property_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Configuration Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
            <div class="card-header text-white" style="background-color: #13395d;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        System Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">System Type</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->system_type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">OEM</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->oem ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Average Density</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->average_density ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">First Usable IP</label>
                                <p class="mb-0 fs-5"><code class="text-primary">{{ $networkManagement->first_usable_ip ?? 'N/A' }}</code></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unit Quantities Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
            <div class="card-header text-white" style="background-color: #13395d;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        Unit Quantities
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Master Unit Quantity</label>
                                <p class="mb-0 fs-4 text-primary">{{ $networkManagement->master_unit_quantity ?? '0' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Remote Unit Quantity</label>
                                <p class="mb-0 fs-4 text-success">{{ $networkManagement->remote_unit_quantity ?? '0' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">BDA Quantity</label>
                                <p class="mb-0 fs-4 text-warning">{{ $networkManagement->bda_quantity ?? '0' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #13395d;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Location Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Latitude</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->latitude ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Longitude</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->longitude ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($networkManagement->latitude && $networkManagement->longitude)
                        <div class="col-12">
                            <div class="info-item">
                                <label class="form-label fw-bold text-muted">Map Location</label>
                                <div class="mt-2">
                                    <a href="https://www.google.com/maps?q={{ $networkManagement->latitude }},{{ $networkManagement->longitude }}" 
                                       target="_blank" 
                                       class="btn btn-custom">
                                        <i class="fas fa-external-link-alt me-2"></i>View on Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Record Information Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
            <div class="card-header text-white" style="background-color: #13395d;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Record Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Record ID</label>
                                <p class="mb-0 fs-5"><code class="text-muted">#{{ $networkManagement->id }}</code></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Created At</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->created_at ? $networkManagement->created_at->format('M d, Y H:i:s') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Last Updated</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->updated_at ? $networkManagement->updated_at->format('M d, Y H:i:s') : 'N/A' }}</p>
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
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.card-header {
    border: none;
    padding: 1.25rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

.info-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.form-label {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

code {
    background-color: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

/* Custom Button Styling */
.btn-custom {
    background: #13395d;
    color: white;
    border: 2px solid #fbbf0f;
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    z-index: 1;
}

.btn-custom::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(251, 191, 15, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
    z-index: -1;
}

.btn-custom:hover {
    background: #FBBF0F;
    border: 2px solid #13395D;
    color: #000;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(19, 57, 93, 0.3);
}

.btn-custom:hover::before {
    width: 300px;
    height: 300px;
}

.btn-custom:active {
    transform: translateY(0);
}

/* Ripple effect on click */
.btn-custom:active::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(19, 57, 93, 0.4);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: ripple 0.6s ease-out;
}

@keyframes ripple {
    to {
        width: 300px;
        height: 300px;
        opacity: 0;
    }
}

.page-title-box {
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .info-item {
        padding: 0.5rem 0;
    }
    
    .fs-5 {
        font-size: 1rem !important;
    }
    
    .fs-4 {
        font-size: 1.1rem !important;
    }
}
</style>
@endsection