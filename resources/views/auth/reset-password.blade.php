@extends('layouts.guest')

@section('content')
<div class="text-center">
    <h4 class="font-size-18 mt-2">Reset Password</h4>
    <p class="text-muted">Enter your new password below.</p>
</div>

<div class="p-3">
    <form class="form-horizontal mt-4" action="{{ route('password.reset') }}" method="POST" id="resetForm">
        @csrf
        
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <div class="input-group">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Enter new password"
                       required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                    <i id="password-eye" class="mdi mdi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="password-strength" class="form-text"></div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <div class="input-group">
                <input type="password" 
                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       placeholder="Confirm new password"
                       required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                    <i id="password_confirmation-eye" class="mdi mdi-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="password-match" class="form-text"></div>
        </div>

        <div class="mb-3 row">
            <div class="col-12 text-center">
                <button class="btn btn-primary w-md waves-effect waves-light" type="submit" id="submitBtn" disabled>
                    <i class="mdi mdi-lock-reset me-1"></i> Reset Password
                </button>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-muted">
                <i class="mdi mdi-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById(fieldId + '-eye');
        
        if (field.type === 'password') {
            field.type = 'text';
            eye.className = 'mdi mdi-eye-off';
        } else {
            field.type = 'password';
            eye.className = 'mdi mdi-eye';
        }
    }

    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = [];
        
        if (password.length >= 8) strength++;
        else feedback.push('At least 8 characters');
        
        if (/[a-z]/.test(password)) strength++;
        else feedback.push('Lowercase letter');
        
        if (/[A-Z]/.test(password)) strength++;
        else feedback.push('Uppercase letter');
        
        if (/[0-9]/.test(password)) strength++;
        else feedback.push('Number');
        
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        else feedback.push('Special character');
        
        const strengthDiv = document.getElementById('password-strength');
        const colors = ['text-danger', 'text-warning', 'text-info', 'text-primary', 'text-success'];
        const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        
        if (password.length === 0) {
            strengthDiv.innerHTML = '';
            return;
        }
        
        strengthDiv.className = 'form-text ' + colors[strength - 1];
        strengthDiv.textContent = `Strength: ${labels[strength - 1]}${feedback.length > 0 ? ' (Missing: ' + feedback.join(', ') + ')' : ''}`;
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const matchDiv = document.getElementById('password-match');
        const submitBtn = document.getElementById('submitBtn');
        
        if (confirmPassword.length === 0) {
            matchDiv.innerHTML = '';
            submitBtn.disabled = false;
            return;
        }
        
        if (password === confirmPassword) {
            matchDiv.className = 'form-text text-success';
            matchDiv.textContent = '✓ Passwords match';
            submitBtn.disabled = false;
        } else {
            matchDiv.className = 'form-text text-danger';
            matchDiv.textContent = '✗ Passwords do not match';
            submitBtn.disabled = true;
        }
    }

    // Event listeners
    document.getElementById('password').addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });

    document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);
</script>
@endsection