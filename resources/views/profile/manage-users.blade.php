@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">User Management</h4>
                <x-primary-button type="button" class="btn-sm">
                    <i class="mdi mdi-plus me-2"></i>Create New User
                </x-primary-button>
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
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="usersTable">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">User Name</th>
                                    <th scope="col">Password</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Reset Password</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="clickable-row">
                                    <td>Affiliated Partners</td>
                                    <td><code>affiliatedpass123</code></td>
                                    <td>affiliated@company.com</td>
                                    <td><span class="badge bg-primary">Admin</span></td>
                                    <td>
                                        <x-primary-button type="button" class="btn-sm px-3 py-1" style="font-size: 0.75rem;">
                                            Reset
                                        </x-primary-button>
                                    </td>
                                    <td>
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
                                    </td>
                                </tr>
                                <tr class="clickable-row">
                                    <td>Walmart</td>
                                    <td><code>walmartpass456</code></td>
                                    <td>walmart@company.com</td>
                                    <td><span class="badge bg-secondary">User</span></td>
                                    <td>
                                        <x-primary-button type="button" class="btn-sm px-3 py-1" style="font-size: 0.75rem;">
                                            Reset
                                        </x-primary-button>
                                    </td>
                                    <td>
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
                                    </td>
                                </tr>
                                <tr class="clickable-row">
                                    <td>XYZ Real Estate</td>
                                    <td><code>xyzrealestate789</code></td>
                                    <td>xyz@realestate.com</td>
                                    <td><span class="badge bg-secondary">User</span></td>
                                    <td>
                                        <x-primary-button type="button" class="btn-sm px-3 py-1" style="font-size: 0.75rem;">
                                            Reset
                                        </x-primary-button>
                                    </td>
                                    <td>
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
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="table-info">
                            <span class="text-muted">
                                Showing 1 to 3 of 3 users
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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

code {
    background-color: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    color: #495057;
}

.gap-2 {
    gap: 0.5rem;
}

@media (max-width: 768px) {
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
        text-align: center;
    }
}
</style>
@endsection