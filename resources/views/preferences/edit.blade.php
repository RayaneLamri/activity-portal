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
                            <x-input-label for="preferred_city" value="Preferred city" />
                            <select id="preferred_city" name="preferred_city" class="form-select">
                                <option value="">No preference</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}" @selected(old('preferred_city', $preference?->preferred_city) === $city)>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <x-input-label for="preferred_min_age" value="Preferred minimum age" />
                                <x-text-input id="preferred_min_age" name="preferred_min_age" type="number" :value="old('preferred_min_age', $preference?->preferred_min_age)" />
                            </div>
                            <div class="col-md-6">
                                <x-input-label for="preferred_max_age" value="Preferred maximum age" />
                                <x-text-input id="preferred_max_age" name="preferred_max_age" type="number" :value="old('preferred_max_age', $preference?->preferred_max_age)" />
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <x-input-label for="available_from" value="Available from" />
                                <x-text-input id="available_from" name="available_from" type="date" :value="old('available_from', optional($preference?->available_from)->format('Y-m-d'))" />
                            </div>
                            <div class="col-md-6">
                                <x-input-label for="available_until" value="Available until" />
                                <x-text-input id="available_until" name="available_until" type="date" :value="old('available_until', optional($preference?->available_until)->format('Y-m-d'))" />
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