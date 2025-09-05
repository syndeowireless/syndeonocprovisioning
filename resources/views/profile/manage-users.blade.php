@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="title-container mb-4">
                    <h1 class="simple-title mb-3">User Management</h1>
                    <x-primary-button type="button" class="btn-sm">
                        <i class="mdi mdi-plus me-2"></i>Create New User
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="mdi mdi-account-group me-2"></i>
                            Users Management
                        </h5>
                        <div class="table-controls d-flex align-items-center gap-3">
                            <div class="sort-controls d-flex align-items-center">
                                <label for="sortOrder" class="form-label me-2 mb-0 text-muted">Sort:</label>
                                <select class="form-select form-select-sm" id="sortOrder" style="width: auto;">
                                    <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Newest First</option>
                                    <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Oldest First</option>
                                </select>
                            </div>
                            <div class="entries-controls d-flex align-items-center">
                                <label for="perPage" class="form-label me-2 mb-0 text-muted">Show:</label>
                                <select class="form-select form-select-sm" id="perPage" style="width: auto;">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                                    <option value="40" {{ $perPage == 40 ? 'selected' : '' }}>40</option>
                                </select>
                                <span class="text-muted ms-2">entries</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="usersTable">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">User Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
                                    <th scope="col">Action</th>                  
                                </tr>
                            
                            </thead>
                            <!-- Yellow line below table headers -->
                            <tr><td colspan="6" style="padding: 0; border: none;"><div style="height: 3px; background-color: #fbbf0f; margin: 0; width: 100%;"></div></td></tr>
                            
                            <tbody>
                                @forelse($users as $user)
                                <tr class="clickable-row">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-primary">Admin</span>
                                        @else
                                            <span class="badge bg-secondary">User</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($user->role !== 'admin')
                                        <div class="d-flex gap-2">
                                            <x-primary-button type="button" class="btn-sm px-3 py-1" style="font-size: 0.75rem;">
                                                Update
                                            </x-primary-button>
                                            <x-primary-button type="button" class="btn-sm px-3 py-1" style="font-size: 0.75rem; background-color: #dc3545; border-color: #dc3545;" 
                                                onmouseover="this.style.backgroundColor='#c82333'; this.style.borderColor='#bd2130';" 
                                                onmouseout="this.style.backgroundColor='#dc3545'; this.style.borderColor='#dc3545';">
                                                Delete
                                            </x-primary-button>
                                        </div>
                                        @else
                                        <span class="text-muted">No actions available</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="mdi mdi-account-off me-2"></i>
                                        No users found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="table-info">
                            <span class="text-muted">
                                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
                            </span>
                        </div>
                        
                        @if($users->hasPages())
                        <nav aria-label="Table pagination">
                            {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                        @endif
                    </div>
                </div>
            </div>
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




/* Existing Styles */
.table-responsive {
    border-radius: 0;
}

.table th {
    background-color: #13395d;
    color: white;
    font-weight: 600;
    border: none;
    padding: 15px 12px;
    vertical-align: middle;
}

.table td {
    padding: 12px;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.clickable-row:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 500;
}

.card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}


.gap-2 {
    gap: 0.5rem;
}

.pagination .page-link {
    border: 1px solid #e9ecef;
    color: #667eea;
    padding: 0.5rem 0.75rem;
    margin: 0 2px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background-color: #13395d;
    border-color: #667eea;
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.table-controls .gap-3 {
    gap: 1rem;
}

@media (max-width: 768px) {
    .simple-title {
        font-size: 2rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table th, .table td {
        padding: 8px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .table-info {
        order: 2;
        text-align: center;
    }
    
    .pagination {
        order: 1;
        justify-content: center;
    }
    
    .table-controls {
        flex-direction: column;
        gap: 0.5rem;
        align-items: stretch;
    }
    
    .sort-controls, .entries-controls {
        justify-content: space-between;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const perPageSelect = document.getElementById('perPage');
    const sortOrderSelect = document.getElementById('sortOrder');
    
    // Handle rows per page change
    perPageSelect.addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('per_page', this.value);
        url.searchParams.set('page', '1'); // Reset to first page
        window.location.href = url.toString();
    });

    // Handle sort order change
    sortOrderSelect.addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('sort', this.value);
        url.searchParams.set('page', '1'); // Reset to first page
        window.location.href = url.toString();
    });
});
</script>

@endsection