<section>
    <h2 class="section-title mb-2">Preferences</h2>
    <p class="section-intro text-muted mb-4">Saved criteria used to match activities with your profile.</p>

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

        <div class="d-flex align-items-center gap-3 mt-4">
            <x-primary-button>Save Preferences</x-primary-button>

            @if (session('status') === 'Preferences updated.')
                <span class="text-muted small">Saved.</span>
            @endif
        </div>
    </form>
</section>
