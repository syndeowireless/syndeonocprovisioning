@extends('layouts.guest')

@section('content')
<div class="text-center">
    <h4 class="font-size-18 mt-2">Forgot your password?</h4>
    <p class="text-muted">Enter your email address and we'll send you an OTP to reset your password.</p>
</div>

<div class="p-3">
    <form class="form-horizontal mt-4" action="{{ route('password.send-otp') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   placeholder="Enter your email address"
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 row">
            <div class="col-12 text-center">
                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">
                    <i class="mdi mdi-email me-1"></i> Send OTP
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
@endsection