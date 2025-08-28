@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Search Provisioning</h4>
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
                console.log('Table functions are ready!');
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
            console.log('Calling resetProvisioningTableFilter from clear button'); // Debug log
            window.resetProvisioningTableFilter();
        } else {
            console.log('Table not ready for clear, waiting...'); // Debug log
            waitForTable(() => {
                console.log('Calling resetProvisioningTableFilter after table ready from clear button'); // Debug log
                window.resetProvisioningTableFilter();
            });
        }
    });

    function performSearch() {
        const query = searchInput.value.trim();
        console.log('Performing search for:', query); // Debug log
        
        if (query) {
            // Trigger search on the provisioning table
            if (checkTableReady()) {
                console.log('Calling filterProvisioningTable with:', query); // Debug log
                window.filterProvisioningTable(query);
            } else {
                console.log('Table not ready, waiting...'); // Debug log
                waitForTable(() => {
                    console.log('Calling filterProvisioningTable after table ready with:', query); // Debug log
                    window.filterProvisioningTable(query);
                });
            }
        } else {
            // If empty query, show all data
            if (checkTableReady()) {
                console.log('Calling resetProvisioningTableFilter'); // Debug log
                window.resetProvisioningTableFilter();
            } else {
                console.log('Table not ready, waiting...'); // Debug log
                waitForTable(() => {
                    console.log('Calling resetProvisioningTableFilter after table ready'); // Debug log
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
    console.log('Search functionality initialized');
});
</script>
@endsection