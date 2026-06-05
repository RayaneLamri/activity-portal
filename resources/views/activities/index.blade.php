<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Activities</h1>
                <div class="text-muted">Browse upcoming activities and send registration requests.</div>
            </div>
        </div>
    </x-slot>

    <div class="app-card app-card-settings shadow-sm p-4 mb-4">
        <div class="app-card-body">
            <form method="GET" action="{{ route('activities.index') }}" class="row g-3 align-items-center" data-live-activity-filter-form>
                @php
                    $selectedCities = $filters['cities'] ?? $preferredCities;
                    $selectedAgeMin = $filters['min_age'] ?? ($preferredMinAge ?? 3);
                    $selectedAgeMax = $filters['max_age'] ?? ($preferredMaxAge ?? 18);
                    $selectedPeriodNames = $filters['period_names'] ?? $preferredPeriodNames;
                @endphp

                <div class="col-12 col-lg-3">
                    <input
                        id="search"
                        type="search"
                        name="search"
                        class="form-control"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search camp, reference, location"
                    >
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <x-input-label for="cities[]" value="Cities" />
                    <select
                        id="cities"
                        name="cities[]"
                        class="form-select"
                        multiple
                        data-select-enhanced
                        data-placeholder="Cities"
                        data-preference-values='@json($preferredCities)'
                    >
                        @foreach ($cities as $city)
                            <option
                                value="{{ $city }}"
                                data-preferred="{{ in_array($city, $preferredCities, true) ? '1' : '0' }}"
                                @selected(in_array($city, $selectedCities, true))
                            >
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <div class="age-range-filter">
                        <div class="age-range-filter__header">
                            <span>Age</span>
                            <span class="age-range-filter__value" data-age-range-label>{{ $selectedAgeMin }} - {{ $selectedAgeMax }}</span>
                        </div>
                        <div
                            class="age-range-filter__slider"
                            data-age-range-slider
                            data-min="3"
                            data-max="18"
                            data-selected-min="{{ $selectedAgeMin }}"
                            data-selected-max="{{ $selectedAgeMax }}"
                            data-preference-min="{{ $preferredMinAge }}"
                            data-preference-max="{{ $preferredMaxAge }}"
                        ></div>
                        <div class="age-range-filter__ends">
                            <span>3</span>
                            <span>18</span>
                        </div>
                        <input type="hidden" name="min_age" value="{{ $selectedAgeMin }}" data-age-range-min>
                        <input type="hidden" name="max_age" value="{{ $selectedAgeMax }}" data-age-range-max>
                    </div>
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <x-input-label for="period_names" value="Periods" />
                    <select
                        id="period_names"
                        name="period_names[]"
                        class="form-select"
                        multiple
                        data-select-enhanced
                        data-placeholder="Periods"
                        data-preference-values='@json($preferredPeriodNames)'
                    >
                        @foreach ($periods as $period)
                            <option
                                value="{{ $period }}"
                                data-preferred="{{ in_array($period, $preferredPeriodNames, true) ? '1' : '0' }}"
                                @selected(in_array($period, $selectedPeriodNames, true))
                            >
                                {{ $period }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-auto">
                    <div class="form-check mb-2">
                        <input type="hidden" name="match_preferences" value="0">
                        <input type="checkbox" name="match_preferences" value="1" class="form-check-input" id="match_preferences" data-match-preferences @checked((bool) ($filters['match_preferences'] ?? false))>
                        <label class="form-check-label" for="match_preferences">Match preferences</label>
                    </div>
                </div>

                <div class="col-12 col-md-auto d-flex gap-2">
                    <button type="button" class="btn app-btn-secondary" data-live-activity-filter-reset>Clear</button>
                </div>
            </form>
        </div>
    </div>

    <div data-activity-results>
        @include('activities.partials.results')
    </div>
</x-app-layout>
