@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Admin Requests</h1>

    @if (session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Accepted By</th>
                    <th>Accepted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->email }}</td>
                        <td>{{ ucfirst($request->status) }}</td>
                        <td>{{ $request->accepted_by ?? '-' }}</td>
                        <td>{{ $request->accepted_at ? \Carbon\Carbon::parse($request->accepted_at)->toDateString() : '-' }}</td>
                        <td>
                            @if ($request->status === 'pending')
                                <form method="POST" action="{{ route('others.admin-requests.accept', $request) }}" style="display:inline-block">
                                    @csrf
                                    <button class="btn btn-sm btn-success" type="submit">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('others.admin-requests.reject', $request) }}" style="display:inline-block">
                                    @csrf
                                    <button class="btn btn-sm btn-danger" type="submit">Reject</button>
                                </form>
                            @else
                                <span class="text-muted">Processed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No admin requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $requests->links() }}
    </div>
</div>
@endsection

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

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="mb-0">Admin requests</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


