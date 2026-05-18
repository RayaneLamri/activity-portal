<div class="modal-header">
    <div>
        <h5 class="modal-title mb-1">Invite {{ $user->name }}</h5>
        <div class="text-muted small">
            Activities are prefiltered from this user's saved preferences.
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <form
        method="GET"
        action="{{ route('admin.users.invite-options', $user) }}"
        class="row g-3 align-items-end mb-4"
        data-user-invite-filter-form
    >
        <input type="hidden" name="filtered" value="1">
        <input type="hidden" name="results_only" value="1">

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
            <label for="invite-city-{{ $user->id }}" class="form-label small text-muted">City</label>
            <select id="invite-city-{{ $user->id }}" name="city" class="form-select">
                <option value="">Any city</option>
                @foreach ($cities as $city)
                    <option value="{{ $city }}" @selected(($filters['city'] ?? null) === $city)>
                        {{ $city }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-4 col-lg-1">
            <label for="invite-min-age-{{ $user->id }}" class="form-label small text-muted">Min age</label>
            <input
                id="invite-min-age-{{ $user->id }}"
                type="number"
                name="min_age"
                class="form-control"
                value="{{ $filters['min_age'] ?? '' }}"
                min="0"
                max="99"
            >
        </div>

        <div class="col-6 col-md-4 col-lg-1">
            <label for="invite-max-age-{{ $user->id }}" class="form-label small text-muted">Max age</label>
            <input
                id="invite-max-age-{{ $user->id }}"
                type="number"
                name="max_age"
                class="form-control"
                value="{{ $filters['max_age'] ?? '' }}"
                min="0"
                max="99"
            >
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <label for="invite-from-{{ $user->id }}" class="form-label small text-muted">Available from</label>
            <input
                id="invite-from-{{ $user->id }}"
                type="date"
                name="starts_on"
                class="form-control"
                value="{{ $filters['starts_on'] ?? '' }}"
            >
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <label for="invite-until-{{ $user->id }}" class="form-label small text-muted">Available until</label>
            <input
                id="invite-until-{{ $user->id }}"
                type="date"
                name="ends_on"
                class="form-control"
                value="{{ $filters['ends_on'] ?? '' }}"
            >
        </div>

        <div class="col-auto">
            <button
                type="button"
                class="btn app-btn-secondary"
                data-user-invite-filter-reset
                data-reset-url="{{ route('admin.users.invite-options', $user) }}"
            >
                Reset
            </button>
        </div>
    </form>

    <div data-user-invite-results>
        @include('admin.users.partials.invite-activities-results')
    </div>
</div>
