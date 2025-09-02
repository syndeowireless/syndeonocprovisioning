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
            <div class="col-6 text-end">
                <!-- <x-primary-button class="btn btn-primary w-md waves-effect waves-light">
                    {{ __('Log in') }}
                </x-primary-button> -->
                <button class="btn btn-custom btn-lg w-md waves-effect waves-light relative overflow-hidden transform transition-all duration-300 active:scale-95 hover:shadow-xl focus:ring-4 focus:ring-blue-300 focus:ring-opacity-50 group">
                    <span class="absolute inset-0 w-0 h-0 rounded-full transition-all duration-700 group-hover:w-full group-hover:h-full group-hover:opacity-30 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" style="background-color: #FBBF0F; opacity: 0;"></span>
                    <span class="relative z-10">{{ __('Log in') }}</span>
                </button>
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