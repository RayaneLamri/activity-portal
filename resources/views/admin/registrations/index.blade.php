<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Admin Registrations</h1>
                <div class="text-muted">Track upcoming activities, requests, invitations, participants, and capacity.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.exports.upcoming-activities') }}" class="btn app-btn-primary">
                    <i class="fa-solid fa-download me-1"></i>
                    Export Future Activities
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-card app-card-settings shadow-sm p-4 mb-4">
        <div class="app-card-body">
            <form method="GET" action="{{ route('admin.registrations.index') }}" class="row g-3 align-items-end" data-live-filter-form>
                @php
                    $selectedCities = $filters['cities'] ?? [];
                    $selectedPeriodNames = $filters['period_names'] ?? [];
                    $selectedAgeGroups = $filters['age_groups'] ?? [];
                @endphp

                <div class="col-12 col-lg-4">
                    <input id="search" type="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Search camp, reference, location">
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <select id="cities" name="cities[]" class="form-select" multiple data-select-enhanced data-placeholder="Cities">
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" @selected(in_array($city, $selectedCities, true))>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <select id="age_groups" name="age_groups[]" class="form-select" multiple data-select-enhanced data-placeholder="Age groups">
                        @foreach ($ageGroups as $key => $group)
                            <option value="{{ $key }}" @selected(in_array($key, $selectedAgeGroups, true))>
                                {{ $group['label'] }} ({{ $group['min'] }}-{{ $group['max'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <select id="period_names" name="period_names[]" class="form-select" multiple data-select-enhanced data-placeholder="Periods">
                        @foreach ($periods as $period)
                            <option value="{{ $period }}" @selected(in_array($period, $selectedPeriodNames, true))>{{ $period }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-lg-auto ms-lg-auto">
                    <button type="button" class="btn app-btn-secondary" data-live-filter-reset>Clear</button>
                </div>
            </form>
        </div>
    </div>

    <div data-overview-results>
        @include('admin.registrations.partials.overview-results')
    </div>

    <div class="modal fade" id="registrations-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" data-registrations-modal-content></div>
        </div>
    </div>

</x-app-layout>
