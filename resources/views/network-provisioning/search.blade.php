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
            <div class="search-input-wrapper position-relative">
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
    max-width: 240px;
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

.search-btn {
    border-radius: 25px;
    padding: 12px 25px;
    font-weight: 600;
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    transition: all 0.3s ease;
    height: 50px;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    background: linear-gradient(45deg, #218838, #1e7e34);
}

.reset-btn {
    border-radius: 25px;
    padding: 12px 20px;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: #667eea;
    transition: all 0.3s ease;
    height: 50px;
}

.reset-btn:hover {
    background: rgba(255, 255, 255, 1);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    color: #5a67d8;
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
    
    .search-btn, .reset-btn {
        height: 45px;
        font-size: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const searchBtn = document.getElementById('searchBtn');
    const resetBtn = document.getElementById('resetBtn');

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
    });

    // Search functionality
    searchBtn.addEventListener('click', function() {
        performSearch();
    });

    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Reset functionality
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        clearSearch.classList.add('d-none');
        // Add reset logic here
        console.log('Reset search');
    });

    function performSearch() {
        const query = searchInput.value.trim();
        if (query) {
            // Add search logic here
            console.log('Searching for:', query);
        }
    }
});
</script>
@endsection