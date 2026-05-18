<x-app-layout>
    <x-slot name="header">
        <h1 class="app-page-title mb-4">My Preferences</h1>
    </x-slot>

    <div class="row g-4 settings-section">
        <div class="col-12 col-md-4">
            <h3 class="section-title">Matching</h3>
            <div class="section-intro">
                Saved criteria used by the activity catalog when you enable preference matching.
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="app-card-body">
                    <form method="POST" action="{{ route('preferences.update') }}" class="settings-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <x-input-label for="city" value="Preferred city" />
                            <select id="city" name="city" class="form-select">
                                <option value="">No preference</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}" @selected(old('city', $preference?->city) === $city)>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <x-input-label for="min_age" value="Preferred minimum age" />
                                <x-text-input id="min_age" name="min_age" type="number" :value="old('min_age', $preference?->min_age)" />
                            </div>
                            <div class="col-md-6">
                                <x-input-label for="max_age" value="Preferred maximum age" />
                                <x-text-input id="max_age" name="max_age" type="number" :value="old('max_age', $preference?->max_age)" />
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <x-input-label for="starts_on" value="Available from" />
                                <x-text-input id="starts_on" name="starts_on" type="date" :value="old('starts_on', optional($preference?->starts_on)->format('Y-m-d'))" />
                            </div>
                            <div class="col-md-6">
                                <x-input-label for="ends_on" value="Available until" />
                                <x-text-input id="ends_on" name="ends_on" type="date" :value="old('ends_on', optional($preference?->ends_on)->format('Y-m-d'))" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-primary-button>Save Preferences</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>