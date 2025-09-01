@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="{{ route('network-provisioning.search') }}" class="btn btn-custom me-3" id="backToSearchBtn" onclick="return navigateWithOverlay(this.href)">
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
            <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
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
            <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
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
                        <!-- <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Average Density</label>
                                <p class="mb-0 fs-5">{{ $networkManagement->average_density ?? 'N/A' }}</p>
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">First Usable IP</label>
                                <p class="mb-0 fs-5"><code class="text-primary">{{ $networkManagement->first_usable_ip ? \App\Helpers\IpHelper::subtractOne($networkManagement->first_usable_ip) : 'N/A' }}</code></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Master Unit Quantity</label>
                                <p class="mb-0 fs-4 text-primary">{{ $networkManagement->master_unit_quantity ?? '0' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">BDA Quantity</label>
                                <p class="mb-0 fs-4 text-warning">{{ $networkManagement->bda_quantity ?? '0' }}</p>
                            </div>
                        </div>
                    </div>

                    @php
                        $masterUnitQty = (int) ($networkManagement->master_unit_quantity ?? 0);
                        $bdaQty = (int) ($networkManagement->bda_quantity ?? 0);
                        $firstUsableIp = $networkManagement->first_usable_ip;
                        
                        $masterUnitIPs = [];
                        $bdaIPs = [];
                        
                        if ($firstUsableIp && ($masterUnitQty > 0 || $bdaQty > 0)) {
                            // Convert IP to integer for calculation
                            $ipParts = explode('.', $firstUsableIp);
                            if (count($ipParts) == 4) {
                                $baseIp = ($ipParts[0] << 24) + ($ipParts[1] << 16) + ($ipParts[2] << 8) + $ipParts[3];
                                
                                $currentIp = $baseIp;
                                
                                // If both Master Unit and BDA quantities > 0, Master Units come first
                                // If only Master Unit quantity > 0, Master Units come first
                                // If only BDA quantity > 0, BDA comes first
                                
                                if ($masterUnitQty > 0) {
                                    // Generate Master Unit Sector IPs
                                    for ($i = 1; $i <= $masterUnitQty; $i++) {
                                        $ip = long2ip($currentIp);
                                        $masterUnitIPs[] = [
                                            'label' => "Master Unit Sector {$i}",
                                            'ip' => $ip
                                        ];
                                        $currentIp++;
                                    }
                                }
                                
                                if ($bdaQty > 0) {
                                    // Generate ERRCS BDA IPs
                                    for ($i = 1; $i <= $bdaQty; $i++) {
                                        $ip = long2ip($currentIp);
                                        $bdaIPs[] = [
                                            'label' => "ERRCS BDA {$i}",
                                            'ip' => $ip
                                        ];
                                        $currentIp++;
                                    }
                                }
                            }
                        }
                    @endphp

                    @if(count($masterUnitIPs) > 0 || count($bdaIPs) > 0)
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold text-muted mb-3">IP Address Assignments</h6>
                        </div>
                        
                        @if(count($masterUnitIPs) > 0)
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Master Unit Sectors</label>
                                @foreach($masterUnitIPs as $masterUnit)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                    <span class="fw-medium">{{ $masterUnit['label'] }}</span>
                                    <code class="text-primary">{{ $masterUnit['ip'] }}</code>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if(count($bdaIPs) > 0)
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">ERRCS BDA Units</label>
                                @foreach($bdaIPs as $bda)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                    <span class="fw-medium">{{ $bda['label'] }}</span>
                                    <code class="text-success">{{ $bda['ip'] }}</code>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Unit Quantities Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
            <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        Unit Quantities
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Master Unit Quantity</label>
                                <p class="mb-0 fs-4 text-primary">{{ $networkManagement->master_unit_quantity ?? '0' }}</p>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">Remote Unit Quantity</label>
                                <p class="mb-0 fs-4 text-success">{{ $networkManagement->remote_unit_quantity ?? '0' }}</p>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-4">
                            <div class="info-item mb-3">
                                <label class="form-label fw-bold text-muted">BDA Quantity</label>
                                <p class="mb-0 fs-4 text-warning">{{ $networkManagement->bda_quantity ?? '0' }}</p>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
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

    <!-- PfSense Configuration Card -->
    @if($networkManagement->xml_config_file)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-code me-2"></i>
                            PfSense Configuration File
                        </h5>
                        <button type="button" 
                                class="btn btn-custom btn-sm" 
                                onclick="downloadXmlFile({{ $networkManagement->id }})">
                            <i class="fas fa-download me-2"></i>Download XML
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="xml-preview-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-bold text-muted mb-0">XML Configuration Preview</label>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleXmlPreview()">
                                <i class="fas fa-eye me-1"></i><span id="toggleText">Show XML</span>
                            </button>
                        </div>
                        <div id="xmlPreview" class="xml-content" style="display: none;">
                            <pre class="bg-light p-3 rounded border" style="max-height: 400px; overflow-y: auto; font-size: 0.85rem;"><code>{{ $networkManagement->xml_config_file }}</code></pre>
                        </div>
                        <div class="text-muted small mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Click "Show XML" to preview the configuration or use the download button to save the file.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Start Provisioning Button -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <button class="btn btn-custom btn-lg">
                Start Provisioning
            </button>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

.btn-custom.btn-lg {
    font-size: 1.2rem;
    padding: 0.75rem 2rem;
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

/* XML Preview Styling */
.xml-content pre {
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    font-family: 'Courier New', monospace;
    line-height: 1.4;
}

.xml-content code {
    color: #495057;
    background: none;
    padding: 0;
    font-size: 0.85rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

/* XML Preview Animation */
.xml-content {
    transition: all 0.3s ease;
}

.xml-content.show {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Spinner animation used as a fallback if GIF fails to load */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* IP Assignment Styling */
.bg-light {
    background-color: #f8f9fa !important;
}

.d-flex.justify-content-between.align-items-center.mb-2.p-2.bg-light.rounded {
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.d-flex.justify-content-between.align-items-center.mb-2.p-2.bg-light.rounded:hover {
    background-color: #e9ecef !important;
    transform: translateX(2px);
}
</style>

<script>
function toggleXmlPreview() {
    const xmlPreview = document.getElementById('xmlPreview');
    const toggleText = document.getElementById('toggleText');
    const toggleIcon = document.querySelector('#xmlPreview').previousElementSibling.querySelector('i');
    
    if (xmlPreview.style.display === 'none') {
        xmlPreview.style.display = 'block';
        xmlPreview.classList.add('show');
        toggleText.textContent = 'Hide XML';
        toggleIcon.className = 'fas fa-eye-slash me-1';
    } else {
        xmlPreview.style.display = 'none';
        xmlPreview.classList.remove('show');
        toggleText.textContent = 'Show XML';
        toggleIcon.className = 'fas fa-eye me-1';
    }
}

function downloadXmlFile(id) {
    // Create a hidden iframe to handle the download without page reload
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = `/network-provisioning/download-xml-db/${id}`;
    document.body.appendChild(iframe);
    
    // Remove the iframe after a short delay
    setTimeout(() => {
        document.body.removeChild(iframe);
    }, 1000);
}

// Show transition overlay and navigate (same visual behavior as create->store)
function navigateWithOverlay(url) {
    try {
        const overlay = document.createElement('div');
        overlay.id = 'transition-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 70px; /* Height of topbar */
            left: 240px; /* Width of sidebar */
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
            opacity: 0;
        `;

        // Responsive offsets
        if (window.innerWidth <= 992) {
            overlay.style.left = '0px';
            overlay.style.top = '60px';
        }

        // Loading container
        const loadingContainer = document.createElement('div');
        loadingContainer.style.cssText = `
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        `;

        // GIF element
        const gifElement = document.createElement('img');
        gifElement.src = '/assets/images/Transition_Animation.gif';
        gifElement.alt = 'Loading...';
        gifElement.style.cssText = `
            max-width: 300px;
            max-height: 300px;
            width: auto;
            height: auto;
            margin-bottom: 1.5rem;
        `;

        // Spinner fallback
        const spinner = document.createElement('div');
        spinner.style.cssText = `
            width: 60px;
            height: 60px;
            border: 6px solid #e5e7eb;
            border-top: 6px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
            display: none;
        `;

        // Ensure keyframes exist (defensive)
        if (!document.querySelector('#loading-spinner-style')) {
            const style = document.createElement('style');
            style.id = 'loading-spinner-style';
            style.textContent = `@keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }`;
            document.head.appendChild(style);
        }

        let proceeded = false;
        const proceed = () => {
            if (proceeded) return;
            proceeded = true;
            try { sessionStorage.setItem('showTransitionOverlay', '1'); } catch (_) {}
            window.location.href = url;
        };

        gifElement.addEventListener('load', () => {
            // Keep the overlay visible briefly to show the GIF
            setTimeout(proceed, 3000);
        });

        gifElement.addEventListener('error', () => {
            gifElement.style.display = 'none';
            spinner.style.display = 'block';
            setTimeout(proceed, 1500);
        });

        // Assemble DOM
        loadingContainer.appendChild(gifElement);
        loadingContainer.appendChild(spinner);
        overlay.appendChild(loadingContainer);
        document.body.appendChild(overlay);

        // Fade in
        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
        });

        // Keep offsets correct on resize
        const handleResize = () => {
            if (window.innerWidth <= 992) {
                overlay.style.left = '0px';
                overlay.style.top = '60px';
            } else {
                overlay.style.left = '240px';
                overlay.style.top = '70px';
            }
        };
        window.addEventListener('resize', handleResize);

        // Safety timeout
        setTimeout(proceed, 8000);
    } catch (e) {
        window.location.href = url;
    }
    return false; // prevent default navigation when used inline
}

document.addEventListener('DOMContentLoaded', function() {
    const backBtn = document.getElementById('backToSearchBtn');
    if (backBtn) {
        // Defensive: ensure JS handler also prevents default and triggers overlay
        backBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            navigateWithOverlay(url);
            return false;
        });
    }
});
</script>
@endsection