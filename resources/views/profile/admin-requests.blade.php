@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h1 class="simple-title mb-3">Admin Requests</h1>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-information-outline me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Admin Requests Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-plus me-2"></i>
                        Pending Admin Requests
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($requests->where('status', 'pending')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">ID</th>
                                        <th class="border-0">Name</th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Requested At</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests->where('status', 'pending') as $request)
                                        <tr>
                                            <td class="align-middle">
                                                <span class="badge bg-primary">#{{ $request->id }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                            <i class="mdi mdi-account font-size-16"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $request->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-muted">{{ $request->email }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-warning">{{ ucfirst($request->status) }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-muted">{{ $request->created_at ? \Carbon\Carbon::parse($request->created_at)->format('M d, Y') : '-' }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="btn-group" role="group">
                                                    <form method="POST" action="{{ route('others.admin-requests.accept', $request) }}" style="display:inline-block">
                                                        @csrf
                                                        <button class="btn btn-sm btn-success" type="submit" title="Accept Request">
                                                            <i class="mdi mdi-check"></i> Accept
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('others.admin-requests.reject', $request) }}" style="display:inline-block">
                                                        @csrf
                                                        <button class="btn btn-sm btn-danger" type="submit" title="Reject Request">
                                                            <i class="mdi mdi-close"></i> Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="mdi mdi-account-check-outline font-size-48 text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Pending Requests</h5>
                            <p class="text-muted mb-0">All admin requests have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #13395d; border-bottom: 4px solid #fbbf0f;">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-bell-outline me-2"></i>
                        Recent Admin Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($requests->where('status', '!=', 'pending')->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($requests->where('status', '!=', 'pending')->take(10) as $request)
                                <div class="list-group-item border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title rounded-circle {{ $request->status === 'accepted' ? 'bg-success' : 'bg-danger' }}">
                                                <i class="mdi mdi-{{ $request->status === 'accepted' ? 'check' : 'close' }} text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <strong>{{ $request->name }}</strong> 
                                                was {{ $request->status === 'accepted' ? 'accepted' : 'rejected' }} as an admin
                                            </h6>
                                            <p class="mb-1 text-muted">
                                                @if($request->accepted_by)
                                                    by <strong>{{ $request->accepted_by }}</strong>
                                                @endif
                                                @if($request->accepted_at)
                                                    on {{ \Carbon\Carbon::parse($request->accepted_at)->format('M d, Y \a\t g:i A') }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{ $request->status === 'accepted' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="mdi mdi-history font-size-48 text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Recent Activity</h5>
                            <p class="text-muted mb-0">Admin request activity will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.simple-title {
    color: #13395d;
    font-weight: 600;
    font-size: 1.75rem;
    margin-bottom: 1rem;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-group .btn {
    margin: 0 2px;
}

.list-group-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f8f9fa;
}

.list-group-item:last-child {
    border-bottom: none;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

@media (max-width: 768px) {
    .simple-title {
        font-size: 1.5rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
@endsection


