@extends('layouts.guest')

@section('content')
<style>
    .otp-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        margin: -1rem -1rem 1rem -1rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .otp-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        opacity: 0.3;
    }
    
    .otp-header {
        position: relative;
        z-index: 1;
    }
    
    .otp-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .otp-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }
    
    .otp-timer {
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,0.2);
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    
    .otp-form-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-top: 1.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        position: relative;
    }
    
    .otp-input-group {
        margin-bottom: 2rem;
    }
    
    .otp-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .otp-input {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 0.5rem;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .otp-input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1), 0 4px 6px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }
    
    .otp-input.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    
    .otp-input.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1), 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .otp-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .otp-btn {
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .otp-btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .otp-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .otp-btn-secondary {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .otp-btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .otp-back-link {
        display: inline-flex;
        align-items: center;
        color: #6b7280;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }
    
    .otp-back-link:hover {
        color: #374151;
        background: #f3f4f6;
        text-decoration: none;
        transform: translateX(-2px);
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #ef4444;
        font-weight: 500;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .timer-pulse {
        animation: pulse 2s infinite;
    }
    
    @media (max-width: 576px) {
        .otp-container {
            padding: 1.5rem;
            margin: -0.75rem -0.75rem 1rem -0.75rem;
        }
        
        .otp-form-container {
            padding: 1.5rem;
        }
        
        .otp-buttons {
            grid-template-columns: 1fr;
        }
        
        .otp-input {
            font-size: 1.25rem;
            letter-spacing: 0.25rem;
        }
    }
</style>

<div class="otp-container">
    <div class="otp-header text-center">
        <h2 class="otp-title">Verify Your Identity</h2>
        <p class="otp-subtitle">We've sent a 6-digit verification code to your email address. Please enter it below to continue.</p>
        <div class="otp-timer timer-pulse">
            <i class="mdi mdi-clock-outline me-2"></i>
            Expires in 2 minutes
        </div>
    </div>
</div>

<div class="otp-form-container">
    <form class="form-horizontal" action="{{ route('password.verify-otp') }}" method="POST">
        @csrf
        
        <div class="otp-input-group">
            <label for="otp" class="otp-label">Verification Code</label>
            <input type="text" 
                   class="otp-input @error('otp') is-invalid @enderror" 
                   id="otp" 
                   name="otp" 
                   value="{{ old('otp') }}"
                   placeholder="000000"
                   maxlength="6"
                   required>
            @error('otp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="otp-buttons">
            <x-primary-button class="otp-btn otp-btn-primary waves-effect waves-light" type="submit">
                <i class="mdi mdi-check-circle me-2"></i> Verify Code
            </x-primary-button>
            <x-primary-button type="button" 
                    class="otp-btn otp-btn-secondary waves-effect waves-light" 
                    onclick="resendOtp()">
                <i class="mdi mdi-refresh me-2"></i> Resend Code
            </x-primary-button>
        </div>

        <div class="text-center">
            <a href="{{ route('password.request') }}" class="otp-back-link">
                <i class="mdi mdi-arrow-left me-2"></i> Back to Email
            </a>
        </div>
    </form>
</div>

<script>
    // Auto-focus on OTP input
    document.getElementById('otp').focus();
    
    // Only allow numbers in OTP input
    document.getElementById('otp').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Resend OTP function
    function resendOtp() {
        if (confirm('Are you sure you want to resend the OTP?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("password.resend-otp") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
