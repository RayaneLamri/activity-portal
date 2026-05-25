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
            <form method="GET" action="{{ route('activities.index') }}" class="row g-3 align-items-end" data-live-activity-filter-form>
                @php
                    $selectedCities = $filters['cities'] ?? [];
                    $selectedAgeGroups = $filters['age_groups'] ?? [];
                    $selectedPeriodNames = $filters['period_names'] ?? [];
                    $preferredCities = $preference?->cityList() ?? [];
                    $preferredAgeGroups = $preference?->ageGroupList() ?? [];
                    $preferredPeriodNames = $preference?->periodNameList() ?? [];
                @endphp

                <div class="col-12 col-md-3">
                    <x-input-label for="cities" value="Cities" />
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

                <div class="col-12 col-md-3">
                    <x-input-label for="age_groups" value="Age groups" />
                    <select
                        id="age_groups"
                        name="age_groups[]"
                        class="form-select"
                        multiple
                        data-select-enhanced
                        data-placeholder="Age groups"
                        data-preference-values='@json($preferredAgeGroups)'
                    >
                        @foreach ($ageGroups as $key => $group)
                            <option
                                value="{{ $key }}"
                                data-preferred="{{ in_array($key, $preferredAgeGroups, true) ? '1' : '0' }}"
                                @selected(in_array($key, $selectedAgeGroups, true))
                            >
                                {{ $group['label'] }} ({{ $group['min'] }}-{{ $group['max'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
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
