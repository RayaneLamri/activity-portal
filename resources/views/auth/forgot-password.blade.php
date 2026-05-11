<x-guest-layout body-class="app app-reset-password p-0">
    <h2 class="auth-heading text-center mb-4">Reset password</h2>

    <div class="auth-form-container text-start">
        <p class="text-muted mb-4">
            {{ __('Forgot your password? No problem. Let us know your email address and we will email you a password reset link.') }}
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="d-grid">
                <x-primary-button class="w-100 theme-btn mx-auto">{{ __('Email Password Reset Link') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>