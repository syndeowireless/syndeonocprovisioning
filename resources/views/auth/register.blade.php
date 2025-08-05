 

@section('content')
    <form class="form-horizontal mt-4" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="{{ __('Enter name') }}" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="{{ __('Enter email') }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="new-password" 
                            placeholder="{{ __('Enter password') }}" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="form-control"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" 
                            placeholder="{{ __('Confirm password') }}" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mb-3 row mt-4">
            <div class="col-12 text-end">
                <x-primary-button class="btn btn-primary w-md waves-effect waves-light">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>

        <div class="mb-0 row">
            <div class="col-12 mt-4">
                <p class="text-muted mb-0 font-size-14">{{ __('By registering you agree to the') }} {{ config('app.name', 'Laravel') }} <a href="#" class="text-primary">{{ __('Terms of Use') }}</a></p>
            </div>
        </div>
    </form>
@endsection

