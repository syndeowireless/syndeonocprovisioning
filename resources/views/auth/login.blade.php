@extends('layouts.guest')
@section('content')
<x-guest-layout>
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
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                </div>
            </div>
            <div class="col-6 text-end">
                <x-primary-button class="btn btn-primary w-md waves-effect waves-light">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>

        <!-- Forgot Password Link -->
        <div class="form-group mb-0 row">
            <div class="col-12 mt-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-muted">
                        <i class="mdi mdi-lock"></i> {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
        </div>
    </form>
</x-guest-layout>
@endsection