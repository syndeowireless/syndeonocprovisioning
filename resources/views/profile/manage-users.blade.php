@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="title-container mb-4">
                    <h1 class="simple-title mb-3">User Management</h1>
                    <x-primary-button type="button" class="btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
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
                                <tr class="clickable-row" data-user-id="{{ $user->id }}">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($user->role !== 'admin')
                                        <div class="d-flex gap-2 flex-wrap">
                                            <x-primary-button type="button" class="btn-sm px-3 py-1" style="font-size: 0.75rem;" 
                                                data-bs-toggle="modal" data-bs-target="#updateUserModal" 
                                                onclick="populateUpdateModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')">
                                                Update
                                            </x-primary-button>
                                            <x-primary-button type="button" class="btn-sm px-3 py-1" style="font-size: 0.75rem;" 
                                                data-bs-toggle="modal" data-bs-target="#resetPasswordModal"
                                                onclick="populateResetPasswordModal('{{ $user->id }}', '{{ $user->name }}')">
                                                Reset Password
                                            </x-primary-button>
                                            <x-primary-button type="button" class="btn-sm px-3 py-1" style="font-size: 0.75rem; background-color: #dc3545; border-color: #dc3545;" 
                                                onmouseover="this.style.backgroundColor='#c82333'; this.style.borderColor='#bd2130';" 
                                                onmouseout="this.style.backgroundColor='#dc3545'; this.style.borderColor='#dc3545';"
                                                onclick="confirmDeleteUser('{{ $user->id }}', '{{ $user->name }}')">
                                                Delete
                                            </x-primary-button>
                                        </div>
                                        @else
                                        <span class="text-muted"></span>
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

<!-- Create New User Modal -->
<x-user-modal 
    modal-id="createUserModal"
    title="Create New User"
    icon="mdi mdi-account-plus"
    button-text="Create"
    form-id="createUserForm"
    user-name-id="userName"
    user-email-id="userEmail"
    :is-create-modal="true" />

<!-- Update User Modal -->
<x-user-modal 
    modal-id="updateUserModal"
    title="Update User"
    icon="mdi mdi-account-edit"
    button-text="Update"
    form-id="updateUserForm"
    :include-hidden-id="true"
    hidden-input-id="updateUserId"
    user-name-id="updateUserName"
    user-email-id="updateUserEmail"
    :is-update-modal="true" />

<!-- Reset Password Modal -->
<x-user-modal 
    modal-id="resetPasswordModal"
    title="Reset Password"
    icon="mdi mdi-lock-reset"
    button-text="Reset Password"
    form-id="resetPasswordForm"
    :include-hidden-id="true"
    hidden-input-id="resetUserId"
    user-name-id="resetUserName"
    :is-reset-password-modal="true" />

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);">
            <div class="modal-header" style="background-color: #dc3545; color: white; border-bottom: 3px solid #c82333; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="deleteUserModalLabel">
                    <i class="mdi mdi-delete-alert me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div class="text-center">
                    <i class="mdi mdi-alert-circle" style="font-size: 4rem; color: #dc3545; margin-bottom: 1rem;"></i>
                    <h4 style="color: #dc3545; margin-bottom: 1rem;">Are you sure?</h4>
                    <p style="color: #6c757d; font-size: 1.1rem; margin-bottom: 0;">
                        Are you sure about deleting this user record? This action cannot be undone.
                    </p>
                    <p style="color: #495057; font-weight: 600; margin-top: 1rem;">
                        User: <span id="deleteUserName" style="color: #dc3545;"></span>
                    </p>
                </div>
                <input type="hidden" id="deleteUserId" value="">
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 1.5rem 2rem; background-color: #f8f9fa; border-radius: 0 0 12px 12px;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                         style="background-color: #6c757d; border: 2px solid #6c757d; color: white; padding: 0.5rem 1.5rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#5a6268'; this.style.borderColor='#5a6268';"
                         onmouseout="this.style.backgroundColor='#6c757d'; this.style.borderColor='#6c757d';">
                    No, Cancel
                </button>
                <button type="button" class="btn" id="confirmDeleteBtn"
                         style="background-color: #dc3545; border: 2px solid #dc3545; color: white; padding: 0.5rem 1.5rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#c82333'; this.style.borderColor='#bd2130';"
                         onmouseout="this.style.backgroundColor='#dc3545'; this.style.borderColor='#dc3545';">
                    <i class="mdi mdi-delete me-2"></i>Yes, Delete
                </button>
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
    
    .d-flex.gap-2.flex-wrap {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-sm {
        font-size: 0.7rem !important;
        padding: 0.25rem 0.5rem !important;
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

    // Handle create user form submission
    const createUserForm = document.getElementById('createUserForm');
    if (createUserForm) {
        createUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            createUser();
        });
    }

    // Handle delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            deleteUser();
        });
    }

    // Real-time password confirmation validation
    const userPassword = document.getElementById('userPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            validatePasswordMatch();
        });
    }
    
    if (userPassword) {
        userPassword.addEventListener('input', function() {
            validatePasswordMatch();
        });
    }
});

