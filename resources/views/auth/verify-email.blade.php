<x-guest-layout>
    <h2 class="auth-heading text-center mb-4">Verify email</h2>

    <div class="auth-form-container text-start">
        <p class="text-muted mb-4">
            {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="d-grid gap-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button class="w-100">{{ __('Resend Verification Email') }}</x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn app-btn-secondary w-100">{{ __('Log Out') }}</button>
            </form>
        </div>
    </div>
</x-guest-layout>