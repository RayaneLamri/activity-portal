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

                        @php
                            $selectedCities = old('cities', $preference?->cityList() ?? []);
                            $selectedAgeGroups = old('age_groups', $preference?->ageGroupList() ?? []);
                            $selectedPeriodNames = old('period_names', $preference?->periodNameList() ?? []);
                        @endphp

                        <div class="mb-3">
                            <x-input-label for="cities" value="Preferred cities" />
                            <select id="cities" name="cities[]" class="form-select" multiple data-select-enhanced data-placeholder="Preferred cities">
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}" @selected(in_array($city, $selectedCities, true))>{{ $city }}</option>
                                @endforeach
                            </select>
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
