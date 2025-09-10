@extends('layouts.guest')

@section('content')
<style>
    .forgot-password-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        padding: 1.5rem 2.5rem 2.5rem 2.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .forgot-password-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #13395d 0%, #fbbf0f 100%);
        border-radius: 20px 20px 0 0;
    }
    
    .page-title {
        color: #13395d;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
        position: relative;
    }
    
    .page-subtitle {
        color: #6c757d;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .form-group-enhanced {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .form-label-enhanced {
        font-weight: 600;
        color: #13395d;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-control-enhanced {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }
    
    .form-control-enhanced:focus {
        border-color: #fbbf0f;
        box-shadow: 0 0 0 0.2rem rgba(251, 191, 15, 0.25), 0 4px 12px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        transform: translateY(-1px);
    }
    
    .form-control-enhanced.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .input-icon {
        color: #13395d;
        font-size: 1.1rem;
    }
    
    .button-container {
        margin: 2rem 0 1.5rem 0;
        text-align: center;
    }
    
    .enhanced-primary-button {
        background: linear-gradient(135deg, #13395d 0%, #1e4a73 100%);
        border: 2px solid #fbbf0f;
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        text-transform: none;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(19, 57, 93, 0.3);
        position: relative;
        overflow: hidden;
        min-width: 160px;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .enhanced-primary-button:hover {
        background: linear-gradient(135deg, #fbbf0f 0%, #e6ac0e 100%);
        border-color: #13395d;
        color: #13395d;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(251, 191, 15, 0.4);
    }
    
    .enhanced-primary-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(19, 57, 93, 0.3);
    }
    
    .back-link {
        color: #6c757d;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }
    
    .back-link:hover {
        color: #13395d;
        background-color: rgba(19, 57, 93, 0.05);
        text-decoration: none;
        transform: translateX(-2px);
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #dc3545;
        font-weight: 500;
    }
    
    .form-animation {
        animation: slideInUp 0.6s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .icon-pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
    
    @media (max-width: 576px) {
        .forgot-password-container {
            padding: 1rem 1.5rem 1.5rem 1.5rem;
            margin: 0.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .enhanced-primary-button {
            width: 100%;
            padding: 1rem 2rem;
        }
    }
</style>

<div class="forgot-password-container form-animation">
    <div class="text-center">
        <div class="mb-2">
            <i class="mdi mdi-lock-reset icon-pulse" style="font-size: 2.5rem; color: #fbbf0f; margin-bottom: 0.5rem;"></i>
        </div>
        <h4 class="page-title">Forgot your password?</h4>
        <p class="page-subtitle">No worries! Enter your email address and we'll send you an OTP to reset your password securely.</p>
    </div>

    <form class="mt-4" action="{{ route('password.send-otp') }}" method="POST">
        @csrf
        
        <div class="form-group-enhanced">
            <label for="email" class="form-label-enhanced">
                <i class="mdi mdi-email input-icon"></i>
                Email Address
            </label>
            <input type="email" 
                   class="form-control form-control-enhanced @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   placeholder="Enter your email address"
                   required>
            @error('email')
                <div class="invalid-feedback">
                    <i class="mdi mdi-alert-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="button-container">
            <x-primary-button type="submit" class="enhanced-primary-button" onclick="createRipple(event, this)">
                <i class="mdi mdi-send me-2"></i>
                Send OTP
            </x-primary-button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="back-link">
                <i class="mdi mdi-arrow-left"></i>
                Back to Login
            </a>
        </div>
    </form>
</div>

<script>
function createRipple(event, element) {
    const button = element;
    const circle = document.createElement('span');
    const diameter = Math.max(button.clientWidth, button.clientHeight);
    const radius = diameter / 2;
    
    const rect = button.getBoundingClientRect();
    const x = event.clientX - rect.left - radius;
    const y = event.clientY - rect.top - radius;
    
    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${x}px`;
    circle.style.top = `${y}px`;
    circle.classList.add('ripple');
    
    const ripple = button.getElementsByClassName('ripple')[0];
    if (ripple) {
        ripple.remove();
    }
    
    button.appendChild(circle);
    
    setTimeout(() => {
        circle.remove();
    }, 600);
}
</script>
@endsection