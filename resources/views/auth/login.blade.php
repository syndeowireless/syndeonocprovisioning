@extends('layouts.guest')

@section('content')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form class="form-horizontal mt-4" method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="{{ __('Enter email') }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="{{ __('Enter password') }}" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Button -->
        <div class="mb-3 row mt-4">
            <div class="col-6">
                <div class="form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember" value="1">
                    <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                </div>
            </div>
            <div class="w-1/2 text-right">                      
    <x-primary-button style="
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
        background-color: #3b82f6 !important;
        color: white !important;
        border-radius: 0.25rem !important;
        transition-property: all !important;
        transition-duration: 300ms !important;
        transition-timing-function: ease-in-out !important;
        border-width: 2px !important;
        border-color: #facc15 !important;
    " 
    onmouseover="
        this.style.backgroundColor = '#facc15 !important';
        this.style.borderColor = '#1e40af !important';
        this.style.transform = 'translateY(-0.125rem) !important';
        this.style.color = 'black !important';
    "
    onmouseout="
        this.style.backgroundColor = '#3b82f6 !important';
        this.style.borderColor = '#facc15 !important';
        this.style.transform = 'translateY(0) !important';
        this.style.color = 'white !important';
    ">                              
        {{ __('Log in') }}                      
    </x-primary-button>              
</div>
        </div>

        <!-- Forgot Password Link -->
        <div class="mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
        </div>
        <div class="mt-4 text-center">
            © <script>document.write(new Date().getFullYear())</script> Syndeo Wireless <span class="d-none d-sm-inline-block"></span>
        </div>
    </form>
@endsection