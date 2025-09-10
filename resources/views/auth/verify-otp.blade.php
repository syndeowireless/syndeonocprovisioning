@extends('layouts.guest')

@section('content')
<div class="text-center">
    <h4 class="font-size-18 mt-2">Verify OTP</h4>
    <p class="text-muted">We've sent a 6-digit OTP to your email address. Enter it below to continue.</p>
    <p class="text-danger small">OTP expires in 2 minutes</p>
</div>

<div class="p-3">
    <form class="form-horizontal mt-4" action="{{ route('password.verify-otp') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="otp" class="form-label">Enter OTP</label>
            <input type="text" 
                   class="form-control text-center @error('otp') is-invalid @enderror" 
                   id="otp" 
                   name="otp" 
                   value="{{ old('otp') }}"
                   placeholder="000000"
                   maxlength="6"
                   style="font-size: 24px; letter-spacing: 8px; font-weight: bold;"
                   required>
            @error('otp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 row">
            <div class="col-6">
                <x-primary-button class="btn btn-primary w-100 waves-effect waves-light" type="submit">
                    <i class="mdi mdi-check me-1"></i> Verify OTP
                </x-primary-button>
            </div>
            <div class="col-6">
                <x-primary-button type="button" 
                        class="btn btn-outline-secondary w-100 waves-effect waves-light" 
                        onclick="resendOtp()">
                    <i class="mdi mdi-refresh me-1"></i> Resend OTP
                </x-primary-button>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('password.request') }}" class="text-muted">
                <i class="mdi mdi-arrow-left me-1"></i> Back to Email
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
