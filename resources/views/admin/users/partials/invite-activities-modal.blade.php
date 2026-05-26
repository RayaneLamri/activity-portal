<div class="modal-header">
    <div>
        <h5 class="modal-title mb-1">Invite {{ $user->name }}</h5>
        <div class="text-muted small">
            Filter activities before sending an invitation.
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <form
        method="GET"
        action="{{ route('admin.users.invite-options', $user) }}"
        class="row g-3 align-items-start mb-4"
        data-user-invite-filter-form
    >
        <input type="hidden" name="filtered" value="1">
        <input type="hidden" name="results_only" value="1">
        @php
            $selectedCities = $filters['cities'] ?? [];
            $selectedAgeGroups = $filters['age_groups'] ?? [];
            $selectedPeriodNames = $filters['period_names'] ?? [];
            $preferredCities = $preference?->cityList() ?? [];
            $preferredAgeGroups = $preference?->ageGroupList() ?? [];
            $preferredPeriodNames = $preference?->periodNameList() ?? [];
        @endphp

        <div class="col-12 col-lg-3">
            <label for="invite-search-{{ $user->id }}" class="form-label small text-muted">Search</label>
            <input
                id="invite-search-{{ $user->id }}"
                type="search"
                name="search"
                class="form-control"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="Camp, reference, location"
            >
        </div>

        <div class="col-12 col-md-4 col-lg-2">
            <label for="invite-cities-{{ $user->id }}" class="form-label small text-muted">Cities</label>
            <select
                id="invite-cities-{{ $user->id }}"
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

        <div class="col-12 col-md-4 col-lg-2">
            <label for="invite-age-groups-{{ $user->id }}" class="form-label small text-muted">Age</label>
            <select
                id="invite-age-groups-{{ $user->id }}"
                name="age_groups[]"
                class="form-select"
                multiple
                data-select-enhanced
                data-placeholder="Age"
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

        <div class="col-12 col-md-4 col-lg-2">
            <label for="invite-periods-{{ $user->id }}" class="form-label small text-muted">Periods</label>
            <select
                id="invite-periods-{{ $user->id }}"
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

        <div class="col-12 col-lg-auto align-self-center">
            <div class="form-check mb-2">
                <input type="checkbox" name="match_preferences" value="1" class="form-check-input" id="invite-match-preferences-{{ $user->id }}" data-match-preferences @checked((bool) ($filters['match_preferences'] ?? false))>
                <label class="form-check-label" for="invite-match-preferences-{{ $user->id }}">Match preferences</label>
            </div>
        </div>

        <div class="col-auto align-self-center">
            <button
                type="button"
                class="btn app-btn-secondary"
                data-user-invite-filter-reset
            >
                Clear
            </button>
        </div>
    </form>
    <div data-user-invite-results>
        @include('admin.users.partials.invite-activities-results')
    </div>
</div>
