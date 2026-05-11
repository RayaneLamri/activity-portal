<x-guest-layout>
    <h2 class="auth-heading text-center mb-4">Confirm password</h2>

    <div class="auth-form-container text-start">
        <p class="text-muted mb-4">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="d-grid">
                <x-primary-button class="w-100 theme-btn mx-auto">{{ __('Confirm') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>