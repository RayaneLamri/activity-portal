<x-guest-layout body-class="app app-signup p-0">
    <h2 class="auth-heading text-center mb-2">Create an account</h2>
    <p class="text-muted text-center mb-4">Set your access and preferences to start matching activities.</p>

    <div class="auth-form-container auth-form-container-wide text-start">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row g-3">
                <div class="col-12 col-lg-6">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>
            </div>

            <div class="border-top pt-3 mt-4">
                <h3 class="h6 mb-1">Preferences</h3>
                <p class="text-muted small mb-3">These criteria can be changed later from your account.</p>

                @php
                    $selectedCities = old('cities', []);
                    $selectedAgeGroups = old('age_groups', []);
                    $selectedPeriodNames = old('period_names', []);
                @endphp

                <div class="mb-3">
                    <x-input-label for="cities" value="Preferred cities" />
                    <select id="cities" name="cities[]" class="form-select" multiple data-select-enhanced data-placeholder="Preferred cities">
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" @selected(in_array($city, $selectedCities, true))>{{ $city }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('cities')" />
                </div>

                <div class="mb-3">
                    <x-input-label for="age_groups" value="Preferred age groups" />
                    <select id="age_groups" name="age_groups[]" class="form-select" multiple data-select-enhanced data-placeholder="Preferred age groups">
                        @foreach ($ageGroups as $key => $group)
                            <option value="{{ $key }}" @selected(in_array($key, $selectedAgeGroups, true))>
                                {{ $group['label'] }} ({{ $group['min'] }}-{{ $group['max'] }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('age_groups')" />
                </div>

                <div class="mb-3">
                    <x-input-label for="period_names" value="Preferred periods" />
                    <select id="period_names" name="period_names[]" class="form-select" multiple data-select-enhanced data-placeholder="Preferred periods">
                        @foreach ($periods as $period)
                            <option value="{{ $period }}" @selected(in_array($period, $selectedPeriodNames, true))>
                                {{ $period }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('period_names')" />
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
