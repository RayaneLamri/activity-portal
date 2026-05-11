<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Admin Registrations</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.exports.upcoming-activities') }}" class="btn app-btn-secondary">
                    <i class="fa-solid fa-download me-1"></i>
                    Export Future Activities
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-card app-card-settings shadow-sm p-4 mb-4">
        <div class="app-card-body">
            <form method="GET" action="{{ route('admin.registrations.index') }}" class="row g-3 align-items-end" data-live-filter-form>
                <div class="col-12 col-lg-4">
                    <input id="search" type="search" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Search camp, reference, location">
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <select id="city" name="city" class="form-select">
                        <option value="">All cities</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" @selected(($filters['city'] ?? null) === $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <input id="from" type="date" name="from" class="form-control" value="{{ $filters['from'] ?? '' }}" aria-label="From date">
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <input id="until" type="date" name="until" class="form-control" value="{{ $filters['until'] ?? '' }}" aria-label="Until date">
                </div>

                <div class="col-12 col-md-3 col-lg-2">
                    <select id="activity_status" name="activity_status" class="form-select">
                        <option value="">All statuses</option>
                        <option value="active" @selected(($filters['activity_status'] ?? null) === 'active')>Active</option>
                        <option value="inactive" @selected(($filters['activity_status'] ?? null) === 'inactive')>Inactive</option>
                    </select>
                </div>

                <div class="col-auto">
                    <button type="button" class="btn app-btn-secondary" data-live-filter-reset>Reset</button>
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
