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

            <div class="border-top pt-3 mt-4">
                <h3 class="h6 mb-3">Preferences</h3>

                <div class="mb-3">
                    <x-input-label for="city" value="Preferred city" />
                    <select id="city" name="city" class="form-select">
                        <option value="">No preference</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" @selected(old('city') === $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('city')" />
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <x-input-label for="min_age" value="Preferred minimum age" />
                        <x-text-input id="min_age" name="min_age" type="number" :value="old('min_age')" />
                        <x-input-error :messages="$errors->get('min_age')" />
                    </div>
                    <div class="col-md-6">
                        <x-input-label for="max_age" value="Preferred maximum age" />
                        <x-text-input id="max_age" name="max_age" type="number" :value="old('max_age')" />
                        <x-input-error :messages="$errors->get('max_age')" />
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <x-input-label for="starts_on" value="Available from" />
                        <x-text-input id="starts_on" name="starts_on" type="date" :value="old('starts_on')" />
                        <x-input-error :messages="$errors->get('starts_on')" />
                    </div>
                    <div class="col-md-6">
                        <x-input-label for="ends_on" value="Available until" />
                        <x-text-input id="ends_on" name="ends_on" type="date" :value="old('ends_on')" />
                        <x-input-error :messages="$errors->get('ends_on')" />
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <x-primary-button class="w-100 theme-btn mx-auto">{{ __('Register') }}</x-primary-button>
            </div>

            <div class="auth-option text-center pt-4">
                {{ __('Already registered?') }}
                <a class="app-link" href="{{ route('login') }}">{{ __('Log in') }}</a>
            </div>
        </form>
    </div>
</x-guest-layout>
