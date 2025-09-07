@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="title-container mb-4">
                    <h1 class="simple-title mb-3">Search Provisioning</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Search Bar Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-3 align-items-center">
                <div class="search-input-wrapper position-relative flex-grow-1">
                    <input type="text" 
                           class="form-control search-input"
                           id="searchInput"
                           placeholder="Search by property name, type, address, or system..."
                           autocomplete="off">
                    <div class="search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="search-clear d-none" id="clearSearch">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Component -->
    <div class="row">
        <div class="col-12">
            <x-provisioning-table />
        </div>
    </div>
</div>

<style>
/* Simple Title Styles */
.title-container {
    text-align: left;
}

.simple-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #13395d;
    margin-bottom: 0;
}

.search-input-wrapper {
    position: relative;
    max-width: 40%;
    margin: 0;
}

.search-input {
    border: none;
    border-radius: 25px;
    padding: 12px 50px 12px 20px;
    font-size: 16px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 50px;
}

.search-input:focus {
    outline: none;
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.search-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    pointer-events: none;
    z-index: 10;
}

.search-clear {
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    cursor: pointer;
    z-index: 10;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.search-clear:hover {
    background: rgba(0, 0, 0, 0.1);
    color: #dc3545;
}

@media (max-width: 768px) {
    .search-container {
        margin: -15px;
        padding: 15px;
    }
    
    .search-input {
        height: 45px;
        font-size: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle overlay on arrival: show briefly, then fade out; instantly clear on bfcache restore
    function fadeOutAndRemoveOverlay(delayMs = 400) {
        const existing = document.getElementById('transition-overlay');
        if (!existing) return;
        setTimeout(() => {
            try {
                existing.style.transition = existing.style.transition || 'opacity 0.3s ease';
                existing.style.opacity = '0';
                setTimeout(() => {
                    if (existing && existing.parentNode) existing.parentNode.removeChild(existing);
                }, 320);
            } catch (_) {
                if (existing && existing.parentNode) existing.parentNode.removeChild(existing);
            }
        }, Math.max(0, delayMs));
    }
    // If a navigation set the arrival flag, create the overlay now and fade out after a brief display
    let arrivalFlag = false;
    try { arrivalFlag = sessionStorage.getItem('showTransitionOverlay') === '1'; } catch (_) {}
    if (arrivalFlag) {
        try { sessionStorage.removeItem('showTransitionOverlay'); } catch (_) {}
        if (!document.getElementById('transition-overlay')) {
            const overlay = document.createElement('div');
            overlay.id = 'transition-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 70px;
                left: 240px;
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
            if (window.innerWidth <= 992) {
                overlay.style.left = '0px';
                overlay.style.top = '60px';
            }
            const loadingContainer = document.createElement('div');
            loadingContainer.style.cssText = `
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            `;
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
            gifElement.addEventListener('error', () => {
                gifElement.style.display = 'none';
                spinner.style.display = 'block';
            });
            loadingContainer.appendChild(gifElement);
            loadingContainer.appendChild(spinner);
            overlay.appendChild(loadingContainer);
            document.body.appendChild(overlay);
            requestAnimationFrame(() => { overlay.style.opacity = '1'; });
        }
        // Allow users to see the animation briefly before removing
        fadeOutAndRemoveOverlay(1000);
    } else {
        // Generic case: if an overlay exists for any reason, remove it quickly
        fadeOutAndRemoveOverlay(450);
    }
    // On bfcache restore, remove immediately to avoid stale overlay
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            fadeOutAndRemoveOverlay(0);
        }
    });

    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    
    // Function to check if table functions are available
    function checkTableReady() {
        return window.filterProvisioningTable && window.resetProvisioningTableFilter;
    }
    
    // Wait for table to be ready
    function waitForTable(callback, maxAttempts = 50) {
        let attempts = 0;
        const checkInterval = setInterval(() => {
            attempts++;
            if (checkTableReady()) {
                clearInterval(checkInterval);
                callback();
            } else if (attempts >= maxAttempts) {
                clearInterval(checkInterval);
                console.error('Table functions not available after maximum attempts');
            }
        }, 100);
    }

    // Show/hide clear button
    searchInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            clearSearch.classList.remove('d-none');
        } else {
            clearSearch.classList.add('d-none');
        }
    });

    // Clear search input
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        clearSearch.classList.add('d-none');
        searchInput.focus();
        // Also reset the table filter
        if (checkTableReady()) {
            window.resetProvisioningTableFilter();
        } else {
            waitForTable(() => {
                window.resetProvisioningTableFilter();
            });
        }
    });

    function performSearch() {
        const query = searchInput.value.trim();
        
        if (query) {
            // Trigger search on the provisioning table
            if (checkTableReady()) {
                window.filterProvisioningTable(query);
            } else {
                waitForTable(() => {
                    window.filterProvisioningTable(query);
                });
            }
        } else {
            // If empty query, show all data
            if (checkTableReady()) {
                window.resetProvisioningTableFilter();
            } else {
                waitForTable(() => {
                    window.resetProvisioningTableFilter();
                });
            }
        }
    }

    // Real-time search as user types
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 150); // Reduced debounce time for more responsive search
    });
    
    // Initialize search functionality when page loads
});
</script>
@endsection