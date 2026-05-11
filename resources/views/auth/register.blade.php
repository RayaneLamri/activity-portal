<x-guest-layout body-class="app app-signup p-0">
    <h2 class="auth-heading text-center mb-4">Create an account</h2>

    <div class="auth-form-container text-start">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="mb-3">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>

            <div class="d-grid gap-2">
                <x-primary-button class="w-100 theme-btn mx-auto">{{ __('Register') }}</x-primary-button>
            </div>

            <div class="auth-option text-center pt-4">
                {{ __('Already registered?') }}
                <a class="app-link" href="{{ route('login') }}">{{ __('Log in') }}</a>
            </div>
        </form>
    </div>
</x-guest-layout>