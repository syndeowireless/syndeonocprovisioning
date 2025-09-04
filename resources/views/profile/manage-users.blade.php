@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="fancy-title-container mb-4">
                    <h2 class="fancy-title mb-3">
                        <i class="mdi mdi-account-supervisor-circle me-3"></i>
                        <span class="title-text">User Management</span>
                        <div class="title-underline"></div>
                    </h2>
                    <x-primary-button type="button" class="btn-lg create-user-btn">
                        <i class="mdi mdi-plus-circle me-2"></i>Create New User
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
                                    <td>
                                        <div class="password-container d-flex align-items-center">
                                            <code class="password-text" data-password="affiliatedpass123">••••••••••••••••</code>
                                            <button type="button" class="btn btn-link p-0 ms-2 password-toggle" onclick="togglePassword(this)">
                                                <i class="mdi mdi-eye-off password-icon"></i>
                                            </button>
                                        </div>
                                    </td>
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
                                    <td>
                                        <div class="password-container d-flex align-items-center">
                                            <code class="password-text" data-password="walmartpass456">••••••••••••••</code>
                                            <button type="button" class="btn btn-link p-0 ms-2 password-toggle" onclick="togglePassword(this)">
                                                <i class="mdi mdi-eye-off password-icon"></i>
                                            </button>
                                        </div>
                                    </td>
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
                                    <td>
                                        <div class="password-container d-flex align-items-center">
                                            <code class="password-text" data-password="xyzrealestate789">•••••••••••••••••</code>
                                            <button type="button" class="btn btn-link p-0 ms-2 password-toggle" onclick="togglePassword(this)">
                                                <i class="mdi mdi-eye-off password-icon"></i>
                                            </button>
                                        </div>
                                    </td>
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
/* Fancy Title Styles */
.fancy-title-container {
    text-align: left;
}

.fancy-title {
    position: relative;
    display: inline-flex;
    align-items: center;
    font-weight: 700;
    color: #13395d;
    text-shadow: 0 2px 4px rgba(19, 57, 93, 0.1);
    margin-bottom: 0;
}

.fancy-title i {
    font-size: 2.5rem;
    background: linear-gradient(135deg, #13395d, #1e5a8a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 2px 4px rgba(19, 57, 93, 0.2));
}

.title-text {
    background: linear-gradient(135deg, #13395d, #1e5a8a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 2.2rem;
    letter-spacing: -0.5px;
}

.title-underline {
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #13395d, #1e5a8a, transparent);
    border-radius: 2px;
    animation: slideIn 0.8s ease-out;
}

@keyframes slideIn {
    from {
        width: 0;
        opacity: 0;
    }
    to {
        width: 100%;
        opacity: 1;
    }
}

.create-user-btn {
    background: linear-gradient(135deg, #13395d, #1e5a8a);
    border: none;
    padding: 12px 24px;
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(19, 57, 93, 0.3);
    transition: all 0.3s ease;
}

.create-user-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(19, 57, 93, 0.4);
    background: linear-gradient(135deg, #1e5a8a, #13395d);
}

/* Password Toggle Styles */
.password-container {
    position: relative;
}

.password-toggle {
    color: #6c757d;
    text-decoration: none;
    transition: color 0.2s ease;
    border: none;
    background: none;
    font-size: 1.1rem;
}

.password-toggle:hover {
    color: #13395d;
}

.password-toggle:focus {
    box-shadow: none;
    outline: none;
}

.password-icon {
    transition: all 0.2s ease;
}

.password-text {
    min-width: 120px;
    display: inline-block;
    transition: all 0.3s ease;
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
    .fancy-title {
        font-size: 1.8rem;
    }
    
    .fancy-title i {
        font-size: 2rem;
    }
    
    .title-text {
        font-size: 1.6rem;
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
        text-align: center;
    }
}
</style>

<script>
function togglePassword(button) {
    const passwordText = button.parentElement.querySelector('.password-text');
    const icon = button.querySelector('.password-icon');
    const actualPassword = passwordText.getAttribute('data-password');
    
    if (icon.classList.contains('mdi-eye-off')) {
        // Show password
        passwordText.textContent = actualPassword;
        icon.classList.remove('mdi-eye-off');
        icon.classList.add('mdi-eye');
        button.setAttribute('title', 'Hide password');
    } else {
        // Hide password
        passwordText.textContent = '•'.repeat(actualPassword.length);
        icon.classList.remove('mdi-eye');
        icon.classList.add('mdi-eye-off');
        button.setAttribute('title', 'Show password');
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.password-toggle');
    toggleButtons.forEach(button => {
        button.setAttribute('title', 'Show password');
    });
});
</script>
@endsection