// Function to populate update modal with user data
function populateUpdateModal(userId, userName, userEmail) {
    document.getElementById('updateUserId').value = userId;
    document.getElementById('updateUserName').value = userName;
    document.getElementById('updateUserEmail').value = userEmail;
}

// Function to populate reset password modal with user data
function populateResetPasswordModal(userId, userName) {
    document.getElementById('resetUserId').value = userId;
    document.getElementById('resetUserName').value = userName;
    // Clear password fields when modal opens
    document.getElementById('resetCurrentPassword').value = '••••••••••••';
    document.getElementById('resetPassword').value = '';
    document.getElementById('resetConfirmPassword').value = '';
}

// Function to confirm delete user
function confirmDeleteUser(userId, userName) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('deleteUserName').textContent = userName;
    
    // Show the delete confirmation modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    deleteModal.show();
}

// Function to delete user
function deleteUser() {
    const userId = document.getElementById('deleteUserId').value;
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    // Disable button and show loading
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Deleting...';
    
    fetch(`/profile/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
            deleteModal.hide();
            
            // Show success message
            showAlert('success', data.message);
            
            // Remove the row from table
            const row = document.querySelector(`tr[data-user-id="${userId}"]`);
            if (row) {
                row.remove();
            } else {
                // If row not found, reload the page
                window.location.reload();
            }
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while deleting the user.');
    })
    .finally(() => {
        // Re-enable button
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="mdi mdi-delete me-2"></i>Yes, Delete';
    });
}

// Function to create user
function createUser() {
    const form = document.getElementById('createUserForm');
    const formData = new FormData(form);
    
    // Check if all required fields are filled
    const requiredFields = ['userName', 'userEmail', 'userRole', 'userPassword', 'confirmPassword'];
    let allFieldsFilled = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field || !field.value.trim()) {
            allFieldsFilled = false;
            field.style.borderColor = '#dc3545';
        } else {
            field.style.borderColor = '#e9ecef';
        }
    });
    
    if (!allFieldsFilled) {
        showAlert('error', 'Please fill out all required fields.');
        return;
    }
    
    // Check password match
    const password = document.getElementById('userPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (password !== confirmPassword) {
        showAlert('error', 'Password and confirm password do not match.');
        return;
    }
    
    const submitBtn = document.querySelector('#createUserModal [type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Creating...';
    
    fetch('/profile/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            userName: formData.get('userName'),
            userEmail: formData.get('userEmail'),
            userRole: formData.get('userRole'),
            userPassword: formData.get('userPassword'),
            confirmPassword: formData.get('confirmPassword')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const createModal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
            createModal.hide();
            
            // Show success message
            showAlert('success', data.message);
            
            // Clear form
            form.reset();
            
            // Add new user to table
            addUserToTable(data.user);
        } else {
            if (data.errors) {
                // Show validation errors
                let errorMessage = 'Please fix the following errors:\n';
                Object.values(data.errors).forEach(error => {
                    errorMessage += `• ${error[0]}\n`;
                });
                showAlert('error', errorMessage);
            } else {
                showAlert('error', data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while creating the user.');
    })
    .finally(() => {
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="mdi mdi-check me-2"></i>Create';
    });
}

// Function to add new user to table
function addUserToTable(user) {
    const tbody = document.querySelector('#usersTable tbody');
    const row = document.createElement('tr');
    row.className = 'clickable-row';
    row.setAttribute('data-user-id', String(user.id));

    const nameTd = document.createElement('td');
    nameTd.textContent = user.name;

    const emailTd = document.createElement('td');
    emailTd.textContent = user.email;

    const roleTd = document.createElement('td');
    const prettyRole = user.role ? (user.role.charAt(0).toUpperCase() + user.role.slice(1)) : '';
    roleTd.textContent = prettyRole;

    const createdTd = document.createElement('td');
    createdTd.textContent = user.created_at || '';

    const updatedTd = document.createElement('td');
    updatedTd.textContent = user.updated_at || '';

    const actionTd = document.createElement('td');
    const actionsDiv = document.createElement('div');
    actionsDiv.className = 'd-flex gap-2 flex-wrap';

    // Update button
    const updateBtn = document.createElement('button');
    updateBtn.type = 'button';
    updateBtn.className = 'btn btn-primary btn-sm px-3 py-1';
    updateBtn.style.fontSize = '0.75rem';
    updateBtn.textContent = 'Update';
    updateBtn.setAttribute('data-bs-toggle', 'modal');
    updateBtn.setAttribute('data-bs-target', '#updateUserModal');
    updateBtn.addEventListener('click', function() {
        populateUpdateModal(user.id, user.name, user.email);
    });

    // Reset Password button
    const resetBtn = document.createElement('button');
    resetBtn.type = 'button';
    resetBtn.className = 'btn btn-success btn-sm px-3 py-1';
    resetBtn.style.fontSize = '0.75rem';
    resetBtn.textContent = 'Reset Password';
    resetBtn.setAttribute('data-bs-toggle', 'modal');
    resetBtn.setAttribute('data-bs-target', '#resetPasswordModal');
    resetBtn.addEventListener('click', function() {
        populateResetPasswordModal(user.id, user.name);
    });

    // Delete button
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'btn btn-danger btn-sm px-3 py-1';
    deleteBtn.style.fontSize = '0.75rem';
    deleteBtn.textContent = 'Delete';
    deleteBtn.addEventListener('click', function() {
        confirmDeleteUser(user.id, user.name);
    });

    actionsDiv.appendChild(updateBtn);
    actionsDiv.appendChild(resetBtn);
    actionsDiv.appendChild(deleteBtn);
    actionTd.appendChild(actionsDiv);

    row.appendChild(nameTd);
    row.appendChild(emailTd);
    row.appendChild(roleTd);
    row.appendChild(createdTd);
    row.appendChild(updatedTd);
    row.appendChild(actionTd);

    tbody.insertBefore(row, tbody.firstChild);
}

// Function to validate password match
function validatePasswordMatch() {
    const password = document.getElementById('userPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (!password || !confirmPassword) return;
    
    // Remove existing error message
    const existingError = document.getElementById('passwordMatchError');
    if (existingError) {
        existingError.remove();
    }
    
    if (confirmPassword.value && password.value !== confirmPassword.value) {
        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.id = 'passwordMatchError';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '0.875rem';
        errorDiv.style.marginTop = '0.25rem';
        errorDiv.textContent = 'Password and confirm password do not match';
        
        confirmPassword.parentNode.appendChild(errorDiv);
        confirmPassword.style.borderColor = '#dc3545';
    } else {
        confirmPassword.style.borderColor = '#e9ecef';
    }
}

// Function to show alert messages
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show`;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    
    alertDiv.innerHTML = `
        <i class="mdi mdi-${type === 'error' ? 'alert-circle' : 'check-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

@endsection