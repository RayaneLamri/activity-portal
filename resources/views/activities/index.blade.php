<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Activities</h1>
            </div>
            <div class="col-auto">
                @if ($preference)
                    <div class="page-utilities">
                        <div class="badge bg-success">Saved preference: {{ $preference->city ?: 'Any city' }}</div>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="app-card app-card-settings shadow-sm p-4 mb-4">
        <div class="app-card-body">
            <form method="GET" action="{{ route('activities.index') }}" class="row g-3 align-items-end" data-live-activity-filter-form>
                <div class="col-12 col-md-3">
                    <x-input-label for="city" value="City" />
                    <select id="city" name="city" class="form-select">
                        <option value="">Any city</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" @selected(($filters['city'] ?? null) === $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <x-input-label for="age" value="Age" />
                    <x-text-input id="age" name="age" type="number" :value="$filters['age'] ?? ''" />
                </div>

                <div class="col-6 col-md-2">
                    <x-input-label for="from" value="From" />
                    <x-text-input id="from" name="from" type="date" :value="$filters['from'] ?? ''" />
                </div>

                <div class="col-6 col-md-2">
                    <x-input-label for="until" value="Until" />
                    <x-text-input id="until" name="until" type="date" :value="$filters['until'] ?? ''" />
                </div>

                <div class="col-12 col-md-auto">
                    <div class="form-check mb-2">
                        <input type="checkbox" name="match_preferences" value="1" class="form-check-input" id="match_preferences" @checked((bool) ($filters['match_preferences'] ?? false))>
                        <label class="form-check-label" for="match_preferences">Match preferences</label>
                    </div>
                </div>

                <div class="col-12 col-md-auto d-flex gap-2">
                    <button type="button" class="btn app-btn-secondary" data-live-activity-filter-reset>Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div data-activity-results>
        @include('activities.partials.results')
    </div>

</x-app-layout>