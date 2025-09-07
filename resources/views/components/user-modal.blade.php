@props([
    'modalId',
    'title',
    'icon',
    'buttonText',
    'formId',
    'includeHiddenId' => false,
    'userNameId' => 'userName',
    'userEmailId' => 'userEmail',
    'hiddenInputId' => 'userId',
    'isCreateModal' => false,
    'isUpdateModal' => false,
    'isResetPasswordModal' => false
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);">
            <div class="modal-header" style="background-color: #13395d; color: white; border-bottom: 3px solid #fbbf0f; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="{{ $modalId }}Label">
                    <i class="{{ $icon }} me-2"></i>{{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <form id="{{ $formId }}">
                    @if($includeHiddenId)
                        <input type="hidden" id="{{ $hiddenInputId }}" name="userId">
                    @endif
                    
                    @if(!$isResetPasswordModal)
                        <div class="mb-3">
                            <label for="{{ $userNameId }}" class="form-label" style="color: #13395d; font-weight: 600;">User Name</label>
                            <input type="text" class="form-control" id="{{ $userNameId }}" name="userName"
                                    style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#13395d'; this.style.boxShadow='0 0 0 0.2rem rgba(19, 57, 93, 0.25)';"
                                    onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';"
                                    placeholder="Enter user name" required>
                        </div>
                        <div class="mb-3">
                            <label for="{{ $userEmailId }}" class="form-label" style="color: #13395d; font-weight: 600;">Email</label>
                            <input type="email" class="form-control" id="{{ $userEmailId }}" name="userEmail"
                                    style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#13395d'; this.style.boxShadow='0 0 0 0.2rem rgba(19, 57, 93, 0.25)';"
                                    onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';"
                                    placeholder="Enter email address" required>
                        </div>
                    @endif

                    @if($isResetPasswordModal)
                        <div class="mb-3">
                            <label for="resetUserName" class="form-label" style="color: #13395d; font-weight: 600;">User Name</label>
                            <input type="text" class="form-control" id="resetUserName" name="userName"
                                    style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem; background-color: #f8f9fa;"
                                    readonly placeholder="User name" required>
                        </div>
                        <div class="mb-3">
                            <label for="resetCurrentPassword" class="form-label" style="color: #13395d; font-weight: 600;">Current Password</label>
                            <input type="password" class="form-control" id="resetCurrentPassword" name="currentPassword"
                                    style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem; background-color: #f8f9fa;"
                                   readonly placeholder="••••••••••••" required>
                        </div>
                        <div class="mb-3">
                            <label for="resetPassword" class="form-label" style="color: #13395d; font-weight: 600;">New Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="resetPassword" name="newPassword"
                                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 3rem 0.75rem 0.75rem; transition: all 0.3s ease;"
                                       onfocus="this.style.borderColor='#13395d'; this.style.boxShadow='0 0 0 0.2rem rgba(19, 57, 93, 0.25)';"
                                        onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';"
                                        placeholder="Enter new password" required>
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0" 
                                        style="border: none; background: none; color: #6c757d; z-index: 10;"
                                        onclick="togglePasswordVisibility('resetPassword', this)">
                                    <i class="mdi mdi-eye" style="font-size: 1.2rem;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="resetConfirmPassword" class="form-label" style="color: #13395d; font-weight: 600;">Confirm New Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="resetConfirmPassword" name="confirmPassword"
                                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 3rem 0.75rem 0.75rem; transition: all 0.3s ease;"
                                       onfocus="this.style.borderColor='#13395d'; this.style.boxShadow='0 0 0 0.2rem rgba(19, 57, 93, 0.25)';"
                                        onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';"
                                        placeholder="Confirm new password" required>
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0" 
                                        style="border: none; background: none; color: #6c757d; z-index: 10;"
                                        onclick="togglePasswordVisibility('resetConfirmPassword', this)">
                                    <i class="mdi mdi-eye" style="font-size: 1.2rem;"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    @if($isCreateModal)
                        <div class="mb-3">
                            <label for="userRole" class="form-label" style="color: #13395d; font-weight: 600;">User Role</label>
                            <select class="form-select" id="userRole" name="userRole"
                                    style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#13395d'; this.style.boxShadow='0 0 0 0.2rem rgba(19, 57, 93, 0.25)';"
                                    onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label" style="color: #13395d; font-weight: 600;">Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="userPassword" name="userPassword"
                                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 3rem 0.75rem 0.75rem; transition: all 0.3s ease;"
                                       onfocus="this.style.borderColor='#13395d'; this.style.boxShadow='0 0 0 0.2rem rgba(19, 57, 93, 0.25)';"
                                        onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';"
                                        placeholder="Enter password" required>
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0" 
                                        style="border: none; background: none; color: #6c757d; z-index: 10;"
                                        onclick="togglePasswordVisibility('userPassword', this)">
                                    <i class="mdi mdi-eye" style="font-size: 1.2rem;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label" style="color: #13395d; font-weight: 600;">Confirm Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 3rem 0.75rem 0.75rem; transition: all 0.3s ease;"
                                       onfocus="this.style.borderColor='#13395d'; this.style.boxShadow='0 0 0 0.2rem rgba(19, 57, 93, 0.25)';"
                                        onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';"
                                        placeholder="Confirm password" required>
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0" 
                                        style="border: none; background: none; color: #6c757d; z-index: 10;"
                                        onclick="togglePasswordVisibility('confirmPassword', this)">
                                    <i class="mdi mdi-eye" style="font-size: 1.2rem;"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    @if($isUpdateModal)
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label" style="color: #13395d; font-weight: 600;">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                                    style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem; transition: all 0.3s ease; background-color: #f8f9fa;"
                                   readonly placeholder="••••••••••••" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label" style="color: #13395d; font-weight: 600;">New Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="newPassword" name="newPassword"
                                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 0.75rem 3rem 0.75rem 0.75rem; transition: all 0.3s ease;"
                                       onfocus="this.style.borderColor='#13395d'; this.style.boxShadow='0 0 0 0.2rem rgba(19, 57, 93, 0.25)';"
                                        onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none';"
                                        placeholder="Enter new password" required>
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0" 
                                        style="border: none; background: none; color: #6c757d; z-index: 10;"
                                        onclick="togglePasswordVisibility('newPassword', this)">
                                    <i class="mdi mdi-eye" style="font-size: 1.2rem;"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 1.5rem 2rem; background-color: #f8f9fa; border-radius: 0 0 12px 12px;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                         style="background-color: #6c757d; border: 2px solid #6c757d; color: white; padding: 0.5rem 1.5rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#5a6268'; this.style.borderColor='#5a6268';"
                         onmouseout="this.style.backgroundColor='#6c757d'; this.style.borderColor='#6c757d';">
                    Cancel
                </button>
                <x-primary-button type="button" class="ms-2" style="padding: 0.5rem 1.5rem; border-radius: 8px; font-weight: 600;">
                    <i class="mdi mdi-check me-2"></i>{{ $buttonText }}
                </x-primary-button>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, toggleButton) {
    const passwordInput = document.getElementById(inputId);
    const icon = toggleButton.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('mdi-eye');
        icon.classList.add('mdi-eye-off');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('mdi-eye-off');
        icon.classList.add('mdi-eye');
    }
}
</script>