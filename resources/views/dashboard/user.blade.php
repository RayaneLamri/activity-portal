<x-app-layout>
    <x-slot name="header">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Overview</h1>
                <div class="text-muted">Follow your activity workflow from preferences to registrations.</div>
            </div>
        </div>
    </x-slot>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-4">
            <div class="app-card app-card-basic shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h3 class="app-card-title mb-2">1. Preferences</h3>
                    <div class="text-muted mb-3">Define the criteria used to match activities.</div>
                    <a class="btn app-btn-secondary" href="{{ route('profile.edit') }}">Manage account</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="app-card app-card-basic shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h3 class="app-card-title mb-2">2. Activities</h3>
                    <div class="text-muted mb-3">Browse upcoming activities and send a request.</div>
                    <a class="btn app-btn-secondary" href="{{ route('activities.index') }}">Browse activities</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="app-card app-card-basic shadow-sm h-100">
                <div class="app-card-body p-3 p-lg-4">
                    <h3 class="app-card-title mb-2">3. Registrations</h3>
                    <div class="text-muted mb-3">Follow requests and answer invitations.</div>
                    <a class="btn app-btn-secondary" href="{{ route('my-registrations.index') }}">My registrations</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-6">
            <div class="app-card app-card-orders-table shadow-sm h-100">
                <div class="app-card-header p-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <h4 class="app-card-title">Matching Activities</h4>
                        </div>
                        <div class="col-auto">
                            <div class="card-header-action">
                                <a href="{{ route('activities.index') }}">View all</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-card-body p-0">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left">
                            <thead>
                                <tr>
                                    <th class="cell">Activity</th>
                                    <th class="cell">Location</th>
                                    <th class="cell">Period</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($matchedActivities as $activity)
                                    <tr>
                                        <td class="cell fw-semibold">{{ $activity->title }}</td>
                                        <td class="cell">
                                            <span class="d-block">{{ $activity->location_name }}</span>
                                            <span class="note">{{ $activity->city ?: 'No city' }}</span>
                                        </td>
                                        <td class="cell">
                                            <span>{{ $activity->period_name ?? 'No period' }}</span>
                                            <span class="note">{{ $activity->starts_on?->format('d/m/Y') }} - {{ $activity->ends_on?->format('d/m/Y') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="cell text-muted" colspan="3">No matching activities found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="app-card app-card-orders-table shadow-sm h-100">
                <div class="app-card-header p-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <h4 class="app-card-title">Active Registrations</h4>
                        </div>
                        <div class="col-auto">
                            <div class="card-header-action">
                                <a href="{{ route('my-registrations.index') }}">View all</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-card-body p-0">
                    <div class="table-responsive">
                        <table class="table app-table-hover mb-0 text-left">
                            <thead>
                                <tr>
                                    <th class="cell">Activity</th>
                                    <th class="cell text-center">Status</th>
                                    <th class="cell">Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activeRegistrations as $registration)
                                    <tr>
                                        <td class="cell fw-semibold">{{ $registration->activity->title }}</td>
                                        <td class="cell text-center">
                                            <span class="badge {{ $registration->statusBadgeClass() }}">
                                                {{ $registration->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="cell">
                                            <span>{{ $registration->date?->format('d/m/Y') }}</span>
                                            <span class="note">{{ $registration->date?->format('H:i') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="cell text-muted" colspan="3">No active registrations.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
