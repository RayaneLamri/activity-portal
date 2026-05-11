<x-guest-layout>
    <h2 class="auth-heading text-center mb-4">Log in</h2>

    <div class="auth-form-container text-start">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="form-check mb-3">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
            </div>

            <div class="d-grid gap-2">
                <x-primary-button class="w-100 theme-btn mx-auto">{{ __('Log in') }}</x-primary-button>
            </div>

            @if (Route::has('password.request'))
                <div class="auth-option text-center pt-4">
                    <a class="app-link" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                </div>
            @endif
        </form>
    </div>
</x-guest-layout>